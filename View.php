<?php

namespace twent\mvccore;

class View
{
    public string $title = '';

    public function renderView($view, $params = [])
    {
        $layout = App::$app->layout;
        if (App::$app->controller) {
            $layout = App::$app->controller->layout;
        }
        $viewContent = $this->renderViewOnly($view, $params);
        ob_start();
        include_once App::$ROOT_DIR."/views/layouts/$layout.php";
        $layoutContent = $this->renderViewOnly($view, $params);
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderViewOnly($view, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once App::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }

}
