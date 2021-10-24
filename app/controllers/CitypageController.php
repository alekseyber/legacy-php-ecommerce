<?php

namespace app\controllers;

use ishop\App;
use app\widgets\menu\Menu;
use app\widgets\filter\Filter;
use app\widgets\delivery\Delivery;

class CitypageController extends AppController {

    public function viewAction() {

        $alias = $this->route['alias'];
        $citypage = \R::getRow('SELECT * FROM `citypage` WHERE `alias` = ? AND `status` = 1 LIMIT 1', [$alias]);

        if (!$citypage) {
            throw new \Exception('Страница не найдена', 404);
        }

        $citysettings = \R::getRow('SELECT `content_top`, `content_bottom`, `divel_title` FROM `citysettings` WHERE `selected` = 1 LIMIT 1');

        self::uriChek();

        $linkCanonical = PATH . '/' . App::$app->getProperty('controlleraction');

        $contData['meta_title'] = $citypage['meta_title'];
        $contData['meta_description'] = $citypage['meta_description'];
        $contData['meta_keywords'] = $citypage['meta_keywords'];

        if (!$contData['meta_title'])
            $contData['meta_title'] = App::$app->getProperty('city_meta_title');
        if (!$contData['meta_description'])
            $contData['meta_description'] = App::$app->getProperty('city_meta_description');
        if (!$contData['meta_keywords'])
            $contData['meta_keywords'] = App::$app->getProperty('city_meta_keywords');

        $contData['htitle'] = $citypage['title'];
        $contData['promo'] = $citypage['promo'];
        $contData['content'] = $citypage['content'];
        $contData['content_top'] = $citysettings['content_top'];
        $contData['content_bottom'] = $citysettings['content_bottom'];
        $contData['divel_title'] = $citysettings['divel_title'];


        $params = [
            'addHeader' => false,
            'typeahead' => false,
            'receiverCityId' => $citypage['city_id'],
            'removePvz' => true,
        ];

        $contentDelivery = new Delivery($params);

        $patternData = self::getBasePattern();
        $patternData['city_name'] = $citypage['city_name'];
        $patternData['city_bname'] = $citypage['city_bname'];
        $patternData['city_cname'] = $citypage['city_cname'];
        $patternData['city_dname'] = $citypage['city_dname'];
        $patternData['city_iname'] = $citypage['city_iname'];
        $patternData['deliveryPriceValue'] = $contentDelivery->deliveryPriceValue;
        $patternData['deliveryPeriodMin'] = $contentDelivery->getPeriod(false);
        $patternData['deliveryPeriodMax'] = $contentDelivery->getPeriod();
        $patternData['deliveryPriceCurrency'] = $contentDelivery->getPriceCurrency();

        $contData = prRep($contData, $patternData);

        $contData['content_on'] = $citypage['content_on'];

        $seo_link_on = App::$app->getProperty('seo_link_on');
        if ($seo_link_on) {
            $contData['link'] = $citypage['seolink'];
        } else {
            $contData['link'] = 'citypage/' . $citypage['alias'];
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


        $contData['catsTop'] = \R::getAll("SELECT `title`, `seolink`, `alias`, `img` FROM `category` WHERE `maintop` = 1 AND `status` = 1 ORDER By `sortvalue` LIMIT 3");
        $contData['catPathImg'] = App::$app->getProperty('img_category_path');

        $product_ids = App::$app->getProperty('product_ids');
        $uri = App::$app->getProperty('filter_default_uri');
        $leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);

        //  $deliveryDataWIdjet = $contentDelivery->getDataWIdjet();
        if ($this->json) {
            $contentDelivery->setHtml();
            $res = [
                'content' => $this->loadViewContent('view', compact('contData', 'contentDelivery')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),                  
            ];
            
            exit(json_encode($res));
        }

        App::$app->setProperty('filter_content', $leftFilter);
        //  $this->setJs($contentDelivery->js, false, 'contentDeliveryJsCity');

        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical);
        $this->set(compact('contData', 'contentDelivery'));
    }

}
