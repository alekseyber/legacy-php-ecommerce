<?php

namespace app\controllers;

use ishop\App;
use app\widgets\menu\Menu;
use app\widgets\filter\Filter;
use ishop\libs\Pagination;
use ishop\Link;

class NewsController extends AppController {

    public function indexAction() {
        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            $lastmodified = self::getLastmodified('news');
            self::setLastmodified($lastmodified['update_at']);
        }
        self::uriChek();
        $linkCanonical = PATH . '/news';
        $getArr = App::$app->getProperty('chek_get');
        $page = isset($getArr['page']) ? $getArr['page'] : 1;
        $perpage = App::$app->getProperty('pagination_news');
        $seo_link_on = App::$app->getProperty('seo_link_on');
        $name = $seo_link_on ? 'link' : 'seolink';
        $postfix_meta = "";

        if ($page > 1) {
            $linkCanonical .= '?page=' . $page;
            $postfix_meta = " - Страница " . $page;
        }
        $total = \R::count('news', 'status = 1');
        $pagination = new Pagination($page, $perpage, $total);
        $start = $pagination->getStart();
        $sql_part = "SELECT `id`, `title`, `promo`, `img`, `alias`, `seolink` AS $name FROM `news` WHERE `status` = 1 ORDER BY `update_at` DESC LIMIT $start, $perpage";
        $paginContent = '';
        if ($perpage >= $total)
            $paginationstr = '';
        else
            $paginationstr = $pagination;

        $news = \R::getAssoc($sql_part);

        if (!$seo_link_on) {
            foreach ($news as &$v) {
                $v['link'] = 'news/' . $v['alias'];
            }
        }
        $paginContent = $this->loadViewContent('view_pagin', compact('news', 'paginationstr'));
        if ($this->json && isset($_POST['pagin'])) {
            exit(json_encode(['content' => $paginContent]));
            //  $this->loadView('view_pagin', compact('news', 'paginationstr'));
        }

        $shop_name = App::$app->getProperty('shop_name');
        $shop_tel = App::$app->getProperty('shop_tel');
        $shop_tel_link = App::$app->getProperty('shop_tel_link');
        $contData['meta_title'] = "Интернет-магазин $shop_name - Новости" . $postfix_meta;
        $contData['meta_description'] = "Интернет-магазин $shop_name блог и интересные новости." . $postfix_meta;
        $contData['meta_keywords'] = "блог, новости";
        $contData['htitle'] = "Блог $shop_name" . $postfix_meta;
        $contData['promo'] = '';


        // $menuCatalog = new Menu([
        //     'tpl' => WWW . '/menu/menu.php',
        //     'class' => 'nav-left nav-pills refinements-item-catproduct nav-stacked',
        // ]);
        // $product_ids = App::$app->getProperty('product_ids');
        // $uri = App::$app->getProperty('filter_default_uri');
        // $filter = new Filter([
        //     'ids' => $product_ids,
        //     'uri' => $uri,
        //     'tpl' => WWW . '/filter/filter_main_tpl.php'
        // ]);
        // $this->setJs($filter->js, false, 'Filter Price');

        $product_ids = App::$app->getProperty('product_ids');
        $uri = App::$app->getProperty('filter_default_uri');
        $leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);

        if ($this->json) {

//'content' => $this->loadViewContent('index', compact('news', 'contData', 'paginationstr')),
            $res = [
                'content' => $this->loadViewContent('index', compact('paginContent', 'contData')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
            ];

            exit(json_encode($res));
        }
        App::$app->setProperty('filter_content', $leftFilter);

        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical);

        // $this->set(compact('news', 'contData', 'paginationstr'));
        $this->set(compact('paginContent', 'contData'));
    }

    public function viewAction() {
        $alias = $this->route['alias'];

        $sql_part = "SELECT * FROM `news` WHERE `status` = 1 AND `alias` = ? LIMIT 1";
        $news_item = \R::getRow($sql_part, [$alias]);

        if (!$news_item) {
            throw new \Exception('Страница не найдена', 404);
        }
        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            self::setLastmodified($news_item['update_at']);
        }
        self::uriChek();
        $linkCanonical = PATH . '/' . App::$app->getProperty('controlleraction');

        $contData['meta_title'] = $news_item['meta_title'];
        $contData['meta_description'] = $news_item['meta_description'];
        $contData['meta_keywords'] = $news_item['meta_keywords'];
        $contData['htitle'] = $news_item['title'];
        $contData['promo'] = $news_item['promo'];
        $contData['content'] = $news_item['content'];
        $patternData = self::getBasePattern();
        $contData = prRep($contData, $patternData);
        $contData['social_link'] = Link::getSocialLink($linkCanonical);
        $seo_link_on = App::$app->getProperty('seo_link_on');

        if ($seo_link_on) {
            $contData['link'] = $news_item['seolink'];
        } else {
            $contData['link'] = 'news/' . $news_item['alias'];
        }
        // $menuCatalog = new Menu([
        //     'tpl' => WWW . '/menu/menu.php',
        //     'class' => 'nav-left nav-pills refinements-item-catproduct nav-stacked',
        // ]);
        // $product_ids = App::$app->getProperty('product_ids');
        // $uri = App::$app->getProperty('filter_default_uri');
        // $filter = new Filter([
        //     'ids' => $product_ids,
        //     'uri' => $uri,
        //     'tpl' => WWW . '/filter/filter_main_tpl.php'
        // ]);
        // $this->setJs($filter->js, false, 'Filter Price');
        // if ($this->isAjax()) {
        //     $js = $filter->js;
        //     $this->loadView('view', compact('filter', 'menuCatalog', 'contData'), $js);
        // }

        $product_ids = App::$app->getProperty('product_ids');
        $uri = App::$app->getProperty('filter_default_uri');
        $leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);

        if ($this->json) {

            $res = [
                'content' => $this->loadViewContent('view', compact('contData')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
            ];

            exit(json_encode($res));
        }
        App::$app->setProperty('filter_content', $leftFilter);

        $opengraph = [];
        if (App::$app->getProperty('on_opengraph')) {
            $opengraph['og:title'] = $contData['htitle'];
            $opengraph['og:type'] = 'article';
            $opengraph['og:description'] = $contData['meta_description'];
            $opengraph['og:url'] = $linkCanonical;
            $opengraph['og:locale'] = 'ru_RU';
            $img = $news_item['img'];
            if ($img && $img != "no_image.jpg") {
                $img_category_path = trim(App::$app->getProperty('img_news_path'), '/');
                $opengraph['og:image'] = PATH . '/' . $img_category_path . '/' . $img;
            }
        }
        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical, $opengraph);
        $this->set(compact('contData'));
    }

}
