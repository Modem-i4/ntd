<?php
class TeacherController
{
    public function __construct(private PDO $pdo) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function ensureTeacherByFid(?string $fid): string {
        $fid = trim((string)$fid);
        $this->pdo->beginTransaction();
        try {
            if ($fid !== '') {
                $st = $this->pdo->prepare('SELECT teacher_code FROM teacher_installations WHERE fid = ? ORDER BY id DESC LIMIT 1');
                $st->execute([$fid]);
                if ($row = $st->fetch()) {
                    $this->pdo->commit();
                    return $row['teacher_code'];
                }
            }

            $teacherCode = null;
            for ($i = 0; $i < 10; $i++) {
                $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                try {
                    $ins = $this->pdo->prepare('INSERT INTO teachers (code) VALUES (?)');
                    $ins->execute([$code]);
                    $teacherCode = $code;
                    break;
                } catch (PDOException $e) {
                    if (($e->errorInfo[1] ?? null) !== 1062) throw $e;
                }
            }
            if ($teacherCode === null) throw new RuntimeException('Failed to generate unique teacher code');

            $ins = $this->pdo->prepare('INSERT INTO teacher_installations (teacher_code, fid) VALUES (?, ?)');
            $ins->execute([$teacherCode, ($fid === '' ? null : $fid)]);

            $this->pdo->commit();
            return $teacherCode;
        } catch (\Throwable $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            throw $e;
        }
    }

    public function create(): array {
        $in  = $this->readJsonOrPost();
        $fid = trim((string)($in['fid'] ?? ''));
        $teacherCode = $this->ensureTeacherByFid($fid);
        return ['teacher_code' => $teacherCode];
    }

    private function readJsonOrPost(): array {
        $ctype = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        if (stripos($ctype, 'application/json') !== false) {
            $raw = file_get_contents('php://input') ?: '';
            $j = json_decode($raw, true);
            return is_array($j) ? $j : [];
        }
        return $_POST ?: [];
    }
}
