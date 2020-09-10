<?php

namespace ishop\base;

class View {

    public $route;
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $layout;
    public $data = [];
    public $meta = [];
    public $js = [];
    public $css = [];
    public $header = ['header' => '', 'footer' => ''];

    public function __construct($route, $layout = '', $view = '', $meta, $js, $css = [], $header = ['header' => '', 'footer' => '']) {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->view = $view;
        $this->model = $route['controller'];
        $this->prefix = $route['prefix'];
        $this->meta = $meta;
        $this->js = $js;
        $this->css = $css;
        $this->header = $header;
        if ($layout === false) {
            $this->layout = false;
        } else {
            $this->layout = $layout ?: LAYOUT;
        }
    }

    public function render($data) {
        if (is_array($data))
            extract($data);
        $this->prefix = str_replace('\\', '/', $this->prefix);
        $viewFile = APP . "/views/{$this->prefix}{$this->controller}/{$this->view}.php";
        if (is_file($viewFile)) {
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
        } else {
            throw new \Exception("На найден вид {$viewFile}", 500);
        }
        if (false !== $this->layout) {
            $layoutFile = APP . "/views/layouts/{$this->layout}.php";
            if (is_file($layoutFile)) {
                require_once $layoutFile;
            } else {
                throw new \Exception("На найден шаблон {$this->layout}", 500);
            }
        }
    }

    public function getMeta() {
        $output = '<title>' . $this->meta['title'] . '</title>' . PHP_EOL;
        $output .= '<meta name="description" content="' . $this->meta['desc'] . '">' . PHP_EOL;
        $output .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;
        if (!empty($this->meta['canonical']))
            $output .= '<link rel="canonical" href="' . $this->meta['canonical'] . '" />' . PHP_EOL;
        if (!empty($this->meta['opengraph'])) {
            foreach ($this->meta['opengraph'] as $k => $v) {
                $output .= '<meta property="' . h($k) . '" content="' . h($v) . '" />' . PHP_EOL;
            }
        }
        return $output;
    }

    public function getJs() {
        $output = '';
        if ($this->js) {
            foreach ($this->js as $v) {
                $output .= $v . PHP_EOL;
            }
        }
        return $output;
    }

    public function getCss() {
        $output = '';
        if ($this->css) {
            foreach ($this->css as $v) {
                $output .= $v . PHP_EOL;
            }
        }
        return $output;
    }

    public function getHeader() {
        $output = $this->header['header'];
        return $output;
    }

    public function getFooterJs() {
        $output = $this->header['footer'];
        return $output;
    }

}
