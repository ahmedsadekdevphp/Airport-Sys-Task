<?php

class Router
{
    private const HTTP_METHODS = ['GET', 'POST'];

    private $routes = [];
    /**
     * Adds a new route to the routing table.
     *
     * @param string $method The HTTP method (e.g., 'GET', 'POST') for the route.
     * @param string $uri The URI (endpoint) for the route.
     * @param mixed $action The action to be executed when the route is matched. This can be 
     *                      a callable or a controller action string (e.g., 'ControllerName@methodName').
     * 
     * @throws InvalidArgumentException If the provided HTTP method is not supported.
     * @return void
     */
    public function add(string $method, string $uri, $action): void
    {
        if (!in_array($method, self::HTTP_METHODS)) {
            throw new InvalidArgumentException("HTTP method $method is not supported.");
        }
        $this->routes[$method][$uri] = $action;
    }

    /**
     * Dispatches a request to the appropriate controller action based on the URL and HTTP method.
     *
     * @param string $url The request URL to be dispatched.
     * @return void
     * 
     * @throws Exception If the HTTP method is not allowed (405) or the route is not found (404).
     */
    public function dispatch(string $url): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $this->normalizeUrl($url);
        $this->checkMethod($method, $this->routes);
        $this->CheckRoute($url, $this->routes[$method]);
        $action = $this->routes[$method][$url];
        $this->handleAction($action);
    }

    /**
     * Checks if the provided URL matches any defined route.
     * 
     * @param string $url The URL to be checked.
     * @param array $routes The list of defined routes where each key represents a valid URL.
     * 
     * @throws Exception Sends a 404 response if the route for the given URL is not found.
     */
    private function CheckRoute($url, $routes)
    {
        if (!array_key_exists($url, $routes)) {
            $this->sendResponse(404, "Route not found for URL: $url");
        }
    }

    /**
     * Checks if the provided HTTP method is allowed .
     *
     * @param string $method The HTTP method to be checked (e.g., 'GET', 'POST').
     * @param array $routes The list of routes where each key represents an allowed method.
     * 
     * @throws Exception Sends a 405 response if the method is not allowed.
     */
    private function checkMethod($method, $routes)
    {
        if (!array_key_exists($method, $routes)) {
            $this->sendResponse(405, "Method $method not allowed.");
        }
    }


    /**     
     * This function uses `parse_url` to extract the path part of the URL and trims any 
     * leading or trailing slashes ('/') from the result. The normalization ensures that 
     *
     * @param string $url The full URL to be normalized.
     * @return string The normalized URL path.
     */
    private function normalizeUrl(string $url): string
    {
        return trim(parse_url($url, PHP_URL_PATH), '/');
    }

    /**
     * Handles the execution of an action, which can either be a callable 
     * (such as a closure) or a controller action specified as a string.
     * @param mixed $action The action to be executed. This can be a callable or a 
     * string representing a controller action.
     * @return mixed The result of the invoked action, either from the callable 
     * or from the controller method.
     * @throws Exception If the action is not callable and the controller action cannot 
     *be handled (e.g., if the controller or method does not exist).
     */

    private function handleAction($action)
    {
        if (is_callable($action)) {
            return call_user_func($action); // Call the closure
        }
        $this->handleControllerAction($action);
    }
    /**
     * Handles the execution of a specified controller action.
     *     *
     * @param string $action The action string specifying the controller and method, 
     *                       formatted as 'ControllerName@methodName'.
     * @return mixed The result of the invoked method from the controller.
     * @throws Exception If the controller file does not exist or if the method is 
     * not found in the controller.
     */
    private function handleControllerAction(string $action)
    {
        list($controllerName, $actionMethod) = explode('@', $action);

        $controllerFile = '../app/controllers/' . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            $this->sendResponse(404, "Controller $controllerName not found.");
        }

        require_once $controllerFile;
        $controllerInstance = new $controllerName();
        if (!method_exists($controllerInstance, $actionMethod)) {
            $this->sendResponse(404, "Method $actionMethod not found in controller $controllerName.");
        }
        return $controllerInstance->$actionMethod();
    }

    private function sendResponse(int $statusCode, string $message): void
    {
        http_response_code($statusCode);
        echo $message;
        exit;
    }
}
