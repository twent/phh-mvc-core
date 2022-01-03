<?php

namespace twent\mvccore;

use twent\mvccore\middlewares\BaseMiddleware;

class Controller
{
    // \twent\mvccore\middlewares\BaseMiddleware[]
    protected array $middlewares = [];
    public string $layout = 'index';
    public string $action = '';

    public function setlayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $params = []): string
    {
        return App::$app->view->renderView($view, $params);
    }

    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

}
