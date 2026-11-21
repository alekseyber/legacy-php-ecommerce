<?php

namespace app\models;

use ishop\base\Model;
use \ishop\App;
use ishop\Cache;

class AppModel extends Model {

    public static function createAlias($table, $field, $str, $id) {
        $str = self::str2url($str);
        $res = \R::findOne($table, "$field = ?", [$str]);
        // занятость алиаса в sef: остатки от удалённых и недосозданных товаров
        if (!$res && $table === 'product') {
            $res = \R::getCell('SELECT id FROM sef WHERE alias = ? LIMIT 1', ['product/' . $str]);
        }
        if ($res) {
            $str = "{$str}-{$id}";
            $res = \R::count($table, "$field = ?", [$str]);
            if ($res) {
                $str = self::createAlias($table, $field, $str, $id);
            }
        }
        return $str;
    }

    public static function createSeolink($str, $id) {

        $res = \R::findOne('sef', "link = ?", [$str]);
        if ($res) {
            $str = "{$str}-{$id}";
            $res = \R::count('sef', "link = ?", [$str]);
            if ($res) {
                $str = self::createSeolink($str, $id);
            }
        }
        return $str;
    }

    public static function getHeaderStr() {
        $header = ['header' => '', 'footer' => ''];
        $res = \R::findOne('headerstr', "current = 1");
        if ($res) {
            $header = ['header' => $res->header, 'footer' => $res->footer];
        }
        return $header;
    }

    public static function str2url($str) {
        // переводим в транслит
        $str = self::rus2translit($str);
        // в нижний регистр
        $str = strtolower($str);
        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
        // удаляем начальные и конечные '-'
        $str = trim($str, "-");
        return $str;
    }

    public static function rus2translit($string) {

        $converter = array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        );

        return strtr($string, $converter);
    }

    public static function getTableRow($name = false, $id = 0, $cacheOn = true, $chek = false) {
        if (!$name && !$id)
            return false;
        $data = [];
        $name = preg_replace('#\s|\(|\)+#', '', $name);
        if ($cacheOn) {
            $cache = Cache::instance();
            $cachetime = App::$app->getProperty('cachetime_' . $name) ? App::$app->getProperty('cachetime_' . $name) : 3600;
            $data = $cache->get($name . $id, $name);
        }

        if (!$data) {
            if ($chek) {
                $count = \R::count($name, ' id = ? ', [$id]);
                if (!$count)
                    return false;
            }
            $data = \R::getRow("SELECT * FROM $name WHERE id = ? LIMIT 1", [$id]);
            if ($cacheOn)
                $cache->set($name . $id, $data, $cachetime, $name);
        }
        return $data;
    }

    public static function setAllLastmodified($cat_id = 0) {
        \R::exec('UPDATE `lastmodified` SET `update_at`= ?', [date('Y-m-d H:i:s')]);
        \R::exec('UPDATE `category` SET `update_at`= ? WHERE `cat_default` = 1', [date('Y-m-d H:i:s')]);

        if ($cat_id) {
            $cat_default_id = \R::getCell('SELECT `id` FROM `category` WHERE `cat_default` = 1');
            $cats = \R::getAssoc("SELECT `id`, `parent_id` FROM category WHERE status = 1 ORDER BY sortvalue");
            $parent_id = self::getParrents($cat_default_id, $cats, $cat_id);
            \R::exec('UPDATE `category` SET `update_at`= ? WHERE `id` = ?', [date('Y-m-d H:i:s'), $parent_id]);
        }
    }

    public static function getParrents($cat_default_id, $cats, $cat_id) {

        if ($cats[$cat_id] == $cat_default_id || $cats[$cat_id] == 0) {
            $rezult = $cat_id;
        } else {
            $cat_id = $cats[$cat_id];
            $rezult = $cat_id;
            self::getParrents($cat_default_id, $cats, $cat_id);
        }

        return $rezult;
    }

}
