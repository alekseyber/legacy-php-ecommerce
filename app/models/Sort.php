<?php

/*
 * Выводит select сортировки и передает параметры сортировки в Sql.
 */

namespace app\models;

use ishop\App;
use ishop\Cache;
use ishop\libs\URL;

class Sort
{

    public $sortSqlPart = '';
    protected $uri;
    protected $name;
    protected $post = 0;
    protected $sortValue = [];
    protected $default_value = 0;

    public function __construct($uri = '', $name = '', $post = [])
    {
        $this->name = $name;
        $this->uri = $uri;
        //  $this->post = $post;
        if (isset($post[$name])) {
            $this->post = (int) $post[$name];
        }

        if ($name && $uri)
            $this->run();
    }

    protected function run()
    {
        $this->getSortValue();
        if ($this->sortValue) {
            $this->getParams();
        }
    }

    protected function getSortValue()
    {
        $cache = Cache::instance();
        $cachetime = App::$app->getProperty('cachetime_sort');
        $this->sortValue = $cache->get('sort_value' . $this->name, 'sort');
        if (!$this->sortValue) {
            $sql_part = 'SELECT sortvalue.id, `title`, `tip`, `col_name`, `sort_default` FROM sortvalue ';
            $sql_part .= ' JOIN sortgroup ON sortvalue.sortgroup_id = sortgroup.id';
            $sql_part .= ' WHERE sortvalue.sost = "1" AND sortgroup.name = ? ORDER BY sortvalue';

            $data = \R::getAssoc($sql_part, [$this->name]);
            $this->sortValue = $data;
            $cache->set('sort_value' . $this->name, $this->sortValue, $cachetime, 'sort');
        }
    }

    protected function getParams()
    {
        $default_value = 0;
        $session_value = 0;
        $candidat_value = 0;
        $edit = false;

        $key = 'sort_' . $this->name;

        if ($this->post) {
            $candidat_value = $this->post;
        }

        if (isset($_SESSION[$key])) {
            $session_value = $_SESSION[$key];
        }
        // if (isset($_COOKIE[$this->name]))
        //     $set_value = $_COOKIE[$this->name];



        //  $set_value = ['sqlpart' => '', 'value' => (int) $set_value];
        $session_item = null;
        $default_item = null;
        $candidat_item = null;

        foreach ($this->sortValue as $k => $v) {
            if ($v['sort_default'] === '1') {
                $default_item = $v;
                $default_value = $k;
                // $default_value['sqlpart'] = $this->greatSqlPart($v);
                // $default_value['value'] = $k;
            }
            if ($k === $session_value) {
                $session_item = $v;
            }
            if ($k === $candidat_value) {
                $candidat_item = $v;
            }
            // if ($k == $set_value['value']) {
            //     $set_value['sqlpart'] = $this->greatSqlPart($v);
            // }
        }

        if ($candidat_item) {
            $this->sortSqlPart = $this->greatSqlPart($candidat_item);
            $this->default_value = $candidat_value;
            $edit = true;
        } else if ($session_item) {
            $this->sortSqlPart = $this->greatSqlPart($session_item);
            $this->default_value = $session_value;
        } else if ($default_item) {
            $this->sortSqlPart = $this->greatSqlPart($default_item);
            $this->default_value = $default_value;
        }

        if ($edit) {
            $_SESSION[$key] = $candidat_value;
        }

        // if ($set_value['value'])
        //     $default_value = $set_value;
        // $this->default_value = $default_value['value'];
        // $this->sortSqlPart = $default_value['sqlpart'];
    }

    protected function greatSqlPart($data = [])
    {
        $sqlPart = '';
        if (isset($data['tip']) && isset($data['col_name'])) {
            if ($data['tip'] && $data['col_name']) {
                $data['tip'] = ($data['tip'] == '2') ? ' DESC' : '';
                $sqlPart = $data['col_name'] . $data['tip'];
            }
        }

        return $sqlPart;
    }

    protected function getHtml()
    {
        $content = '';
        if ($this->sortValue) {
            $content = '<button class="btn btn-raised btn-secondary" type="button" data-bs-toggle="dropdown">Сортировка</button>' . PHP_EOL;
            $content .= '<div class="dropdown-menu">' . PHP_EOL;
            foreach ($this->sortValue as $k => $v) {
                $link = URL::addGET($this->uri, 'sortproduct', $k);
                $class = ($k === $this->default_value) ? "disabled js-sort" : 'js-sort';
                $content .= "<a class='dropdown-item $class' href='$link'>{$v['title']}</a>" . PHP_EOL;
            }
            $content .= '</div>' . PHP_EOL;
        }
        return $content;
    }

    public function __toString()
    {

        return $this->getHtml();
    }
}
