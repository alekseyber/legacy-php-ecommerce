<?php

namespace ishop;

class App {

    public static $app;

    public function __construct() {
        $query = trim($_SERVER['QUERY_STRING'], '/');
        if ($query != $_SERVER['QUERY_STRING']) {
            header("Location: /$query");
        }
//        $request_uri = str_replace('?', '&', trim($_SERVER['REQUEST_URI'], '/'));
		if (TIME_SESSION) {
            $timeSession = 86400 * TIME_SESSION;
            ini_set('session.gc_maxlifetime', $timeSession);
            ini_set('session.cookie_lifetime', $timeSession);
            session_set_cookie_params($timeSession);
        }
        session_start();
        self::$app = Registry::instance();
        $this->getParams();
        new ErrorHandler();

        if (isset($_GET[SITEOFFKEY])) {
            setcookie(SITEOFFKEY, true, time() + 3600 * 10);
        }
        if (SITEOFF && (!isset($_COOKIE[SITEOFFKEY]))) {
            if (!isset($_GET[SITEOFFKEY]))
                throw new \Exception('Сайт находится в разработке, зайдите позже.', 503);
        }

//        if ($query != $request_uri) {
//            //throw new \Exception("Страница не найдена", 404); // отдаем 404 ошибку
//            header("Location: $query"); // или можно попробовать редирект
//        }

        Router::dispatch($query);
    }

    protected function getParams() {
        $params = require_once CONF . '/params.php';
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                self::$app->setProperty($k, $v);
            }
        }
    }

}
