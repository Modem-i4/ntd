<?php
// app/api/Controllers/GameController.php
require_once __DIR__ . '/TeacherController.php';
require __DIR__ . '/../Enum/checkpoints.php';

class GameController
{
    public function __construct(private PDO $pdo) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    
    /** POST /api/games/create  {scenario_slug, fid} */
    public function create(): array {
        $in = $this->readJsonOrPost();
        $scenario = trim((string)($in['scenario_slug'] ?? ''));
        $fid = isset($in['fid']) ? trim((string)$in['fid']) : null;
        if ($scenario === '') throw new RuntimeException('scenario_slug is required');

        $st = $this->pdo->prepare('SELECT slug FROM scenarios WHERE slug = ?');
        $st->execute([$scenario]);
        if (!$st->fetch()) throw new RuntimeException('Scenario not found');

        $tc = new TeacherController($this->pdo);
        $teacherCode = $tc->ensureTeacherByFid($fid);

        for ($i = 0; $i < 10; $i++) {
            $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            try {
                $ins = $this->pdo->prepare('INSERT INTO games (code, teacher_code, scenario_slug) VALUES (?, ?, ?)');
                $ins->execute([$code, $teacherCode, $scenario]);
                return ['code' => $code, 'scenario_slug' => $scenario, 'teacher_code' => $teacherCode];
            } catch (PDOException $e) {
                if (($e->errorInfo[1] ?? null) !== 1062) throw $e;
            }
        }
        throw new RuntimeException('Failed to generate unique game code');
    }

    /** GET /api/games/scenario?game_code=XXXXXX */
    public function scenario(): array {
        $code = trim((string)($_GET['game_code'] ?? ''));
        if ($code === '') throw new RuntimeException('game_code is required');

        $st = $this->pdo->prepare('SELECT scenario_slug FROM games WHERE code = ?');
        $st->execute([$code]);
        $row = $st->fetch();
        if (!$row) throw new RuntimeException('Game not found');

        return [
            'scenario_slug' => $row['scenario_slug'],
            'checkpoints' => Scenario::stagesBySlug($row['scenario_slug'])
        ];
    }

