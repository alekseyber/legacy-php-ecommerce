<?php

namespace app\controllers;

use ishop\App;
use app\widgets\menu\Menu;
use app\widgets\filter\Filter;

//use \app\models\Product;

class PageController extends AppController {

    public function viewAction() {

        $alias = $this->route['alias'];
        $page = \R::getRow('SELECT * FROM `page` WHERE `alias` = ? AND `status` = 1 AND `main` = 0 LIMIT 1', [$alias]);

        if (!$page) {
            throw new \Exception('Страница не найдена', 404);
        }
        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            self::setLastmodified($page['update_at']);
        }
        self::uriChek();

        $linkCanonical = PATH . '/' . App::$app->getProperty('controlleraction');

        $contData['meta_title'] = $page['meta_title'];
        $contData['meta_description'] = $page['meta_description'];
        $contData['meta_keywords'] = $page['meta_keywords'];
        $contData['htitle'] = $page['title'];
        $contData['promo'] = $page['promo'];
        $contData['content'] = $page['content'];
        $patternData = self::getBasePattern();
        $contData = prRep($contData, $patternData);
        $seo_link_on = App::$app->getProperty('seo_link_on');
        if ($seo_link_on) {
            $contData['link'] = $page['seolink'];
        } else {
            $contData['link'] = 'page/' . $page['alias'];
        }


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

        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical);
        $this->set(compact('contData'));
    }

}
