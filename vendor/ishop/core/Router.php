<?php

namespace ishop;

use app\models\Seourl;

class Router {

    protected static $routes = [];
    protected static $route = [];

    public static function add($regexp, $route = []) {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes() {
        return self::$routes;
    }

    public static function getRoute() {
        return self::$route;
    }

    public static function dispatch($url) {
       
        $url = self::removeQueryString($url);

        App::$app->setProperty('controlleraction', $url);
        $seo_link_on = App::$app->getProperty('seo_link_on');        
        if ($seo_link_on) {
            if (!preg_match('#^' . ADMIN_PREFIX . '/?#', $url)) {
                if ($url && !preg_match('#^utm_.+$#', $url)) {
                    $seourl = new Seourl();
                    $url_new = $seourl->getAlias($url);                   

                    if ($url_new) {
                        $url = $url_new;
                    } else {
                        throw new \Exception("Страница не найдена $url", 404);
                    }
                }
            }
        }


        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['prefix'] . self::$route['controller'] . 'Controller';
            if (class_exists($controller)) {
                $controllerObject = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';
                if (method_exists($controllerObject, $action)) {
                    $controllerObject->$action();
                    $controllerObject->getView();
                } else {
                    throw new \Exception("Метод $controller::$action не найден", 404);
                }
            } else {
                throw new \Exception("Контроллер $controller не найден", 404);
            }
        } else {
            throw new \Exception("Страница не найдена $url", 404);
        }
    }

    public static function matchRoute($url) {

        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#{$pattern}#i", $url, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $route[$k] = $v;
                    }
                }
                if (empty($route['action'])) {
                    $route['action'] = 'index';
                }
                if (!isset($route['prefix'])) {
                    $route['prefix'] = '';
                } else {
                    $route['prefix'] .= '\\';
                }
                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    // CamelCase
    protected static function upperCamelCase($name) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    // camelCase
    protected static function lowerCamelCase($name) {
        return lcfirst(self::upperCamelCase($name));
    }

    protected static function removeQueryString($url) {

        if ($url) {
            $params = explode('&', $url, 2);
            if (false === strpos($params[0], '=')) {
                return rtrim($params[0], '/');
            } else {
                return '';
            }
        }
    }

}
