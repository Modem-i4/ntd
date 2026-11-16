<?php

class LinksController
{
    private $links = [
        'viber' => 'https://invite.viber.com/?g2=AQA9f78qEd0ctVVxZSh2Cr3IKOcXYImxr0bxEwg2g90cyMCg%2Fx78axJBOEr%2BAUmW',
        'calendar' => 'https://calendar.google.com/calendar/u/0?cid=Y18xYjExNTE3MjY3N2E1NGQ2YzQ3YTM3NTg3ZmE3N2MwY2Y2MTM3ZWExODdkYmEwMWZkMGNkZjgxZWU4MTY5OTRkQGdyb3VwLmNhbGVuZGFyLmdvb2dsZS5jb20'
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
