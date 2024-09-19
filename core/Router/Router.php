<?php
namespace Router;
use Exception;

class Router {
    private static array $routes = ["get" => [], "post" => []];
    private static string $current, $root, $currentName = "";

    private null|string $route;
    private null|string $method;
    private null|string $controller;
    private null|string $controllerFunction;
    private null|array $params;

    public function __construct()
    {
        global $config;
        $this->route = null;
        $this->method = null;
        $this->controller = null;
        $this->controllerFunction = null;
        $this->params = null;
        static::$current = static::urlPathToUri($_SERVER["REQUEST_URI"], ltrim(rtrim(str_replace("index.php", "", $_SERVER["PHP_SELF"]), "/"), "/"));
        static::$root = static::prepareUrl(str_replace("index.php", "", $_SERVER["PHP_SELF"]));
    }
    public static function getCurrentRouteName(): string
    {
        return self::$currentName;
    }
    private static function prepareUrl($url): string
    {
        return rtrim(ltrim(parse_url($url)["path"],  "/"), "/");
    }
    private static function urlPathToUri($url, $root)
    {
        preg_match("/^(\/\/[a-zA-Z0-9.-]+\/)?([^\/]*\/)?($root)(\/?[^\/]+)*\/?$/", self::prepareUrl($url), $matches1);
        preg_match("/^($root)\/?(.*)$/", $matches1[0], $matches);
        return $matches[2];
    }
    private static function addRoute($type, $path, $controller, $function, $name = null, $params = null): void
    {
        if (!$name) $name = $path;
        self::$routes[$type][$name] = [rtrim(ltrim($path, "/"), "/") => "$controller&$function"];
        if ($params) static::$routes[$type][$name]["params"] = $params;
    }
    public static function get($path, $controller, $params = null): self
    {
        $instance = new static();
        $instance->route = $path;
        $instance->method = "get";
        $instance->controller = $controller[0];
        $instance->controllerFunction = $controller[1];
        $instance->params = $params;
        static::addRoute("get", $path, $controller[0], $controller[1], $path, $params);
        return $instance;
    }
    public static function post($path, $controller, $params = null): self
    {
        $instance = new static();
        $instance->route = $path;
        $instance->method = "post";
        $instance->controller = $controller[0];
        $instance->controllerFunction = $controller[1];
        $instance->params = $params;
        static::addRoute("post", $path, $controller[0], $controller[1], $path, $params);
        return $instance;
    }
    public function name($name): self
    {
        unset(static::$routes[$this->method][$this->route], static::$routes[$this->method][$name]);
        self::addRoute($this->method, $this->route, $this->controller, $this->controllerFunction, $name, $this->params);
        return $this;
    }
    public static function getRoute($type, $name): bool|int|string
    {
        foreach (self::$routes[$type] as $routeName => $rou)
            foreach ($rou as $url => $rout)
                if ($routeName == $name) return $url;
        return false;
    }
    public static function getUniqueRoute($name): bool|int|string
    {
        foreach (self::$routes as $type => $rou)
            foreach ($rou as $routeName => $rout)
                foreach ($rout as $url => $func) {
                    if ($routeName == $name) return $url;
                    elseif ($url == $name) return $routeName;
                }
        return false;
    }
    public static function getRouteByPath($path, $type = null): bool|int|string
    {
        if ($type === null) $type = strtolower($_SERVER["REQUEST_METHOD"]);
        foreach (static::$routes[$type] as $routeName => $rou) {
            foreach ($rou as $url => $rout) {
                if ($url == $path) return $routeName;
                elseif ($routeName == $path) return $routeName;
            }
        }
        return false;
    }
    public static function redirect($routeName, $routeType = null, $routeParams = null, $flashMessage = null): void
    {
        global $helper;
        if ($routeType === null) $routeType = strtolower($_SERVER["REQUEST_METHOD"]);
        $route = is_array($routeName) ? self::fetchRouteAndSetValues($routeName[0], $routeName[1]) : self::getUniqueRoute($routeName);
        if ($route !== false)
        {
            $url = self::getRoot(true).$route;
            if ($routeParams !== null) $url .= $routeParams;
            if ($flashMessage) flashMessage($flashMessage);
            header("Location: $url", true);
            exit();
        }else ddd("{Router} -> Route ($routeName) not found!");
    }
    public static function getRoot($rootUrl): string
    {
        return $rootUrl ? resource() : static::$root;
    }
    public static function getCurrent(): string
    {
        return static::$current;
    }
    public static function isCurrentRoute($name): bool
    {
        return static::$current == static::getUniqueRoute($name);
    }
    public static function fetchRoute($route): array
    {
        preg_match_all("/{\s*(\S.*?)\s*}/", $route, $routeMatches);
        $data = [];
        foreach ($routeMatches[1] as $key => $match) {
            $end = explode("/", str_replace(explode($routeMatches[0][$key], $route)[0], "", static::$current))[0];
            $data[$match] = $end;
            $route = str_replace($routeMatches[0][$key], $end, $route);
        }
        return [$route, $data];
    }
    public static function fetchRouteAndSetValues($route, $values): array|int|bool|string
    {
        $route = static::getUniqueRoute($route);
        preg_match_all("/{\s*(\S.*?)\s*}/", $route, $routeMatches);
        foreach ($routeMatches[1] as $key => $match)
            $route = str_replace($routeMatches[0][$key], $values[$match], $route);
        return $route;
    }
    private static function __run()
    {
        $found = 0;
        foreach (static::$routes[strtolower($_SERVER['REQUEST_METHOD'])] as $ke => $rou) {
            foreach ($rou as $key => $rout) {
                if ($found)
                    break;
                if (static::fetchRoute($key)[0] == static::$current) {
                    self::$currentName = $ke;
                    $found = 1;
                    $route = explode("&", $rout);
                    $controller = $route[0];
                    $function = $route[1];
                    $controllerModel = new $controller;
                    foreach (static::fetchRoute($key)[1] as $__k => $value)
                        $rou["params"][$__k] = $value;
                    ob_start();
                    echo (isset($rou["params"]))
                        ? $controllerModel->$function($rou["params"])
                        : $controllerModel->$function();
                }
            }
        }
        if (!$found) view("404");
    }
    public static function run(): void
    {
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
        });

        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== NULL) {
                ddd([
                    "SHUTDOWN_DEBUG" => [
                        "title" => "Error in file: " . $error['file'],
                        "on line" => $error['line'],
                        "message" => $error['message']
                    ]
                ]);
            }
        });

        try {
            self::__run();
        } catch (\Throwable $e) {
            ob_end_clean();
            ddd(["ERROR_DEBUG" => [
                "title" => "Error in file: ". $e->getFile(),
                "on line" => $e->getLine(),
                "message" => $e->getMessage()
            ]]);
        }
    }
    public static function getRoutes(): array
    {
        return static::$routes;
    }
}