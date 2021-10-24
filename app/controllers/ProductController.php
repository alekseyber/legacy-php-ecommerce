<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Product;
use ishop\App;
use ishop\Cache;
use app\widgets\filter\Filter;
use ishop\libs\URL;
use ishop\Link;

class ProductController extends AppController {

    public function viewAction() {


        $alias = $this->route['alias'];
        self::uriChek();
        $controlleraction = App::$app->getProperty('controlleraction');
        $getArr = App::$app->getProperty('chek_get');
        $uri = App::$app->getProperty('chek_uri');
        $p_model = new Product();

        $controlleraction = Link::getLink($controlleraction);
        $editcatritem = $getArr['editcatritem'] ?? 0;
        if ($editcatritem) {
            $controlleraction = URL::addGET($controlleraction, 'editcatritem', $editcatritem);
        }

        $product = $p_model->setProduct($alias, $getArr, $controlleraction);

        if (!$product) {
            throw new \Exception('Страница не найдена', 404);
        }
        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            self::setLastmodified($product['update_at']);
        }
        $product['edit'] = 0;
        $product['b_cart_text'] = 'В корзину';
        if ($editcatritem) {
            $product['cardData']['edit'] = $editcatritem;
            $product['b_cart_text'] = 'Обновить в корзине';
            $product['edit'] = 1;
        }

        $product['gallery'] = [];

        $controlleraction = ltrim($controlleraction, '/');
        $linkCanonical = PATH . '/' . $controlleraction;
        $addcolor = '';
        if ($product['color_default'] != $product['color_default_base']) {
            $linkCanonical = URL::addGET($linkCanonical, 'color', $product['color_default']);
            $addcolor = $product['color_title'];
            $char = mb_strtoupper(substr($addcolor, 0, 2), "utf-8");
            $addcolor[0] = $char[0];
            $addcolor = ' - ' . $addcolor;
        }


        $product['social_link'] = Link::getSocialLink($linkCanonical);
        Product::addDannProduct($product, true);
        if ($this->json) {
            if (isset($_POST['productcard']) && $_POST['productcard'] == 'mainproduct') {

                // $this->loadView('mainproduct', compact('product'));

                $res = [
                    'content' => $this->loadViewContent('mainproduct', compact('product')),
                ];

                exit(json_encode($res));
            }

            if (isset($_POST['productcard']) && $_POST['productcard'] == 'product_modal') {


                $res = [
                    'content' => $this->loadViewContent('product_modal', compact('product')),
                ];

                exit(json_encode($res));
            }


            //throw new \Exception('Страница не найдена', 404);
            //die;
        }

        $data_bread = [
            'title' => $product['title'],
            'seolink' => $product['seolink'],
            'alias' => 'product/' . $product['alias']
        ];
        // хлебные крошки
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($product['category_id'], $data_bread);


        // связанные товары       
        $related = null;
        if ($product['related']) {
            // $arr = [$product['related']];
            // $products = Product::getAllProducts($arr);
            // $newpr = Product::getAllProducts($products, 3, false, 0, 1, false, false);
            // $related= $p_model->setProducts($arr);

            $related = $p_model->setProduct(false, [], false, $product['related'], true);
        }


        // запись в куки запрошенного товара
        $p_model->setRecentlyViewed($product['id']);

        // просмотренные товары
        $r_viewed = $p_model->getRecentlyViewed();
        $recentlyViewed = null;

        // if ($r_viewed) {
        //     foreach ($r_viewed as $v) {
        //         $recentlyViewed[$v] = $p_model->setProduct(false, [], false, $v);
        //     }
        // }
        if ($r_viewed) {
            $products = Product::getAllProducts($r_viewed);
            $recentlyViewed = $p_model->setProducts($products);
        }

        $cache = Cache::instance();
        $cachetime = App::$app->getProperty('cachetime_product_content');
        $curr = \ishop\App::$app->getProperty('currency');
        $cache_key = $product['alias'] . $product['seolink'] . $product['sku'] . $product['id'] . $curr['code'];
        $contData = $cache->get($cache_key, 'product');
        //$contData = [];
        if (!$contData) {
            $contData['meta_title'] = $product['meta_title'];
            $contData['meta_description'] = $product['meta_description'];			
            $contData['meta_keywords'] = $product['meta_keywords'];
            if (!$contData['meta_title'])
                $contData['meta_title'] = App::$app->getProperty('product_meta_title');
            if (!$contData['meta_description'])
                $contData['meta_description'] = App::$app->getProperty('product_meta_description');
            if (!$contData['meta_keywords'])
                $contData['meta_keywords'] = App::$app->getProperty('product_meta_keywords');

            $contData['content'] = $product['content'];
            //  $filterContent = new \app\widgets\filter\Filter($product['id'], false, WWW . '/filter/oneproduct_filter_tpl.php', true, true, false, false);
            //$ids = '', $uri = '', $tpl = '', $check = true, $oneproduct = false, $cartproduct = false, $status = true, $cache = true
            $filterContent = new Filter([
                'ids' => $product['id'],
                'tpl' => WWW . '/filter/oneproduct_filter_tpl.php',
                'oneproduct' => true,
            ]);
            $patternData = $filterContent->getPatternArray();
            Product::addProductPattern($product, $patternData);
            $contData = prRep($contData, $patternData);
            $contData['filter'] = $filterContent->getHtml();
            $cache->set($cache_key, $contData, $cachetime, 'product');
        }
        $contData['meta_title'] = $contData['meta_title'] . $addcolor;
		$contData['meta_description'] = $contData['meta_description'] . $addcolor;
		
        $content = $contData['content'];
        $filter = $contData['filter'];

        $product_ids = App::$app->getProperty('product_ids');
        $uri = App::$app->getProperty('filter_default_uri');
        $leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);

        if ($this->json) {
            $res = [
                'content' => $this->loadViewContent('view', compact('product', 'content', 'related', 'recentlyViewed', 'breadcrumbs', 'filter')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
                    //  'js' => $js
            ];

            exit(json_encode($res));

            // $this->loadView('view', compact('product', 'content', 'related', 'recentlyViewed', 'breadcrumbs', 'filter'));
        }
        $opengraph = [];


        if (App::$app->getProperty('on_opengraph')) {
            $opengraph['og:title'] = $product['title'] . ' ' . $product['gender'];
            //$opengraph['og:title'] = $contData['meta_title'];
            $opengraph['og:type'] = 'article';
            $opengraph['og:description'] = $contData['meta_description'];
            $opengraph['og:url'] = $linkCanonical;
            $opengraph['og:locale'] = 'ru_RU';
            $img = $product['img_main'];
            if ($img) {
                $img = trim($img, '/');
                $opengraph['og:image'] = PATH . '/' . $img;
            }
        }
        //  $this->setJs($js, false, 'mainProduct');
        App::$app->setProperty('filter_content', $leftFilter);
        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical, $opengraph);
        $this->set(compact('product', 'content', 'related', 'recentlyViewed', 'breadcrumbs', 'filter'));
    }

}
