<?php

namespace app\controllers;

use ishop\App;
use app\widgets\menu\Menu;
use app\widgets\filter\Filter;

class ErrorController extends AppController {

    public function indexAction() {

        $contData['meta_title'] = "Запрошенная страница не найдена";
        $contData['meta_description'] = "Неправильно набран адрес, или такой страницы на сайте больше не существует.";
        $contData['meta_keywords'] = "404";

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

        if ($this->json) {          

            $res = [
                'content' => $this->loadViewContent('index'),
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
                'succes' => false,
//                'historyset' => 1,
            ];

            exit(json_encode($res));
        }

        App::$app->setProperty('filter_content', $leftFilter);

        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords']);
        $this->set(compact('contData'));
    }

}
