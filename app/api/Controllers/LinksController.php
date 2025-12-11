<?php

class LinksController
{
    private $links = [
        'viber' => 'https://invite.viber.com/?g2=AQA865S0wiiEnlVxZShZKxiSReB90exI7vgkzUtSFK94M1gy639qzmMJPAPDnL6i',
    ];

    /** GET /l/{goto} */
    public function redirect($goto)
    {
        if (isset($this->links[$goto])) {
            header('Location: ' . $this->links[$goto]);
            exit;
        }
        http_response_code(404);
        echo 'Посилання не знайдено';
    }
}
