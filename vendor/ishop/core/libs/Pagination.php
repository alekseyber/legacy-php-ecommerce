<?php

namespace ishop\libs;

use ishop\Link;
use ishop\libs\URL;
use ishop\App;

class Pagination {

    public $currentPage;
    public $perpage;
    public $total;
    public $countPages;
    public $uri;
    public $frwd;
    public $frwd_txt;
    public $isAdmin = false;

    public function __construct($page, $perpage, $total, $baseurl = false, $frwd = false, $frwd_txt = 'К предыдущим записям', $isAdmin = false) {

        $this->isAdmin = $isAdmin;
        $this->perpage = $perpage;
        $this->total = $total;
        $this->countPages = $this->getCountPages();
        $this->currentPage = $this->getCurrentPage($page);
        $this->uri = $this->getParams();
        $this->uri = ltrim($this->uri, '/');
        if ($baseurl) {
            $this->uri = '/' . $this->uri;
        } else {
            $this->uri = Link::getLink($this->uri);
        }
        $this->uri = urldecode($this->uri);
        $this->frwd = $frwd;
        $this->frwd_txt = $frwd_txt;
    }

    public function getHtml() {
        $back = null; // ссылка НАЗАД
        $forward = null; // ссылка ВПЕРЕД
        $startpage = null; // ссылка В НАЧАЛО
        $endpage = null; // ссылка В КОНЕЦ
        $page2left = null; // вторая страница слева
        $page1left = null; // первая страница слева
        $page2right = null; // вторая страница справа
        $page1right = null; // первая страница справа

        if ($this->currentPage > 1) {
            $href = URL::addGET($this->uri, 'page', $this->currentPage - 1);
            $back = "<li class='page-item'><a class='page-link js-link-pagin' href='{$href}' aria-label = 'Previous'><span aria-hidden = 'true'><i class='fa fa-arrow-left' aria-hidden='true'></i></span><span class = 'sr-only'>Previous</span></a></li>" . PHP_EOL;
        }
        if ($this->currentPage < $this->countPages) {
            if ($this->frwd) {
                $href = URL::addGET($this->uri, 'page', $this->currentPage + 1);
                return "<div class='next-pagination text-center'>" . PHP_EOL . "<a href='{$href}' class='btn btn-transparent next-pagination-btn'>{$this->frwd_txt}</a>" . PHP_EOL . '</div>' . PHP_EOL;
            }
            $href = URL::addGET($this->uri, 'page', $this->currentPage + 1);
            $forward = "<li class='page-item'><a class='page-link js-link-pagin' href='{$href}' aria-label = 'Next'><span aria-hidden = 'true'><i class='fa fa-arrow-right' aria-hidden='true'></i></span><span class = 'sr-only'>Next</span></a></li>" . PHP_EOL;
        }
        if ($this->frwd) {
            return '';
        }
        if ($this->currentPage > 3) {
            $href = URL::addGET($this->uri, 'page', 1);
            $startpage = "<li class='page-item'><a class='nav-link js-link-pagin' href='{$href}'><i class='fa fa-fast-backward' aria-hidden='true'></i></a></li>" . PHP_EOL;
        }
        if ($this->currentPage < ($this->countPages - 2)) {
            $href = URL::addGET($this->uri, 'page', $this->countPages);
            $endpage = "<li class='page-item'><a class='nav-link js-link-pagin' href='{$href}'><i class='fa fa-fast-forward' aria-hidden='true'></i></a></li>" . PHP_EOL;
        }
        if ($this->currentPage - 2 > 0) {
            $href = URL::addGET($this->uri, 'page', $this->currentPage - 2);
            $page2left = "<li class='page-item'><a class='nav-link js-link-pagin' href='{$href}'>" . ($this->currentPage - 2) . "</a></li>" . PHP_EOL;
        }
        if ($this->currentPage - 1 > 0) {
            $href = URL::addGET($this->uri, 'page', $this->currentPage - 1);
            $page1left = "<li class='page-item'><a class='nav-link js-link-pagin' href='{$href}'>" . ($this->currentPage - 1) . "</a></li>" . PHP_EOL;
        }
        if ($this->currentPage + 1 <= $this->countPages) {
            $href = URL::addGET($this->uri, 'page', $this->currentPage + 1);
            $page1right = "<li class='page-item'><a class='nav-link js-link-pagin' href='{$href}'>" . ($this->currentPage + 1) . "</a></li>" . PHP_EOL;
        }
        if ($this->currentPage + 2 <= $this->countPages) {
            $href = URL::addGET($this->uri, 'page', $this->currentPage + 2);
            $page2right = "<li class='page-item'><a class='nav-link js-link-pagin' href='{$href}'>" . ($this->currentPage + 2) . "</a></li>" . PHP_EOL;
        }

        return '<nav class="pagination-product" aria-label="Page navigation">' . PHP_EOL . '<ul class="pagination justify-content-center">' . $startpage . $back . $page2left . $page1left . '<li class="page-item active">' . '<a class="page-link">' . $this->currentPage . '<span class="sr-only">(current)</span></a></li>' . $page1right . $page2right . $forward . $endpage . '</ul>' . PHP_EOL . '</nav>';
    }

    public function __toString() {
        return $this->getHtml();
    }

    public function getCountPages() {
        return ceil($this->total / $this->perpage) ?: 1;
    }

    public function getCurrentPage($page) {
        if (!$page || $page < 1)
            $page = 1;
        if ($page > $this->countPages)
            $page = $this->countPages;
        return $page;
    }

    public function getStart() {
        return ($this->currentPage - 1) * $this->perpage;
    }

    public function getParams() {

        if (!$this->isAdmin) {
            $uri = App::$app->getProperty('chek_uri');
            $uri = trim(URL::deletePage($uri));
            return $uri;
        }

        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim(URL::deletePage($uri));

        return $uri;
    }

}