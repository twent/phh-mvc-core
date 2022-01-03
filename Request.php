<?php

namespace twent\mvccore;

class Request
{
    public function url()
    {
        $url = $_SERVER['REQUEST_URI'] ?? '/';
        // Проверяем есть ли параметры в запросе
        $params_pos = strpos($url, '?');
        if ($params_pos === false) {
            return $url;
        }

        return substr($url, 0, $params_pos);

    }

    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'post';
    }

    public function data()
    {
        $form_data = [];

        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $form_data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $form_data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }


        }

        return $form_data;
    }

}