    /**
     * POST /api/games/update
     * Body JSON/form: { user_code, game_code, progress?, score? }
     *  - progress: JSON-об’єкт етапів {stageA:1, stageB:0,...} (мердж за ключами)
     *  - score: ціле (перезапис)
     */
    public function gamesUpdate(): array {
        $in = $this->readJsonOrPost();
        $user  = trim((string)($in['user_code'] ?? ''));
        $game  = trim((string)($in['game_code'] ?? ''));
        $score = array_key_exists('score', $in) ? (int)$in['score'] : null;
        $progressAssoc = null;

        if (array_key_exists('progress', $in)) {
            $progressAssoc = is_array($in['progress'])
                ? $in['progress']
                : (is_string($in['progress']) ? json_decode($in['progress'], true) : null);
            if (!is_array($progressAssoc)) throw new RuntimeException('progress must be object');
        }

        if ($user === '' || $game === '') {
            throw new RuntimeException('user_code and game_code are required');
        }

        $this->pdo->beginTransaction();
        try {
            // існує гра?
            $g = $this->pdo->prepare('SELECT 1 FROM games WHERE code = ?');
            $g->execute([$game]);
            if (!$g->fetch()) throw new RuntimeException('Game not found');

            // шукаємо активну сесію користувача для гри (ended_at IS NULL)
            $sel = $this->pdo->prepare(
                'SELECT id, progress, score FROM sessions
                  WHERE user_code = ? AND game_code = ? AND ended_at IS NULL
              ORDER BY started_at DESC, id DESC
                 LIMIT 1'
            );
            $sel->execute([$user, $game]);
            $sess = $sel->fetch();

            if (!$sess) {
                $ins = $this->pdo->prepare(
                    'INSERT INTO sessions (user_code, game_code, started_at, game_started_at)
                     VALUES (?, ?, NOW(), NOW())'
                );
                $ins->execute([$user, $game]);
                $session_id = (int)$this->pdo->lastInsertId();
                $currProgressJson = null;
                $currScore        = null;
            } else {
                $session_id       = (int)$sess['id'];
                $currProgressJson = $sess['progress'] ?? null;
                $currScore        = $sess['score'] ?? null;
            }

            // готуємо оновлення
            $newProgressJson = $currProgressJson;
            if ($progressAssoc !== null) {
                $incoming = json_encode($progressAssoc, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
                $newProgressJson = $this->mergeProgressJson($currProgressJson, $incoming);
            }
            $newScore = $currScore;
            if ($score !== null) $newScore = $score;

            // UPDATE
            $sql = 'UPDATE sessions SET ';
            $set = [];
            $args = [];
            if ($progressAssoc !== null) { $set[] = 'progress = ?'; $args[] = $newProgressJson; }
            if ($score !== null)         { $set[] = 'score = ?';    $args[] = $newScore; }
            if ($set) {
                $sql .= implode(', ', $set) . ' WHERE id = ?';
                $args[] = $session_id;
                $upd = $this->pdo->prepare($sql);
                $upd->execute($args);
            }

            $this->pdo->commit();

            return [
                'session_id' => $session_id,
                'user_code'  => $user,
                'game_code'  => $game,
                'progress'   => $newProgressJson ? json_decode($newProgressJson, true) : null,
                'score'      => $newScore !== null ? (int)$newScore : null,
            ];
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    /** GET /api/games/stats?game_code=XXXXXX&limit=10 */
    public function stats(): array {
        $code  = trim((string)($_GET['game_code'] ?? ''));
        $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;
        if ($code === '') throw new RuntimeException('game_code is required');

        // перший старт + кількість гравців
        $st = $this->pdo->prepare(
            'SELECT MIN(started_at) AS first_session_started_at,
                    COUNT(DISTINCT user_code) AS players_count
               FROM sessions
              WHERE game_code = ?'
        );
        $st->execute([$code]);
        $base = $st->fetch() ?: ['first_session_started_at'=>null,'players_count'=>0];

        // лідери: MAX(score) по user_code
        $leadersSt = $this->pdo->prepare(
            'SELECT user_code, MAX(score) AS best_score
            FROM sessions
            WHERE game_code = ?
        GROUP BY user_code
        ORDER BY (MAX(score) IS NULL) ASC,  -- спочатку ті, де score не NULL
                    MAX(score) DESC,
                    user_code ASC
            LIMIT '.(int)$limit
        );
        $leadersSt->execute([$code]);

        $leaders = [];
        while ($r = $leadersSt->fetch()) {
            if ($r['best_score'] === null) continue;
            $leaders[] = ['user_code'=>$r['user_code'], 'score'=>(int)$r['best_score']];
        }

        // етапи: беремо останню сесію з progress для кожного user_code
        $latest = $this->pdo->prepare(
            'SELECT s1.user_code, s1.progress
               FROM sessions s1
               JOIN (
                     SELECT user_code, MAX(started_at) AS mx, MAX(id) AS mid
                       FROM sessions
                      WHERE game_code = ? AND progress IS NOT NULL
                   GROUP BY user_code
               ) t ON t.user_code = s1.user_code
                  AND s1.game_code = ?
                  AND ((s1.started_at = t.mx AND s1.id = t.mid) OR s1.id = t.mid)
              WHERE s1.progress IS NOT NULL'
        );
        $latest->execute([$code, $code]);

        $stageCounts = [];
        $totalWithProgress = 0;
        while ($row = $latest->fetch()) {
            $totalWithProgress++;
            $prog = json_decode($row['progress'], true) ?: [];
            foreach ($prog as $stage => $flag) {
                $val = (int)!!$flag;
                if (!array_key_exists($stage, $stageCounts)) $stageCounts[$stage] = 0;
                $stageCounts[$stage] += $val;
            }
        }

        return [
            'started_at' => $base['first_session_started_at'],
            'players_count'            => (int)$base['players_count'],
            'leaders'                  => $leaders,
            'stages'                   => [
                'counts' => $stageCounts,
                'total_players_with_progress' => $totalWithProgress,
            ],
        ];
    }

    /* ================= helpers ================ */

    private function readJsonOrPost(): array {
        $ctype = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($ctype, 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            $data = json_decode($raw, true);
            if (!is_array($data)) throw new RuntimeException('Invalid JSON');
            return $data;
        }
        return $_POST; // form-data / x-www-form-urlencoded
    }

    /** Мердж JSON-об’єктів 1 рівня за ключами (нові поверх старих) */
    private function mergeProgressJson(?string $oldJson, ?string $newJson): ?string {
        if (!$oldJson) return $newJson;
        if (!$newJson) return $oldJson;
        $old = json_decode($oldJson, true) ?: [];
        $new = json_decode($newJson, true) ?: [];
        foreach ($new as $k=>$v) $old[$k] = $v;
        return json_encode($old, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    }
}
