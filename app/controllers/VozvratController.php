<?php

namespace app\controllers;

use ishop\App;
use app\widgets\menu\Menu;
use app\widgets\filter\Filter;
use ishop\Recaptcha;
use app\models\Vozvrat;

class VozvratController extends AppController {

    public function indexAction() {
        self::uriChek();
        $msg = '';
        $form = true;
        $msgerror = '';
        $succes = false;
        $message = '';

        if (isset($_POST['g-recaptcha-response'])) {
            //  $rekey = \ishop\App::$app->getProperty('reCAPTCHA');
            $response = $_POST['g-recaptcha-response'];
            $re = Recaptcha::instance();
            $reRezult = $re->verifyResponse($response);
            if ($reRezult === true) {
                $phone = $_POST['phone'] ?? '';
                $action = $_POST['status'] ?? 0;
                $action = (int) ($action);
                $phone = (int) phoneFormat($phone);
                if ($phone) {
                    $acquirers = \R::findOne('acquirers', 'phone = ?', [$phone]);
                    if ($acquirers) {
                        if ($action) {
                            $act = 'возврата';
                            $action = 'возврат';
                        } else {
                            $act = 'обмена';
                            $action = 'обмен';
                        }
                        $count = \R::count('vozvrats', 'status = 0 AND acquirers_id = ?', [$acquirers->id]);
                        if (!$count) {
                            $vozvrat = new Vozvrat();
                            $data['phone'] = $phone;
                            $data['action'] = $action;
                            $data['acquirers_id'] = $acquirers->id;
                            $orders = \R::getCol('SELECT `order_num` FROM `orders` WHERE `acquirers_id` = ?', [$acquirers->id]);

                            if ($orders) {
                                $data['orders'] = implode(',', $orders);
                            }
                            $vozvrat->load($data);
                            if (!isset($_SESSION['geo_data'])) {
                                if (App::$app->getProperty('detectadresip_on')) {
                                    $geo = \ishop\Geoip::instance();
                                    $geo_data = $geo->get();
                                } else {
                                    $geo_data = \ishop\Geoip::getIp();
                                }
                                $_SESSION['geo_data'] = $geo_data;
                            }
                            $vozvrat->saveVozvrat();
                            $vozvrat->mailVozvrat($action);
                        }
                        $form = false;
                        $msg = "<h3>Ваша заявка принята.</h3><p><strong>В течении одного рабочего дня, по указанному телефону, с Вами свяжется менеджер для проведения процедуры $act.</strong></p><p>Спасибо, что обратились в наш интернет-магазин.</p>";
                        $succes = true;
                        $message = "Ваша заявка принята. В течении одного рабочего дня, по указанному телефону, с Вами свяжется менеджер для проведения процедуры $act. Спасибо, что обратились в наш интернет-магазин.";
                    } else {
                        $msgerror = 'К сожалению, этот номер не найден в нашей базе заказов, проверьте правильность ввода номера.';
                        $message = $msgerror;
                        $_SESSION['form_data'] = $_POST;
                    }
                } else {
                    $msgerror = 'Проверьте правильность ввода номера.';
                    $message = $msgerror;
                    $_SESSION['form_data'] = $_POST;
                }
            }
            if ($this->json) {
                exit(json_encode([
                    'content' => '',
                    'message' => $message,
                    'succes' => $succes,
                ]));
            }
        }

        $shop_name = App::$app->getProperty('shop_name');
        $shop_tel = App::$app->getProperty('shop_tel');
        $shop_tel_link = App::$app->getProperty('shop_tel_link');
        $contData['meta_title'] = "Интернет-магазин $shop_name - Возврат/обмен";
        $contData['meta_description'] = "Интернет-магазин $shop_name заказать возврат/обмен.";
        $contData['meta_keywords'] = "возврат, обмен";
        $contData['htitle'] = "Заказать возврат/обмен";
        $contData['promo'] = '';


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
        // $this->setJs($filter->js, false, 'Filter Price');
        //        if ($this->isAjax()) {
        //            $js = $filter->js;
        //            $this->loadView('index', compact('filter', 'menuCatalog', 'contData', 'shop_tel', 'shop_tel_link', 'msg', 'form', 'msgerror'), $js);
        //        }

        if ($this->json) {

            $res = [
                'content' => $this->loadViewContent('index', compact('contData', 'shop_tel', 'shop_tel_link', 'msg', 'form', 'msgerror')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),
            ];
            exit(json_encode($res));
        }

        App::$app->setProperty('filter_content', $leftFilter);
        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords']);
        $this->set(compact('contData', 'shop_tel', 'shop_tel_link', 'msg', 'form', 'msgerror'));
    }

}
