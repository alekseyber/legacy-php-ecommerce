<?php

define("DEBUG", 1);
define("HASH_ON", 0);
define("SITEOFF", 0);
define("SITEOFFKEY", 'utm_fjoirgff9094fds');
define("ROOT", dirname(__DIR__));
define("WWW", ROOT . '/public');
define("APP", ROOT . '/app');
define("CORE", ROOT . '/vendor/ishop/core');
define("LIBS", ROOT . '/vendor/ishop/core/libs');
define("CACHE", ROOT . '/tmp/cache');
define("CONF", ROOT . '/config');
define("_WORK", 1);
define("PREFIX", '111_');
define("LAYOUT", 'uggsmaterial');
define("MEMCACHED", 0);
define("MEMCACHED_SES", 0); //не реализовано
define("MEMCACHED_SERVER", '127.0.0.1');
define("MEMCACHED_PORT", 11211);
define("TIME_SESSION", 30); // время жизни сессии в сутках, если ноль, то по умолчанию

$isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);

$URL_START = $isHttps ? 'https://' : 'http://';
// http://domen/public/index.php
//$app_path = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
$app_path = $URL_START . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
// http://domen/public/
$app_path = preg_replace("#[^/]+$#", '', $app_path);
// http://domen
$app_path = str_replace('/public/', '', $app_path);
define("PATH", $app_path);
define("ADMIN", PATH . '/satis');
define("ADMIN_PREFIX", 'satis');

require_once ROOT . '/vendor/autoload.php';
