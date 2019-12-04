<?php

namespace app\controllers\admin;

class MainController extends AppController {

    public function indexAction() {
        $countNewOrders = \R::count('orders', "orderstatus_id = 1");
        //$countUsers = \R::count('user');
        $countUsers = 0;
        $countProducts = \R::count('product');
        $countCategories = \R::count('category');
        $countNews = \R::count('news');
        $countPage = \R::count('page');
        $countComment = \R::count('comment', 'status = 0');
        $countFilterGr = \R::count('attribute_group');
        $this->setMeta('Панель управления');
        $this->set(compact('countNewOrders', 'countCategories', 'countProducts', 'countUsers', 'countNews', 'countPage', 'countFilterGr', 'countComment'));
    }

}
