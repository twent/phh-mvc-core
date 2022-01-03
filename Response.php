<?php

namespace twent\mvccore;

class Response
{
    public function statusCode(int $code)
    {
        return http_response_code($code);
    }

    public function redirect(string $url_path)
    {
        header('Location:' . $url_path);
    }

}
