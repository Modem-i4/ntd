<?php

class UserController {
    public function __construct(private PDO $pdo) {
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /* ========== HELPERS ========== */

    private function jsonInput(): array {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function requireField(array $in, string $key): string {
        $v = isset($in[$key]) ? trim((string)$in[$key]) : '';
        if ($v === '') {
            throw new InvalidArgumentException("$key is required");
        }
        return $v;
    }

    /**
     * Генерує унікальний 6-значний код користувача (000000–999999).
     */
    private function genUserCode(): string {
        do {
            $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $stmt = $this->pdo->prepare('SELECT 1 FROM users WHERE code = ? LIMIT 1');
            $stmt->execute([$code]);
            $exists = (bool)$stmt->fetchColumn();
        } while ($exists);
        return $code;
    }

    /* ========== ACTIONS ========== */

    /**
     * POST /api/users/get-or-create
     * Body: { "fid": "..." }
     * Return: ['user' => ['code' => string, 'saves' => ?string]]
     */
    public function getOrCreate(): array {
        $in  = $this->jsonInput();
        $fid = $this->requireField($in, 'fid');

        try {
            $this->pdo->beginTransaction();

            // 1) шукаємо існуючого юзера за fid
            $stmt = $this->pdo->prepare(
                'SELECT u.code, u.saves
                   FROM user_installations ui
                   JOIN users u ON u.code = ui.user_code
                  WHERE ui.fid = ?
                  LIMIT 1'
            );
            $stmt->execute([$fid]);
            $user = $stmt->fetch();

            if (!$user) {
                // 2) створюємо нового та прив’язуємо fid
                $code = $this->genUserCode();
                $this->pdo->prepare('INSERT INTO users (code, saves) VALUES (?, NULL)')
                          ->execute([$code]);

                // на випадок залишків
                $this->pdo->prepare('DELETE FROM user_installations WHERE fid = ?')
                          ->execute([$fid]);

                $this->pdo->prepare('INSERT INTO user_installations (user_code, fid) VALUES (?, ?)')
                          ->execute([$code, $fid]);

                $user = ['code' => $code, 'saves' => null];
            }

            $this->pdo->commit();
            return ['user' => $user];
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new RuntimeException('get-or-create failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * POST /api/users/attach-to-user
     * Body: { "user_code": "000123", "fid": "..." }
     * Return: ['attached' => true, 'user_code' => string, 'fid' => string]
     */
    public function attachToUser(): array {
        $in       = $this->jsonInput();
        $userCode = strtoupper($this->requireField($in, 'user_code'));
        $fid      = $this->requireField($in, 'fid');

        try {
            $this->pdo->beginTransaction();

            // існує користувач?
            $chk = $this->pdo->prepare('SELECT 1 FROM users WHERE code = ? LIMIT 1');
            $chk->execute([$userCode]);
            if (!$chk->fetchColumn()) {
                throw new RuntimeException('User not found');
            }

            // переприв’язка fid → user_code
            $this->pdo->prepare('DELETE FROM user_installations WHERE fid = ?')
                      ->execute([$fid]);

            $this->pdo->prepare('INSERT INTO user_installations (user_code, fid) VALUES (?, ?)')
                      ->execute([$userCode, $fid]);

            $this->pdo->commit();
            return ['attached' => true, 'user_code' => $userCode, 'fid' => $fid];
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new RuntimeException('attach-to-user failed: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * POST /api/users/save
     * Body: { "user_code": "000123", "saves": <json|string> }
     * Зберігає users.saves і повертає оновленого користувача.
     */
    public function save(): array {
        $in       = $this->jsonInput();
        $userCode = strtoupper($this->requireField($in, 'user_code'));
        $saves    = $in['saves'] ?? null;

        // якщо передано масив/об'єкт — перетворюємо на JSON
        if (is_array($saves) || is_object($saves)) {
            $saves = json_encode($saves, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } elseif (!is_string($saves)) {
            // дозволяємо null або рядок; інше — помилка
            throw new InvalidArgumentException('saves must be a string, JSON-encodable array/object, or null');
        }

        // оновлюємо
        $stmt = $this->pdo->prepare('UPDATE users SET saves = ? WHERE code = ?');
        $stmt->execute([$saves, $userCode]);

        if ($stmt->rowCount() === 0) {
            // або користувача нема, або те саме значення — перевіримо існування
            $chk = $this->pdo->prepare('SELECT 1 FROM users WHERE code = ? LIMIT 1');
            $chk->execute([$userCode]);
            if (!$chk->fetchColumn()) {
                throw new RuntimeException('User not found');
            }
        }

        // віддаємо назад користувача
        $sel = $this->pdo->prepare('SELECT code, saves FROM users WHERE code = ? LIMIT 1');
        $sel->execute([$userCode]);
        $user = $sel->fetch();

        return ['user' => $user];
    }
}
