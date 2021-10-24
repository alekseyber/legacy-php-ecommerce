<?php

namespace app\controllers;

use ishop\App;
use app\widgets\menu\Menu;
use app\widgets\filter\Filter;
use \app\models\Product;
use ishop\libs\Pagination;

class SearchController extends AppController {

    public function typeaheadAction() {

        if ($this->json) {
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
            if ($query) {
                $products = Product::searchProduct($query, 10);               
                exit(json_encode($products));              
            }
        }
        die;
    }

    public function indexAction() {
        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            $lastmodified = self::getLastmodified('search');
            self::setLastmodified($lastmodified['update_at']);
        }
        self::uriChek();

        $linkCanonical = PATH . '/search';
        $getArr = App::$app->getProperty('chek_get');
        $query = '';
        $rezult = [];
        if (isset($getArr['s'])) {
            $query = !empty(trim($getArr['s'])) ? trim($getArr['s']) : null;
            if ($query)
                $rezult = Product::searchProduct($query);
        }
        $query_str = h($query);

        $total = count($rezult);
        $page = isset($getArr['page']) ? $getArr['page'] : 1;
        $perpage = App::$app->getProperty('pagination');
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();

        if ($perpage >= $total) {
            $paginationstr = '';
        } else {
            $paginationstr = $pagination;
            $rezult = [];
        }

        $contData['meta_title'] = "Поиск по: $query_str - товаров не найдено";
        $contData['meta_description'] = "С учетом текущих условий поиска - товаров не найдено, попробуйте изменить поисковые фразы.";
        $contData['meta_keywords'] = "";
        $contData['htitle'] = "Поиск по: '$query_str' - товаров не найдено";
        $contData['promo'] = 'С учетом текущих условий поиска - товаров не найдено, попробуйте изменить поисковые фразы.';
        $products = [];
        $paginContent = '';
        if ($total) {
            if (!$rezult)
                $rezult = Product::searchProduct($query, 0, true, $start, $perpage);

            $p_model = new Product();
            $products = $p_model->setRezultProducts($rezult);

            $paginContent = $this->loadViewContent('view_pagin', compact('products', 'paginationstr'));

            if ($this->json && isset($_POST['pagin'])) {
                exit(json_encode(['content' => $paginContent]));
                // $this->loadView('view_pagin', compact('products', 'paginationstr'));
            }
            $pr = getWord($total, ["найден", "найдено", "найдено"]);
            $text = getWord($total);

            $contData['meta_title'] = "Поиск по: $query_str - $pr $total $text";
            $contData['meta_description'] = "С учетом текущих условий поиска - $pr $total вариантов.";
            $contData['htitle'] = "Поиск по: '$query_str' - $pr $total $text";
            $contData['promo'] = "С учетом текущих условий поиска - $pr $total $text, результаты ниже.";
        }

        // $menuCatalog = new Menu([
        //     'tpl' => WWW . '/menu/menu.php',
        //     'class' => 'nav-left nav-pills refinements-item-catproduct nav-stacked',
        // ]);

        $product_ids = App::$app->getProperty('product_ids');
        $uri = App::$app->getProperty('filter_default_uri');
        $leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);
        //$this->setJs($filter->js, false, 'Filter Price');

        if ($this->json) {
            // $js = $filter->js;
            // $this->loadView('index', compact('products', 'paginationstr', 'total', 'filter', 'menuCatalog', 'contData'), $js);
            // 'content' => $this->loadViewContent('index', compact('products', 'paginationstr', 'total', 'contData')),
            $res = [
                'content' => $this->loadViewContent('index', compact('paginContent', 'total', 'contData')),
                'historyset' => 1,
                // 'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
            ];

            exit(json_encode($res));
        }
        App::$app->setProperty('filter_content', $leftFilter);
        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical);
        $this->set(compact('paginContent', 'total', 'contData'));
    }

}
