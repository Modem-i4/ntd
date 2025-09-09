<?php
function render(string $view, array $data = [], ?string $layout = 'base'): void {
    extract($data, EXTR_SKIP);
    ob_start();
    require $view;
    $content = ob_get_clean();
    if ($layout) {
        $title = $data['title'] ?? 'Document';
        require __DIR__ . "/layouts/{$layout}.php";
    } else {
        echo $content;
    }
}
