<?php

namespace twent\mvccore;

use twent\mvccore\exceptions\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    private array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($url, $callback)
    {

        $this->routes['get'][$url] = $callback;
    }

    public function post($url, $callback)
    {
        $this->routes['post'][$url] = $callback;
    }

    public function resolve()
    {
       $method = $this->request->method();
       $url = $this->request->url();
       $callback = $this->routes[$method][$url] ?? false;

       if (is_string($callback)) {
            return App::$app->view->renderView($callback);
       }

       if ($callback === false) {
           throw new NotFoundException();
       }

       if (is_array($callback)) {
           /**
            * @var \twent\mvccore\Controller $controller
            */
           $controller = new $callback[0]();
           App::$app->controller = $controller;
           $controller->action = $callback[1];
           $callback[0] = $controller;

           foreach ($controller->getMiddlewares() as $middleware) {
               $middleware->execute();
           }
       }

       return call_user_func($callback, $this->request, $this->response);
    }

    public function renderView($view, $params = [])
    {
        return App::$app->view->renderView($view, $params);
    }

    public function renderViewOnly($view, $params = [])
    {
        return App::$app->view->renderViewOnly($view, $params);
    }

}
