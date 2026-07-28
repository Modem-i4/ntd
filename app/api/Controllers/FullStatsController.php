<?php

class FullStatsController {
    private const VALID_SESSION_SQL = "(s.score > 0 OR s.ended_at IS NOT NULL)";
    private const SCENARIOS = [
        'vitovt' => ['name' => 'Вітовт', 'title' => 'З\'їзд монархів у Луцьку'],
        'orsha' => ['name' => 'Орша', 'title' => 'Битва під Оршею'],
        'khotyn' => ['name' => 'Хотин', 'title' => 'Хотинська битва 1621'],
        'danylo' => ['name' => 'Данило', 'title' => 'Король Данило'],
        'kyiv' => ['name' => 'Ярмарок', 'title' => 'Ярмарок у Києві'],
        'orlyk' => ['name' => 'Орлик', 'title' => 'Конституція Пилипа Орлика'],
        'unr' => ['name' => 'УНР', 'title' => 'Від гетьманату до Директорії'],
        'lesya' => ['name' => 'Леся', 'title' => 'Леся Українка: слово і свобода'],
        'plast' => ['name' => 'Пласт', 'title' => 'Вчися діяти: організації поч. ХХ ст.'],
    ];

    public function __construct(private PDO $pdo) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function index(array $query = []): array {
        try {
            if (!$query) $query = $_GET;
            $group = $this->resolveGroup($query['group'] ?? 'week');

            return [
                'ok' => true,
                'meta' => [
                    'group' => $group,
                    'availableGroups' => ['week', 'month'],
                    'generatedAt' => gmdate('c'),
                ],
                'scenarios' => $this->scenarioDirectory(),
                'summary' => $this->summary(),
                'timeline' => $this->timeline($group),
                'scenarioUsageTimeline' => $this->scenarioUsageTimeline($group),
                'scenarioSessionStats' => $this->scenarioSessionStats(),
                'scenarioDurationStats' => $this->scenarioDurationStats(),
                'improvementByScenario' => $this->improvementByScenario(),
                'improvementDetails' => $this->improvementDetails(),
                'teacherActivityTimeline' => $this->teacherActivityTimeline($group),
                'indicators' => $this->indicators(),
            ];
        } catch (Throwable $e) {
            return [
                'ok' => false,
                'error' => 'full stats failed',
                'detail' => $e->getMessage(),
            ];
        }
    }

    public function json(array $query = []): string {
        if (!$query) $query = $_GET;
        return json_encode(
            $this->index($query),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function cached(array $query = []): array {
        if (!$query) $query = $_GET;
        $group = $this->resolveGroup($query['group'] ?? 'week');
        $cacheFile = $this->cacheFile($group);

        if (!is_file($cacheFile)) return $this->index(['group' => $group]);

        $raw = file_get_contents($cacheFile);
        if ($raw === false) return $this->index(['group' => $group]);

        $data = json_decode($raw, true);
        if (!is_array($data)) return $this->index(['group' => $group]);

        return $data;
    }

    public function makeCache(array $query = []): array {
        if (!$query) $query = $_GET;
        $group = $this->resolveGroup($query['group'] ?? 'week');
        $data = $this->index(['group' => $group]);
        $cacheFile = $this->cacheFile($group);
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0775, true);
        }

        file_put_contents(
            $cacheFile,
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        return $this->cached(['group' => $group]);
    }

    public function summary(): array {
        $uniquePlayers = (int)$this->pdo
            ->query("SELECT COUNT(DISTINCT user_code) FROM sessions")
            ->fetchColumn();

        $lessons = (int)$this->pdo
            ->query("
                SELECT COUNT(*) FROM (
                    SELECT game_code
                    FROM sessions
                    GROUP BY game_code
                    HAVING COUNT(DISTINCT user_code) > 1
                ) t
            ")
            ->fetchColumn();

        $sessions = (int)$this->pdo
            ->query("
                SELECT COUNT(*)
                FROM sessions s
                WHERE " . self::VALID_SESSION_SQL . "
            ")
            ->fetchColumn();

        $uniqueTeachers = (int)$this->pdo
            ->query("
                SELECT COUNT(DISTINCT g.teacher_code)
                FROM games g
                JOIN (
                    SELECT game_code
                    FROM sessions
                    GROUP BY game_code
                    HAVING COUNT(DISTINCT user_code) > 1
                ) vl ON vl.game_code = g.code
                WHERE g.teacher_code IS NOT NULL
            ")
            ->fetchColumn();

        return [
            'uniquePlayers' => $uniquePlayers,
            'lessons' => $lessons,
            'sessions' => $sessions,
            'uniqueTeachers' => $uniqueTeachers,
        ];
    }

    public function timeline(string $group = 'week'): array {
        $group = $this->resolveGroup($group);

        $activePlayers = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('s.started_at', $group)} AS period_start,
                   COUNT(DISTINCT s.user_code) AS uniquePlayers
            FROM sessions s
            GROUP BY period_start
            ORDER BY period_start
        ");

        $newPlayers = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('first_started_at', $group)} AS period_start,
                   COUNT(*) AS newUniquePlayers
            FROM (
                SELECT user_code, MIN(started_at) AS first_started_at
                FROM sessions
                GROUP BY user_code
            ) first_players
            GROUP BY period_start
            ORDER BY period_start
        ");

        $lessons = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('first_started_at', $group)} AS period_start,
                   COUNT(*) AS lessons
            FROM ({$this->validLessonsSql()}) vl
            GROUP BY period_start
            ORDER BY period_start
        ");

        $sessions = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('s.started_at', $group)} AS period_start,
                   COUNT(*) AS sessions
            FROM sessions s
            WHERE " . self::VALID_SESSION_SQL . "
            GROUP BY period_start
            ORDER BY period_start
        ");

        $activeTeachers = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('vl.first_started_at', $group)} AS period_start,
                   COUNT(DISTINCT g.teacher_code) AS uniqueTeachers
            FROM ({$this->validLessonsSql()}) vl
            JOIN games g ON g.code = vl.game_code
            WHERE g.teacher_code IS NOT NULL
            GROUP BY period_start
            ORDER BY period_start
        ");

