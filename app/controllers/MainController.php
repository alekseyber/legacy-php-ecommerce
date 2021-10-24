<?php

namespace app\controllers;

use ishop\Cache;
use ishop\App;
//use app\widgets\menu\Menu;
use app\widgets\slider\Slider;
use app\widgets\filter\Filter;
use app\models\Product;

//use app\models\Sort;

class MainController extends AppController {

    public function indexAction() {

        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            $lastmodified = self::getLastmodified('main');
            self::setLastmodified($lastmodified['update_at']);
        }
        self::uriChek();
        $product_ids = App::$app->getProperty('product_ids');
        $cache = Cache::instance();
        $content = $cache->get('maincontent', 'maincontent');

        if (!$content) {

            $cachetime = App::$app->getProperty('cachetime_maincontent');
            $contentData = \R::getRow("SELECT title, promo, content, meta_title, meta_description, meta_keywords FROM page WHERE main = '1' LIMIT 1");
            $name = App::$app->getProperty('shop_name');
            $shop_tel = App::$app->getProperty('shop_tel');
            $contentData = prRep($contentData, ['name' => $name, 'shop_tel' => $shop_tel]);

            $catsTop = \R::getAll("SELECT `title`, `seolink`, `alias`, `img` FROM `category` WHERE `maintop` = 1 AND `status` = 1 ORDER By `sortvalue` LIMIT 3");
            $catPathImg = App::$app->getProperty('img_category_path');

            $hit_count = App::$app->getProperty('hit_count');
            $p_model = new Product();
            $productsHits = [];
            if ($hit_count) {
                $hits = Product::getAllProducts($product_ids, 1, false, 0, 1, false, false);
                $productsHits = $p_model->setProducts($hits);
            }
            //пока отключено
            // $sales_count = App::$app->getProperty('sales_count');
            //$productsSales = [];
            // if ($sales_count) {
            //     $addJsSwPr = true;
            //     $sales = Product::getAllProducts($product_ids, 2, false, 0, 1, false, false);
            //     $productsSales = $p_model->setProducts($sales);
            // } 
            $newpr_count = App::$app->getProperty('newpr_count');
            $productsNew = [];
            if ($newpr_count) {
                $newpr = Product::getAllProducts($product_ids, 3, false, 0, 1, false, false);
                $productsNew = $p_model->setProducts($newpr);
            }
            $main_comment_count = App::$app->getProperty('main_comment_count');
            $comments = [];

            if ($main_comment_count) {
                $comments = \R::getAll("SELECT `social`,`author_name`,`author_avatar`,`commen_text`, `date` FROM `comment` WHERE `status` = 1 ORDER BY `date` DESC LIMIT $main_comment_count");
            }

            $topslider = new Slider([
                'id' => 1,
            ]);

            $content = [
                'contentData' => $contentData,
                'catsTop' => $catsTop,
                'catPathImg' => $catPathImg,
                'productsHits' => $productsHits,
                'productsNew' => $productsNew,
                'comments' => $comments,
                'topslider' => $topslider,
            ];
            // debug($content,1);
            $cache->set('maincontent', $content, $cachetime, 'maincontent');
        }

        $uri = App::$app->getProperty('filter_default_uri');
        $filter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);



        $content['valTagsHtml'] = $filter->valTagsHtml;

        if ($this->json) {

            $res = [
                'content' => $this->loadViewContent('index', compact('content')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $filter->toString(),
            ];

            exit(json_encode($res));
        }


        App::$app->setProperty('filter_content', $filter);
        $opengraph = [];
        $linkCanonical = '/' . App::$app->getProperty('controlleraction'); //PATH .
        if (App::$app->getProperty('on_opengraph')) {
            $opengraph['og:title'] = $content['contentData']['meta_title'];
            $opengraph['og:type'] = 'website';
            $opengraph['og:description'] = $content['contentData']['meta_description'];
            $opengraph['og:url'] = $linkCanonical;
            $opengraph['og:locale'] = 'ru_RU';
        }
        
        $this->setMeta(h($content['contentData']['meta_title']), h($content['contentData']['meta_description']), h($content['contentData']['meta_keywords']), $linkCanonical, $opengraph);
        $this->set(compact('content'));
    }

}
