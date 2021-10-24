<?php

namespace ishop\base;

abstract class Controller {

    public $route;
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $layout;
    public $data = [];
    public $meta = ['title' => '', 'desc' => '', 'keywords' => '', 'canonical' => ''];
    public $js = [];
    public $css = [];
    public $header = ['header' => '', 'footer' => ''];

    public function __construct($route) {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];
    }

    public function getView() {
        $viewObject = new View($this->route, $this->layout, $this->view, $this->meta, $this->js, $this->css, $this->header);
        $viewObject->render($this->data);
    }

    public function set($data) {
        $this->data = $data;
    }

    public function setMeta($title = '', $desc = '', $keywords = '', $canonical = '', $opengraph = []) {
        $this->meta['title'] = h($title);
        $this->meta['desc'] = h($desc);
        $this->meta['keywords'] = h($keywords);
        $this->meta['canonical'] = h($canonical);
        $this->meta['opengraph'] = $opengraph;
    }

    public function setJs($data = false, $src = false, $comment = false, $noTag = false) {
        if (($comment) && ($data || $src))
            $this->js[] = '<!-- ' . $comment . ' -->' . PHP_EOL;

        if (($noTag) && $data) {
            $this->js[] = $data . PHP_EOL;
            return true;
        }

        if ($data) {
            $this->js[] = '<script>' . PHP_EOL . $data . PHP_EOL . '</script>';
            return true;
        }
        if ($src) {
            $this->js[] = '<script src="' . $src . '"></script>';
            return true;
        }
        return false;
    }

    public function setCss($src = false) {
        if ($src) {
            $this->css[] = '<link href="' . $src . '" rel="stylesheet">';
            return true;
        }
        return false;
    }

    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    public function loadView($view, $vars = [], $js = '', $jssrc = '') {
        extract($vars);
        //ob_start("ob_gzhandler");
        $this->prefix = str_replace('\\', '/', $this->prefix);
        require APP . "/views/{$this->prefix}{$this->controller}/{$view}.php";

        if ($jssrc) {
            echo $jssrc;
        }
        if ($js) {
            $js = '<script>' . $js . '</script>';
            echo $js;
        }

        die;
    }

    public function loadViewContent($view, $vars = []) {
        $this->prefix = str_replace('\\', '/', $this->prefix);
        extract($vars);
        ob_start();
        require APP . "/views/{$this->prefix}{$this->controller}/{$view}.php";
        return ob_get_clean();
    }

}
