<?php
class TrainingController
{
    /** PDO лишаємо для сумісності зі створенням у public/api.php */
    public function __construct($pdo = null) {}

    /**
     * GET /api/training?slug=...
     * Повертає: ['content' => string] або ['error' => string]
     */
    public function index(): array
    {
        // 1) slug
        $slug = isset($_GET['slug']) ? strtolower(trim((string)$_GET['slug'])) : '';
        if ($slug === '' || !preg_match('~^[a-z0-9\/\-]+$~', $slug)) {
            http_response_code(400);
            return ['error' => 'Invalid or missing slug'];
        }
        $base = isset($_GET['base']) ? strtolower(trim((string)$_GET['base'])) : 'training';

        // 2) базова директорія: app/sections/training (відносно цього файлу)
        $baseDir = realpath(__DIR__ . "/../../sections/{$base}");
        if ($baseDir === false) {
            http_response_code(500);
            return ['error' => 'Base directory missing', 'dir'=>__DIR__ . "/../../sections/{$base}"];
        }

        // 3) файл контенту
        $target = $baseDir . '/' . $slug . '.php';
            $real   = realpath($target);
        if (!$real || strncmp($real, $baseDir, strlen($baseDir)) !== 0 || !is_file($real)) {
            http_response_code(404);
            return ['error' => 'Content not found'];
        }

        // 4) рендер у буфер
        ob_start();
        include $real;
        $html = ob_get_clean();

        // 5) результат: лише контент
        return ['content' => $html];
    }
}
