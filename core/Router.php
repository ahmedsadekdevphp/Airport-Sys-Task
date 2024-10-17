<?php
class Router
{
    private $controller;
    private $method = 'index';
    private $params = [];
    private $requestMethod;

    public function __construct()
    {
        $url = $this->parseUrl();
        if (isset($url[0]) && file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        } else {
            $this->error404();
            return;
        }
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }

        $this->params = $url ? array_values($url) : [];
    }

    public function dispatch()
    {
        // Check for the request method and call the appropriate controller method
        if ($this->requestMethod == 'GET') {
            call_user_func_array([$this->controller, $this->method], $this->params);
        }
        // You can add additional cases for POST, PUT, DELETE methods
    }

    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return null;
    }

    private function error404()
    {
        http_response_code(404);
        echo "404 Not Found";
    }
}
