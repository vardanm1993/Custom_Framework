<?php

namespace Core;

class View
{
    /**
     * @param string $view
     * @param array $data
     * @return false|string
     */
    public function renderView(string $view, array $data = []): false|string
    {
        ob_start();
        extract($data);
        include_once App::$ROOT_DIR . "/resources/views/$view.php";

        return ob_get_clean();
    }
}