        $newTeachers = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('first_lesson_at', $group)} AS period_start,
                   COUNT(*) AS newUniqueTeachers
            FROM (
                SELECT g.teacher_code, MIN(vl.first_started_at) AS first_lesson_at
                FROM ({$this->validLessonsSql()}) vl
                JOIN games g ON g.code = vl.game_code
                WHERE g.teacher_code IS NOT NULL
                GROUP BY g.teacher_code
            ) first_teachers
            GROUP BY period_start
            ORDER BY period_start
        ");

        $rows = $this->mergePeriodRows([
            $activePlayers,
            $newPlayers,
            $lessons,
            $sessions,
            $activeTeachers,
            $newTeachers,
        ], $group);

        $playersTotal = 0;
        $lessonsTotal = 0;
        $sessionsTotal = 0;
        $teachersTotal = 0;

        foreach ($rows as &$row) {
            $playersTotal += (int)$row['newUniquePlayers'];
            $lessonsTotal += (int)$row['lessons'];
            $sessionsTotal += (int)$row['sessions'];
            $teachersTotal += (int)$row['newUniqueTeachers'];

            $row['uniquePlayersCumulative'] = $playersTotal;
            $row['lessonsCumulative'] = $lessonsTotal;
            $row['sessionsCumulative'] = $sessionsTotal;
            $row['uniqueTeachersCumulative'] = $teachersTotal;

            unset($row['newUniquePlayers'], $row['newUniqueTeachers']);
        }
        unset($row);

        return [
            'group' => $group,
            'xKey' => 'periodLabel',
            'rows' => $rows,
        ];
    }

    public function scenarioUsageTimeline(string $group = 'week'): array {
        $group = $this->resolveGroup($group);

        $rows = $this->pdo->query("
            SELECT {$this->periodStartExpr('s.started_at', $group)} AS period_start,
                   g.scenario_slug AS scenarioSlug,
                   COUNT(DISTINCT s.game_code) AS lessons,
                   COUNT(*) AS sessions,
                   COUNT(DISTINCT s.user_code) AS uniquePlayers,
                   COUNT(DISTINCT g.teacher_code) AS uniqueTeachers
            FROM sessions s
            JOIN games g ON g.code = s.game_code
            JOIN (
                SELECT game_code
                FROM sessions
                GROUP BY game_code
                HAVING COUNT(DISTINCT user_code) > 1
            ) vl ON vl.game_code = s.game_code
            WHERE " . self::VALID_SESSION_SQL . "
            GROUP BY period_start, g.scenario_slug
            ORDER BY period_start, g.scenario_slug
        ")->fetchAll();

        $items = [];
        $scenarios = [];
        $wideLessons = [];
        $wideSessions = [];

        foreach ($rows as $row) {
            $periodStart = $this->dateString($row['period_start']);
            $scenarioSlug = (string)$row['scenarioSlug'];
            $scenarios[$scenarioSlug] = true;

            $item = [
                'periodStart' => $periodStart,
                'periodLabel' => $this->periodLabel($periodStart, $group),
                'scenarioSlug' => $scenarioSlug,
                'scenarioName' => $this->scenarioName($scenarioSlug),
                'lessons' => (int)$row['lessons'],
                'sessions' => (int)$row['sessions'],
                'uniquePlayers' => (int)$row['uniquePlayers'],
                'uniqueTeachers' => (int)$row['uniqueTeachers'],
            ];

            $items[] = $item;

            if (!isset($wideLessons[$periodStart])) {
                $wideLessons[$periodStart] = [
                    'periodStart' => $periodStart,
                    'periodLabel' => $item['periodLabel'],
                ];
            }
            $wideLessons[$periodStart][$scenarioSlug] = (int)$row['lessons'];

            if (!isset($wideSessions[$periodStart])) {
                $wideSessions[$periodStart] = [
                    'periodStart' => $periodStart,
                    'periodLabel' => $item['periodLabel'],
                ];
            }
            $wideSessions[$periodStart][$scenarioSlug] = (int)$row['sessions'];
        }

        ksort($scenarios);
        foreach ($wideLessons as &$wideRow) {
            foreach (array_keys($scenarios) as $scenarioSlug) {
                $wideRow[$scenarioSlug] ??= 0;
            }
        }
        unset($wideRow);
        foreach ($wideSessions as &$wideRow) {
            foreach (array_keys($scenarios) as $scenarioSlug) {
                $wideRow[$scenarioSlug] ??= 0;
            }
        }
        unset($wideRow);

        return [
            'group' => $group,
            'metric' => 'sessions',
            'xKey' => 'periodLabel',
            'series' => array_keys($scenarios),
            'seriesLabels' => $this->scenarioLabels(array_keys($scenarios)),
            'rows' => $items,
            'wideLessons' => array_values($wideLessons),
            'wideSessions' => array_values($wideSessions),
        ];
    }

    public function scenarioSessionStats(): array {
        $rows = $this->pdo->query("
            SELECT g.scenario_slug AS scenarioSlug,
                   COUNT(*) AS sessions
            FROM sessions s
            JOIN games g ON g.code = s.game_code
            WHERE " . self::VALID_SESSION_SQL . "
            GROUP BY g.scenario_slug
            ORDER BY sessions DESC, g.scenario_slug
        ")->fetchAll();

        $total = array_sum(array_map(fn (array $row): int => (int)$row['sessions'], $rows));

        return array_map(function (array $row) use ($total): array {
            $sessions = (int)$row['sessions'];
            $slug = (string)$row['scenarioSlug'];

            return [
                'scenarioSlug' => $slug,
                'scenarioName' => $this->scenarioName($slug),
                'scenarioTitle' => $this->scenarioTitle($slug),
                'sessions' => $sessions,
                'share' => $total > 0 ? $sessions / $total : 0.0,
            ];
        }, $rows);
    }

    public function scenarioDurationStats(): array {
        $rows = $this->pdo->query("
            SELECT g.scenario_slug AS scenarioSlug,
                   COUNT(*) AS sessionsCount,
                   AVG(TIMESTAMPDIFF(SECOND, s.game_started_at, s.game_ended_at)) AS gameSeconds,
                   AVG(
                       TIMESTAMPDIFF(SECOND, s.started_at, s.ended_at)
                       - TIMESTAMPDIFF(SECOND, s.game_started_at, s.game_ended_at)
                   ) AS answerSeconds
            FROM sessions s
            JOIN games g ON g.code = s.game_code
            WHERE s.started_at IS NOT NULL
              AND s.ended_at IS NOT NULL
              AND s.game_started_at IS NOT NULL
              AND s.game_ended_at IS NOT NULL
              AND TIMESTAMPDIFF(SECOND, s.game_started_at, s.game_ended_at) >= 0
              AND TIMESTAMPDIFF(SECOND, s.started_at, s.ended_at) >= TIMESTAMPDIFF(SECOND, s.game_started_at, s.game_ended_at)
            GROUP BY g.scenario_slug
            ORDER BY (
                AVG(TIMESTAMPDIFF(SECOND, s.game_started_at, s.game_ended_at))
                + AVG(
                    TIMESTAMPDIFF(SECOND, s.started_at, s.ended_at)
                    - TIMESTAMPDIFF(SECOND, s.game_started_at, s.game_ended_at)
                )
            ) ASC, g.scenario_slug
        ")->fetchAll();

        return array_map(function (array $row): array {
            $slug = (string)$row['scenarioSlug'];
            $gameSeconds = (int)round((float)$row['gameSeconds']);
            $answerSeconds = (int)round((float)$row['answerSeconds']);

            return [
                'scenarioSlug' => $slug,
                'scenarioName' => $this->scenarioName($slug),
                'sessionsCount' => (int)$row['sessionsCount'],
                'gameSeconds' => $gameSeconds,
                'answerSeconds' => $answerSeconds,
                'totalSeconds' => $gameSeconds + $answerSeconds,
            ];
        }, $rows);
    }

    public function improvementByScenario(): array {
        $testRows = $this->pdo->query("
            WITH base_candidates AS (
                SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                       CASE WHEN topt.correct = 1 THEN 1.0 ELSE 0.0 END AS pre_raw,
                       ROW_NUMBER() OVER (
                           PARTITION BY s.user_code, at.scenario_slug, at.metric, at.test_n
                           ORDER BY s.started_at, at.id
                       ) AS rn
                FROM ans_test at
                JOIN sessions s ON s.id = at.session_id
                JOIN test_opts topt
                  ON topt.scenario_slug = at.scenario_slug
                 AND topt.metric = at.metric
                 AND topt.test_n = at.test_n
                 AND topt.number = at.first_option_n
            ),
            baseline AS (
                SELECT user_code, scenario_slug, metric, test_n, pre_raw
                FROM base_candidates
                WHERE rn = 1
            ),
            current_scored AS (
                SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                       CASE WHEN topt.correct = 1 THEN 1.0 ELSE 0.0 END AS post_raw
                FROM ans_test at
                JOIN sessions s ON s.id = at.session_id
                JOIN test_opts topt
                  ON topt.scenario_slug = at.scenario_slug
                 AND topt.metric = at.metric
                 AND topt.test_n = at.test_n
                 AND topt.number = at.final_option_n
                WHERE at.final_option_n IS NOT NULL
            ),
            joined AS (
                SELECT cs.scenario_slug, cs.metric, cs.user_code,
                       ((3.0 * b.pre_raw - 1.0) / 2.0) AS pre_corr_item,
                       ((3.0 * cs.post_raw - 1.0) / 2.0) AS post_corr_item,
                       b.pre_raw AS pre_raw,
                       cs.post_raw AS post_raw
                FROM current_scored cs
                JOIN baseline b
                  ON b.user_code = cs.user_code
                 AND b.scenario_slug = cs.scenario_slug
                 AND b.metric = cs.metric
                 AND b.test_n = cs.test_n
            )
            SELECT scenario_slug AS scenarioSlug,
                   metric AS category,
                   GREATEST(0.0, AVG(pre_corr_item)) AS pre,
                   GREATEST(0.0, AVG(post_corr_item)) AS post,
                   (GREATEST(0.0, AVG(post_corr_item)) - GREATEST(0.0, AVG(pre_corr_item))) AS improvement,
                   AVG(pre_raw) AS preRaw,
                   AVG(post_raw) AS postRaw,
                   COUNT(*) AS answersCount,
                   COUNT(DISTINCT user_code) AS usersCount,
                   'tests' AS source
            FROM joined
            GROUP BY scenario_slug, metric
            ORDER BY scenario_slug, metric
        ")->fetchAll();

        $surveyRows = $this->pdo->query("
            WITH pre AS (
                SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_before AS B0,
                       ROW_NUMBER() OVER (
                           PARTITION BY s.user_code, sa.scenario_slug, sa.sur_n
                           ORDER BY s.started_at, sa.id
                       ) AS rn
                FROM ans_sur sa
                JOIN sessions s ON s.id = sa.session_id
                WHERE sa.val_before IS NOT NULL
            ),
            baseline_sa AS (
                SELECT user_code, scenario_slug, sur_n, B0
                FROM pre
                WHERE rn = 1
            ),
            current_sa AS (
                SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_after AS after_val
                FROM ans_sur sa
                JOIN sessions s ON s.id = sa.session_id
                WHERE sa.val_after IS NOT NULL
            ),
            joined AS (
                SELECT c.user_code, c.scenario_slug,
                       b.B0 AS pre_val,
                       c.after_val AS post_val,
                       (c.after_val - b.B0) AS improvement
                FROM current_sa c
                JOIN baseline_sa b
                  ON b.user_code = c.user_code
                 AND b.scenario_slug = c.scenario_slug
                 AND b.sur_n = c.sur_n
            )
            SELECT scenario_slug AS scenarioSlug,
                   'surveys' AS category,
                   AVG(pre_val) / 10.0 AS pre,
                   AVG(post_val) / 10.0 AS post,
                   AVG(improvement) / 10.0 AS improvement,
                   NULL AS preRaw,
                   NULL AS postRaw,
                   COUNT(*) AS answersCount,
                   COUNT(DISTINCT user_code) AS usersCount,
                   'surveys' AS source
            FROM joined
            GROUP BY scenario_slug
            ORDER BY scenario_slug
        ")->fetchAll();

        $long = array_merge(
            $this->normalizeImprovementRows($testRows),
            $this->normalizeImprovementRows($surveyRows)
        );

        $wide = $this->makeImprovementWide($long);

        return [
            'xKey' => 'scenarioSlug',
            'valueKey' => 'improvement',
            'categories' => $this->uniqueValues($long, 'category'),
            'long' => $long,
            'wide' => $wide,
        ];
    }

    public function improvementDetails(): array {
        $testRows = $this->pdo->query("
            WITH base_candidates AS (
                SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                       CASE WHEN topt.correct = 1 THEN 1.0 ELSE 0.0 END AS pre_raw,
                       ROW_NUMBER() OVER (
                           PARTITION BY s.user_code, at.scenario_slug, at.metric, at.test_n
                           ORDER BY s.started_at, at.id
                       ) AS rn
                FROM ans_test at
                JOIN sessions s ON s.id = at.session_id
                JOIN test_opts topt
                  ON topt.scenario_slug = at.scenario_slug
                 AND topt.metric = at.metric
                 AND topt.test_n = at.test_n
                 AND topt.number = at.first_option_n
            ),
            baseline AS (
                SELECT user_code, scenario_slug, metric, test_n, pre_raw
                FROM base_candidates
                WHERE rn = 1
            ),
            current_scored AS (
                SELECT s.user_code, at.scenario_slug, at.metric, at.test_n,
                       CASE WHEN topt.correct = 1 THEN 1.0 ELSE 0.0 END AS post_raw
                FROM ans_test at
                JOIN sessions s ON s.id = at.session_id
                JOIN test_opts topt
                  ON topt.scenario_slug = at.scenario_slug
                 AND topt.metric = at.metric
                 AND topt.test_n = at.test_n
                 AND topt.number = at.final_option_n
                WHERE at.final_option_n IS NOT NULL
            ),
            joined AS (
                SELECT cs.scenario_slug, cs.metric, cs.test_n, cs.user_code,
                       ((3.0 * b.pre_raw - 1.0) / 2.0) AS pre_corr_item,
                       ((3.0 * cs.post_raw - 1.0) / 2.0) AS post_corr_item,
                       b.pre_raw AS pre_raw,
                       cs.post_raw AS post_raw
                FROM current_scored cs
                JOIN baseline b
                  ON b.user_code = cs.user_code
                 AND b.scenario_slug = cs.scenario_slug
                 AND b.metric = cs.metric
                 AND b.test_n = cs.test_n
            )
            SELECT j.scenario_slug AS scenarioSlug,
                   j.metric AS category,
                   j.test_n AS itemNumber,
                   t.question_text AS questionText,
                   GREATEST(0.0, AVG(j.pre_corr_item)) AS pre,
                   GREATEST(0.0, AVG(j.post_corr_item)) AS post,
                   (GREATEST(0.0, AVG(j.post_corr_item)) - GREATEST(0.0, AVG(j.pre_corr_item))) AS improvement,
                   AVG(j.pre_raw) AS preRaw,
                   AVG(j.post_raw) AS postRaw,
                   COUNT(*) AS answersCount,
                   COUNT(DISTINCT j.user_code) AS usersCount,
                   'tests' AS source
            FROM joined j
            LEFT JOIN tests t
              ON t.scenario_slug = j.scenario_slug
             AND t.metric = j.metric
             AND t.number = j.test_n
            GROUP BY j.scenario_slug, j.metric, j.test_n, t.question_text
            ORDER BY j.scenario_slug, j.metric, j.test_n
        ")->fetchAll();

        $surveyRows = $this->pdo->query("
            WITH pre AS (
                SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_before AS B0,
                       ROW_NUMBER() OVER (
                           PARTITION BY s.user_code, sa.scenario_slug, sa.sur_n
                           ORDER BY s.started_at, sa.id
                       ) AS rn
                FROM ans_sur sa
                JOIN sessions s ON s.id = sa.session_id
                WHERE sa.val_before IS NOT NULL
            ),
            baseline_sa AS (
                SELECT user_code, scenario_slug, sur_n, B0
                FROM pre
                WHERE rn = 1
            ),
            current_sa AS (
                SELECT s.user_code, sa.scenario_slug, sa.sur_n, sa.val_after AS after_val
                FROM ans_sur sa
                JOIN sessions s ON s.id = sa.session_id
                WHERE sa.val_after IS NOT NULL
            ),
            joined AS (
                SELECT c.user_code, c.scenario_slug, c.sur_n,
                       b.B0 AS pre_val,
                       c.after_val AS post_val,
                       (c.after_val - b.B0) AS improvement
                FROM current_sa c
                JOIN baseline_sa b
                  ON b.user_code = c.user_code
                 AND b.scenario_slug = c.scenario_slug
                 AND b.sur_n = c.sur_n
            )
            SELECT j.scenario_slug AS scenarioSlug,
                   'surveys' AS category,
                   j.sur_n AS itemNumber,
                   sv.question_text AS questionText,
                   AVG(j.pre_val) / 10.0 AS pre,
                   AVG(j.post_val) / 10.0 AS post,
                   AVG(j.improvement) / 10.0 AS improvement,
                   NULL AS preRaw,
                   NULL AS postRaw,
                   COUNT(*) AS answersCount,
                   COUNT(DISTINCT j.user_code) AS usersCount,
                   'surveys' AS source
            FROM joined j
            LEFT JOIN surveys sv
              ON sv.scenario_slug = j.scenario_slug
             AND sv.sur_n = j.sur_n
            GROUP BY j.scenario_slug, j.sur_n, sv.question_text
            ORDER BY j.scenario_slug, j.sur_n
        ")->fetchAll();

        return array_merge(
            $this->normalizeImprovementRows($testRows, true),
            $this->normalizeImprovementRows($surveyRows, true)
        );
    }

    public function teacherActivityTimeline(string $group = 'week'): array {
        $group = $this->resolveGroup($group);

        $activeTeachers = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('vl.first_started_at', $group)} AS period_start,
                   COUNT(DISTINCT g.teacher_code) AS activeTeachers,
                   COUNT(*) AS lessonsPlayed
            FROM ({$this->validLessonsSql()}) vl
            JOIN games g ON g.code = vl.game_code
            WHERE g.teacher_code IS NOT NULL
            GROUP BY period_start
            ORDER BY period_start
        ");

        $newTeachers = $this->fetchPeriodCounts("
            SELECT {$this->periodStartExpr('first_lesson_at', $group)} AS period_start,
                   COUNT(*) AS newTeachers
            FROM (
                SELECT g.teacher_code, MIN(vl.first_started_at) AS first_lesson_at
                FROM ({$this->validLessonsSql()}) vl
                JOIN games g ON g.code = vl.game_code
                WHERE g.teacher_code IS NOT NULL
                GROUP BY g.teacher_code
            ) first_teachers
            GROUP BY period_start
            ORDER BY period_start
        ");

        $rows = $this->mergePeriodRows([$activeTeachers, $newTeachers], $group);
        $teachersTotal = 0;

        foreach ($rows as &$row) {
            $teachersTotal += (int)$row['newTeachers'];
            $row['teachersCumulative'] = $teachersTotal;
        }
        unset($row);

        return [
            'group' => $group,
            'xKey' => 'periodLabel',
            'rows' => $rows,
        ];
    }

    private function validLessonsSql(): string {
        return "
            SELECT game_code, MIN(started_at) AS first_started_at
            FROM sessions
            GROUP BY game_code
            HAVING COUNT(DISTINCT user_code) > 1
        ";
    }

    private function resolveGroup(mixed $group): string {
        $group = is_string($group) ? strtolower($group) : 'week';
        return in_array($group, ['week', 'month'], true) ? $group : 'week';
    }

    private function periodStartExpr(string $column, string $group): string {
        return match ($group) {
            'month' => "DATE_FORMAT($column, '%Y-%m-01')",
            default => "DATE_SUB(DATE($column), INTERVAL WEEKDAY($column) DAY)",
        };
    }

    private function fetchPeriodCounts(string $sql): array {
        $rows = $this->pdo->query($sql)->fetchAll();
        $result = [];

        foreach ($rows as $row) {
            if (empty($row['period_start'])) continue;

            $periodStart = $this->dateString($row['period_start']);
            unset($row['period_start']);

            $result[$periodStart] = [];
            foreach ($row as $key => $value) {
                $result[$periodStart][$key] = (int)$value;
            }
        }

        return $result;
    }

    private function mergePeriodRows(array $datasets, string $group): array {
        $periods = [];
        $allKeys = [];

        foreach ($datasets as $dataset) {
            foreach ($dataset as $periodStart => $values) {
                $periods[$periodStart] = true;
                foreach ($values as $key => $_) {
                    $allKeys[$key] = true;
                }
            }
        }

        if (!$periods) return [];

        $periodKeys = $this->filledPeriodKeys(array_keys($periods), $group);
        $rows = [];
        $allKeys = array_keys($allKeys);

        foreach ($periodKeys as $periodStart) {
            $row = [
                'periodStart' => $periodStart,
                'periodLabel' => $this->periodLabel($periodStart, $group),
            ];

            foreach ($allKeys as $key) {
                $row[$key] = 0;
            }

            foreach ($datasets as $dataset) {
                if (!isset($dataset[$periodStart])) continue;
                foreach ($dataset[$periodStart] as $key => $value) {
                    $row[$key] = (int)$value;
                }
            }

            $rows[] = $row;
        }

        return $rows;
    }

    private function filledPeriodKeys(array $periodKeys, string $group): array {
        sort($periodKeys);

        $start = new DateTimeImmutable($periodKeys[0]);
        $end = new DateTimeImmutable($periodKeys[count($periodKeys) - 1]);
        $interval = match ($group) {
            'month' => new DateInterval('P1M'),
            default => new DateInterval('P1W'),
        };

        $result = [];
        for ($cursor = $start; $cursor <= $end; $cursor = $cursor->add($interval)) {
            $result[] = $cursor->format('Y-m-d');
        }

        return $result;
    }

    private function periodLabel(string $periodStart, string $group): string {
        $date = new DateTimeImmutable($periodStart);

        return match ($group) {
            'month' => $date->format('Y') . ' ' . $this->ukMonthShort((int)$date->format('n')),
            default => $date->format('Y') . ' ' . $this->ukMonthShort((int)$date->format('n')) . ' ' . $date->format('d'),
        };
    }

    private function dateString(mixed $value): string {
        return (new DateTimeImmutable((string)$value))->format('Y-m-d');
    }

    private function normalizeImprovementRows(array $rows, bool $includeItemNumber = false): array {
        return array_map(function (array $row) use ($includeItemNumber): array {
            $normalized = [
                'scenarioSlug' => (string)$row['scenarioSlug'],
                'scenarioName' => $this->scenarioName((string)$row['scenarioSlug']),
                'category' => (string)$row['category'],
                'pre' => $this->floatOrNull($row['pre']),
                'post' => $this->floatOrNull($row['post']),
                'improvement' => $this->floatOrNull($row['improvement']),
                'preRaw' => $this->floatOrNull($row['preRaw']),
                'postRaw' => $this->floatOrNull($row['postRaw']),
                'answersCount' => (int)$row['answersCount'],
                'usersCount' => (int)$row['usersCount'],
                'source' => (string)$row['source'],
            ];

            if ($includeItemNumber) {
                $normalized['itemNumber'] = (int)$row['itemNumber'];
                $normalized['questionText'] = $row['questionText'];
            }

            return $normalized;
        }, $rows);
    }

    private function makeImprovementWide(array $rows): array {
        $wide = [];

        foreach ($rows as $row) {
            $scenarioSlug = $row['scenarioSlug'];
            $category = $row['category'];

            if (!isset($wide[$scenarioSlug])) {
                $wide[$scenarioSlug] = [
                    'scenarioSlug' => $scenarioSlug,
                    'scenarioName' => $this->scenarioName($scenarioSlug),
                    '_improvementSum' => 0.0,
                    '_improvementCount' => 0,
                ];
            }

            $wide[$scenarioSlug][$category] = $row['improvement'];
            $wide[$scenarioSlug][$category . 'UsersCount'] = $row['usersCount'];
            $wide[$scenarioSlug][$category . 'AnswersCount'] = $row['answersCount'];

            if ($row['improvement'] !== null) {
                $wide[$scenarioSlug]['_improvementSum'] += (float)$row['improvement'];
                $wide[$scenarioSlug]['_improvementCount']++;
            }
        }

        foreach ($wide as &$row) {
            $row['averageImprovement'] = $row['_improvementCount'] > 0
                ? $row['_improvementSum'] / $row['_improvementCount']
                : 0.0;
            unset($row['_improvementSum'], $row['_improvementCount']);
        }
        unset($row);

        usort($wide, fn (array $a, array $b): int => $b['averageImprovement'] <=> $a['averageImprovement']);
        return array_values($wide);
    }

    private function uniqueValues(array $rows, string $key): array {
        $values = [];
        foreach ($rows as $row) {
            if (isset($row[$key])) $values[(string)$row[$key]] = true;
        }

        $preferred = ['critical', 'knowledge', 'knowladge', 'narratives', 'surveys'];
        $result = [];
        foreach ($preferred as $value) {
            if (isset($values[$value])) {
                $result[] = $value;
                unset($values[$value]);
            }
        }
        ksort($values);
        return array_merge($result, array_keys($values));
    }

    private function floatOrNull(mixed $value): ?float {
        return $value === null ? null : (float)$value;
    }

    private function cacheFile(string $group): string {
        return __DIR__ . '/../../../storage/full_stats_' . $group . '_cache.json';
    }

    private function ukMonthShort(int $month): string {
        return [
            1 => 'січ',
            2 => 'лют',
            3 => 'бер',
            4 => 'кві',
            5 => 'тра',
            6 => 'чер',
            7 => 'лип',
            8 => 'сер',
            9 => 'вер',
            10 => 'жов',
            11 => 'лис',
            12 => 'гру',
        ][$month] ?? '';
    }

    private function scenarioDirectory(): array {
        $result = [];
        foreach (self::SCENARIOS as $slug => $scenario) {
            $result[$slug] = [
                'slug' => $slug,
                'name' => $scenario['name'],
                'title' => $scenario['title'],
            ];
        }
        return $result;
    }

    private function scenarioName(string $slug): string {
        return self::SCENARIOS[$slug]['name'] ?? $slug;
    }

    private function scenarioTitle(string $slug): string {
        return self::SCENARIOS[$slug]['title'] ?? $this->scenarioName($slug);
    }

    private function scenarioLabels(array $slugs): array {
        $labels = [];
        foreach ($slugs as $slug) {
            $labels[$slug] = $this->scenarioName((string)$slug);
        }
        return $labels;
    }

    private function indicators(): array {
        $summary = $this->summary();

        return [
            'avgSessionsPerLesson' => $summary['lessons'] > 0
                ? $summary['sessions'] / $summary['lessons']
                : 0.0,
            'avgPlayersPerLesson' => $summary['lessons'] > 0
                ? $summary['uniquePlayers'] / $summary['lessons']
                : 0.0,
            'avgLessonsPerTeacher' => $summary['lessons'] > 0
                ? $summary['lessons'] / $summary['uniqueTeachers']
                : 0.0,
            'lessonsPerTeacher' => $summary['uniqueTeachers'] > 0
                ? $summary['lessons'] / $summary['uniqueTeachers']
                : 0.0,
        ];
    }
}
