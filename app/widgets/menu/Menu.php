<?php

namespace app\widgets\menu;

use ishop\App;
use ishop\Cache;
use RedUNIT\Base\Threeway;

class Menu {

    protected $data;
    protected $tree;
    protected $menuHtml;
    protected $tpl;
    protected $container = 'ul';
    protected $class = 'menu';
    protected $table = 'category';
    protected $cache = 3600;
    protected $cachetree = 3600;
    protected $cacheKey = 'ishop_menu';
    protected $cacheKeyTree = 'menu_tree';
    protected $attrs = [];
    protected $prepend = '';
    protected $seolink = '';
    protected $default_view = true;
    protected $delitel = 'ul';
    protected $delitel_class = 'item-child';
    protected $count_del = 0;
    protected $admin = false;

    public function __construct($options = []) {
        $this->tpl = __DIR__ . '/menu_tpl/menu.php';
        $this->cache = \ishop\App::$app->getProperty('cachetime_menu');
        $this->cachetree = \ishop\App::$app->getProperty('cachetime_menu_tree');
        $this->getOptions($options);
        $this->run();
    }

    protected function getOptions($options) {
        //debug ($options);
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    protected function run() {
        $cache = Cache::instance();
        $this->menuHtml = $cache->get($this->cacheKey . $this->seolink);
        if (!$this->menuHtml || $this->admin) {
            if (!$this->admin) {
                $this->data = App::$app->getProperty('cats');
            } else {
                $this->data = [];
            }

            if (!$this->data) {
                $this->data = $cats = \R::getAssoc("SELECT * FROM {$this->table} ORDER BY sortvalue");
            }
            if (!$this->admin) {
                $this->tree = $cache->get($this->cacheKeyTree);
            } else {
                $this->tree = [];
            }

            if (!$this->tree) {
                $this->tree = $this->getTree();
                if ($this->cachetree && !$this->admin) {
                    $cache->set($this->cacheKeyTree, $this->tree, $this->cachetree, 'menu');
                }
            }
            $this->menuHtml = $this->getMenuHtml($this->tree, $this->seolink);
            if ($this->cache) {
                $cache->set($this->cacheKey . $this->seolink, $this->menuHtml, $this->cache, 'menu');
            }
        }
    }

    public function __toString() {
        return $this->output();
    }

    protected function output() {
        $content = '';
        $attrs = '';
        if (!empty($this->attrs)) {
            foreach ($this->attrs as $k => $v) {
                $attrs .= " $k='$v' ";
            }
        }
        if ($this->container) {
            $content = "<{$this->container} class='{$this->class}'$attrs>" . PHP_EOL;
            $content .= $this->prepend ? $this->prepend . PHP_EOL : '';
            $content .= $this->menuHtml;
            $content .= "</{$this->container}>" . PHP_EOL;
        } else {
            $content = $this->prepend ? $this->prepend . PHP_EOL : '';
            $content .= $this->menuHtml;
        }


        return $content;
    }

    protected function getTree() {
        $tree = [];
        $data = $this->data;
        $parent_id = '';
        foreach ($data as $id => &$node) {

            if ($node['cat_default']) {
                if ($this->default_view)
                    $tree[$id] = &$node;
                $parent_id = $id;
                continue;
            }

            if (!$node['parent_id'] || $parent_id == $node['parent_id']) {
                $tree[$id] = &$node;
            } else {
                $data[$node['parent_id']]['childs'][$id] = &$node;
            }
        }

        return $tree;
    }

    protected function getMenuHtml($tree, $tab = '') {
        $str = '';
        $i = 0;
        $str_start = '';
        $str_end = '';
        $str_start = '<' . $this->delitel . ' class="' . $this->delitel_class . '">';
        if ($this->count_del > 0) {
            $str_start = '<' . $this->delitel . ' class="' . $this->delitel_class . '">';
            $str_end = '</' . $this->delitel . '>';
        }

        foreach ($tree as $id => $category) {
            if ($this->count_del > 0 && !isset($category['childs'])) {
                if ($this->count_del == $i) {
                    $str .= $str_end . PHP_EOL . $str_start . PHP_EOL . $this->catToTemplate($category, $tab, $id) . PHP_EOL;
                } else {
                    $str .= $this->catToTemplate($category, $tab, $id) . PHP_EOL;
                }

                $i++;
            } else {
                $str .= $this->catToTemplate($category, $tab, $id) . PHP_EOL;
            }
        }
        //echo $str . "<br>";
        return $str;
    }

    protected function catToTemplate($category, $tab, $id) {
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }

    public function getJson() {

        return $this->data;
    }

}
