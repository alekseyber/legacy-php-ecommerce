<?php

namespace app\controllers;

use ishop\App;
use ishop\cdek\Controller;
use ishop\cdek\Settings;
use app\widgets\filter\Filter;
use app\widgets\delivery\Delivery;

class DeliveryController extends AppController {

    public function typeaheadAction() {
        if ($this->json) {
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
            if ($query) {
                $rezults = \R::getAll("SELECT ID, CityName, OblName FROM cityrus WHERE CityName LIKE ? LIMIT 11", ["%{$query}%"]);

                exit(json_encode($rezults));
            }
        }
        die;
    }

    public function setpvzAction() {
        if ($this->json) {

            if (isset($_POST['clearpvz'])) {
                if (isset($_SESSION['delivery_pvz'])) {
                    unset($_SESSION['delivery_pvz']);
                    //   $_SESSION['delivery_pvz'] = '';
                    exit(json_encode(['succes' => true, 'message' => 'Успех']));
                }
            }

            $dann = !empty(trim($_POST['dann'])) ? trim($_POST['dann']) : null;
            if ($dann) {
                $dann = json_decode($dann, true);
                if ($dann) {
                    $_SESSION['delivery_pvz'] = serialize($dann);
                    exit(json_encode(['succes' => true, 'message' => 'Успех']));
                }
            }
            exit(json_encode(['succes' => false, 'message' => 'Отказ']));
        }
        die;
    }

    public function indexAction() {
        //$receiverCityId = 561;
        $service = isset($_GET['service']);

        if ($service) {
            header('Access-Control-Allow-Origin: *');

            $cdekAccount = App::$app->getProperty('cdekAccount');
            $cdekKey = App::$app->getProperty('cdekKey');
            $cdekTarifPriority = App::$app->getProperty('cdekTarifPriority');

            Controller::processRequest(
                    Settings::factory(
                            $cdekTarifPriority['courier'], $cdekTarifPriority['pickup'], $cdekAccount, $cdekKey
                            /** Настройте приоритет тарифов курьерской доставки */
                            /** Настройте приоритет тарифов доставки до пунктов выдачи */
                            /** Вставьте свой аккаунт\идентификатор для интеграции */
                            /** Вставьте свой пароль для интеграции */
                    )
            );

            die;
        }

        self::uriChek();
        $getArr = App::$app->getProperty('chek_get');
        $getReceiverCityId = $getArr['receiverCityId'] ?? 0;

        $modal = isset($_GET['modal']);
        $rezult = isset($_GET['rezult']);

        if (!$modal) {
            $modal = isset($_POST['modal']);
        }

        $params = [
            'modal' => $modal,
            'addHeader' => true,
            'typeahead' => true,
            'getReceiverCityId' => $getReceiverCityId,
            'rezult' => $rezult
        ];

        $contentDelivery = new Delivery($params);
        $deliveryDataWIdjet = $contentDelivery->getDataWIdjet();
        $contData['htitle'] = "Доставка и оплата";

        if ($this->json) {

            if ($rezult || $modal) {
                $contentDelivery->setHtml();
            }


            if ($rezult) {
                $res = [
                    'err' => (int) $contentDelivery->err,
                    'geo_city' => $contentDelivery->geo_city,
                    'content' => $contentDelivery->html,
                    'data' => $deliveryDataWIdjet,
                ];

                exit(json_encode($res));
            }

            if ($modal) {
                //  $dann = $_POST['dann'] ?? null;
                $dann = $_POST;
                $id = $dann['id'] ?? 1;
                $name = $dann['name'] ?? 'promo';

                $id = (int) $id;
                $name = preg_replace('#[^a-zA-Z1-9_]{1,30}#', '', $name);
                $content = $this->loadViewContent('modaldivel', compact('contentDelivery', 'id', 'name', 'contData'));

                exit(json_encode([
                    'content' => $content,
                    'succes' => true,
                    'message' => '',
                    'data' => $deliveryDataWIdjet,
                ]));


                //   $this->loadView('modaldivel', compact('contentDelivery', 'id', 'name', 'contData'), $contentDelivery->js);
            }
        }

        $linkCanonical = PATH . '/delivery';
        $shop_name = App::$app->getProperty('shop_name');
        $shop_tel = App::$app->getProperty('shop_tel');
        $shop_tel_link = App::$app->getProperty('shop_tel_link');
        $contData['meta_title'] = "Интернет-магазин $shop_name - Доставка";
        $contData['meta_description'] = "Интернет-магазин $shop_name тел.: $shop_tel доставка с примеркой во все города России.";
        $contData['meta_keywords'] = "доставка, условия доставки";
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
        $product_ids = App::$app->getProperty('product_ids');
        $uri = App::$app->getProperty('filter_default_uri');
        $leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);

        // if ($this->isAjax()) {
        //     $js = $contentDelivery->js;
        //     $this->loadView('index', compact('contentDelivery', 'contData', 'filter', 'menuCatalog'), $js);
        // }

        $contentDelivery->setDataWIdjet();

        if ($this->json) {
            $contentDelivery->setHtml();
            $res = [
                'content' => $this->loadViewContent('index', compact('contentDelivery', 'contData')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
                'data' => $deliveryDataWIdjet,
            ];

            exit(json_encode($res));
        }

        //  $this->setJs($filter->js, false, 'Filter Price');


        if (App::$app->getProperty('last-modified_header_on')) {
            $lastmodified = self::getLastmodified('delivery');
            self::setLastmodified($lastmodified['update_at']);
        }

      //  $this->setJs($contentDelivery->js, false, 'contentDeliveryJs');
        App::$app->setProperty('filter_content', $leftFilter);

        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical);
        $this->set(compact('contentDelivery', 'contData'));
    }

}
