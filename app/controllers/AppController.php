<?php

namespace app\controllers;

use app\models\AppModel;
use app\widgets\currency\Currency;
use ishop\App;
use ishop\base\Controller;
use ishop\Cache;

class AppController extends Controller {

    public function __construct($route) {
        parent::__construct($route);
        new AppModel();
        $ip_str = detectIp();
        $ip = ip2long($ip_str);
        $block_ip = \R::count('blockip', 'ip = ?', [$ip]);
        if ($block_ip) {
            throw new \Exception('IP в block_ip', 503);
        }
        $this->addParamsFromBd();
        App::$app->setProperty('currencies', Currency::getCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
        App::$app->setProperty('cats', self::cacheCategory());
        App::$app->setProperty('product_ids', self::cacheProduct());
        $_SESSION['recomacces'] = App::$app->getProperty('recomacces_qty');
        $this->header = AppModel::getHeaderStr();

        $this->json = false;
        if (isset($_GET['json'])) {
            header('Content-Type: application/json');
            $this->json = true;
        }
    }

    public static function cacheCategory() {
        $cache = Cache::instance();
        $cats = $cache->get('cats');

        if (!$cats) {
            $cachetime = App::$app->getProperty('cachetime_category');
            $cats = \R::getAssoc("SELECT * FROM category WHERE status = 1 ORDER BY sortvalue");
            $cache->set('cats', $cats, $cachetime);
        }
        return $cats;
    }

    public static function cacheProduct() {
        $cache = Cache::instance();
        $product_ids = $cache->get('product_ids');
        if (!$product_ids) {
            $cachetime = App::$app->getProperty('cachetime_productids');
            $product_ids = \app\models\Product::getAllProductsIds();
            $cache->set('product_ids', $product_ids, $cachetime);
        }
        return $product_ids;
    }

    protected static function postChek() {
        $post = [];
        $data = self::requestChekTable($_POST, 2);
        $post = $data['getparams'];
        return $post;
    }

    protected static function uriChek() {
        $uri = '';
        $get = $_GET;
        //$get = $_REQUEST;
        $getparams = [];
        $controlleraction = App::$app->getProperty('controlleraction');

        if ($controlleraction) {
            unset($get[$controlleraction]);
            $controlleraction = mb_strtolower($controlleraction);
            $uri = $controlleraction;
        }

        if ($get) {
            $data = self::requestChekTable($get, 1, $uri);
            $get = $data['get'];
            $getparams = $data['getparams'];
            $uri = $data['uri'];

            if ($get) {
                $cache = Cache::instance();
                $groups = $cache->get('filter_groupcheckappcontroller', 'filter');
                if (!$groups) {
                    $groups = \R::getAssoc('SELECT id, alias, color, sizes FROM attribute_group WHERE filter = 1 AND status = 1 ORDER BY sortvalue');
                    $cachetime = \ishop\App::$app->getProperty('cachetime_filter');
                    $cache->set('filter_groupcheckappcontroller', $groups, $cachetime, 'filter');
                }
                foreach ($groups as $v) {
                    if (isset($get[$v['alias']])) {
                        $val = $get[$v['alias']];
                        $pattern = $v['color'] ? "#[^a-z,]+#" : "#[^\d,]+#";
                        $val = preg_replace($pattern, '', $val);
                        $val = trim($val, ',');
                        $pattern = "#\s+#";
                        $val = preg_replace($pattern, '', $val);
                        if ($val) {
                            $getparams[$v['alias']] = $val;
                            $uri = \ishop\libs\URL::addGET($uri, $v['alias'], $val);
                        }
                    }
                }
            }
        }
        App::$app->setProperty('chek_uri', urldecode($uri));
        App::$app->setProperty('chek_get', $getparams);
    }

    protected static function requestChekTable($get = [], $metods_id = 1, $uri = '') {
        $metods_id = (int) $metods_id; // аудит 2026-11: приведение перед подстановкой в SQL
        $getparams = [];
        $cache = Cache::instance();
        $paramsTable = $cache->get('paramstable');
        $paramsTable = $cache->get('paramstable' . $metods_id, 'paramstable');

        if (!$paramsTable) {
            $cachetime = App::$app->getProperty('cachetime_getparams');
            $paramsTable = \R::getAssoc("SELECT id, name, deluri, setsession, setcookie, cookietime, pattern, onlyint FROM getparams WHERE metods_id = $metods_id");
            $cache->set('paramstable' . $metods_id, $paramsTable, $cachetime, 'paramstable');
        }


        foreach ($paramsTable as $v) {
            $val = '';

            if (isset($get[$v['name']])) {
                $val = $get[$v['name']];
                unset($get[$v['name']]);
                if ($v['pattern']) {
                    $val = preg_replace($v['pattern'], '', $val);
                }
                if ($v['onlyint']) {
                    $val = (int) $val;
                }
                if ($v['setsession'])
                    $_SESSION[v['name']] = $val;
                if ($v['setcookie'] && $v['cookietime'] > 0) {
                    setcookie($v['name'], $val, time() + $v['cookietime'], '/');
                }

                if (!$v['deluri'] && $val && $uri) {
                    $uri = \ishop\libs\URL::addGET($uri, $v['name'], $val);
                }
                if ($val) {
                    $getparams[$v['name']] = $val;
                }
            }
        }

        $data['uri'] = $uri;
        $data['getparams'] = $getparams;
        $data['get'] = $get;

        return $data;
    }

    public static function getTablePref($table = '') {
        return PREFIX . $table;
    }

    public static function getBasePattern() {
        $patternData['shop_name'] = App::$app->getProperty('shop_name');
        $patternData['shop_tel'] = App::$app->getProperty('shop_tel');
        $patternData['shop_tel_link'] = App::$app->getProperty('shop_tel_link');
        $patternData['shop_adress'] = App::$app->getProperty('shop_adress');
        $patternData['shop_email'] = App::$app->getProperty('shop_email');
        $patternData['shop_work'] = App::$app->getProperty('shop_work');

        return $patternData;
    }

    public static function getLastmodified($controler = '') {
        $lastmodified = \R::getRow("SELECT `expires`,`expires_index`,`update_at` FROM `lastmodified` WHERE `controler`= ? LIMIT 1", [$controler]);

        return $lastmodified;
    }

    public static function setLastmodified($update_at = '') {

        $LastModified_unix = strtotime($update_at);
        $LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);

        $IfModifiedSince = false;
        if (isset($_ENV['HTTP_IF_MODIFIED_SINCE'])) {
            $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
        }

        if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            exit;
        }
        header('Last-Modified: ' . $LastModified);
        //header("Expires: Thu, 01 Dec 2018 16:00:00 GMT");
    }

    protected function addParamsFromBd() {
//        $params_from_bd_status = App::$app->getProperty('params_from_bd_status');
//        if ($params_from_bd_status) {
        $cache = Cache::instance();
        $params_from_bd = $cache->get('params_from_bd');

        if (!$params_from_bd) {
            $params_from_bd = \R::getAssoc("SELECT `name`, `value`, `typevalue`, `pattern` FROM `params` WHERE `status` = 1");
            $cache->set('params_from_bd', $params_from_bd, 3600);
        }
        foreach ($params_from_bd as $key => $item) {

            switch ($item['typevalue']) {
                case 'int':
                    $v = (int) $item['value'];
                    break;
                case 'boll':
                    $v = (bool) $item['value'];
                    break;
                default:
                    $v = $item['value'];
            }
            If ($item['pattern']) {
                $pattern = "#" . $item['pattern'] . "#";
                if (!preg_match($pattern, $v)) {
                    continue;
                }
            }

            App::$app->setProperty($key, $v);
        }
//        }
    }

}
