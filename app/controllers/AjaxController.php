<?php

namespace app\controllers;

use app\widgets\recomacces\Recomacces;
use ishop\Recaptcha;
use app\models\Askquestion;
use ishop\App;

class AjaxController extends AppController {

    public function indexAction() {

        if ($this->json) {

            $template = $_GET['template'] ?? false;
            if ($template && strlen($template) > 0 && strlen($template) < 30) {
                try {
                    $template = htmlspecialchars($template);

                    $name = "template_$template";

                    $res = [
                        'content' => $this->loadViewContent($name),
                        'message' => '',
                        'succes' => true,
                    ];

                    exit(json_encode($res));
                } catch (\Exception $ex) {
                    throw new \Exception('Шаблон не найден', 404);
                }
            }

            $dann = $_POST;

            if (!isset($dann['id']) || !isset($dann['name'])) {
                exit(json_encode([
                    'content' => '',
                    'message' => 'Не получены обязательные атрибуты',
                    'succes' => false,
                ]));
            }

            $id = (int) $dann['id'];
            $name = preg_replace('#[^a-zA-Z1-9_]{1,30}#', '', $dann['name']);
            $name = mb_strtolower($name);
            //  $modal = false;

            if (isset($dann['block']) && $dann['block']) {
                try {
                    $res = [
                        'content' => $this->loadViewContent($name, compact('dann')),
                        'message' => '',
                        'succes' => true,
                    ];
                    exit(json_encode($res));

                    //  echo $this->loadViewContent($name, compact('dann'));
                } catch (\Exception $ex) {
                    throw new \Exception('Страница не найдена', 404);
                }
            }



            $data = \app\models\Product::getTableRow($name, $id, true, true);
            if (!isset($data['content'])) {
                exit(json_encode([
                    'content' => '',
                    'message' => 'Заданный контент не найден.',
                    'succes' => false,
                ]));
            }

            if (isset($dann['modal']) && $dann['modal']) {

                $res = [
                    'content' => $this->loadViewContent('modalview', compact('data', 'id', 'name')),
                    'message' => '',
                    'succes' => true,
                ];
                exit(json_encode($res));

                //  $this->loadView('modalview', compact('data', 'id', 'name'));
            }
            $res = [
                'content' => $data['content'],
                'message' => '',
                'succes' => true,
            ];
            exit(json_encode($res));
        }
        throw new \Exception('Страница не найдена', 404);
    }

    public function recomaccesAction() {
        if ($this->json) {
            $res = [
                'content' => new Recomacces(),
                'message' => '',
                'succes' => true,
            ];
            exit(json_encode($res));
//            echo new Recomacces;
        }
        throw new \Exception('Страница не найдена', 404);
    }

    public function askquestionAction() {


//        $dann = isset($_POST['dann']) ? $_POST['dann'] : '';
//        $id = 2;
//        $name = "promo";
//        $data['title'] = $dann['title'] ?? 'Обратный звонок';
//        $data['product'] = $dann['product'] ?? '';
//        $messege = 'Благодарим Вас за обращение на нашем сайте. В ближайшее время мы с Вами свяжемся.';
//        if ($data['product']) {
//            $data['product'] = h(json_encode($data['product']));
//            $messege = 'Благодарим Вас за заказ на нашем сайте. В ближайшее время мы с Вами свяжемся.';
//        }

        if ($this->json) {
            $message = 'Благодарим Вас за обращение на нашем сайте. В ближайшее время мы с Вами свяжемся.';            
            $rez = ['succes' => false, 'message' => 'Ошибка связи, повторите отправку позже.'];

            if (isset($_POST['g-recaptcha-response'])) {
                //  $rekey = \ishop\App::$app->getProperty('reCAPTCHA');
                $response = $_POST['g-recaptcha-response'];
                $re = Recaptcha::instance();
                $reRezult = $re->verifyResponse($response);
                if ($reRezult === true) {
                    $askquestion = new Askquestion();
                    $data = $_POST;
                    $askquestion->load($data);

                    if (!$askquestion->validate($data)) {
                        $askquestion->getErrors();
                        $rez = ['succes' => false, 'message' => $_SESSION['error']];
                        //unset($_SESSION['error']);
                    } else {
                        if (!isset($_SESSION['geo_data'])) {
                            if (App::$app->getProperty('detectadresip_on')) {
                                $geo = \ishop\Geoip::instance();
                                $geo_data = $geo->get();
                            } else {
                                $geo_data = \ishop\Geoip::getIp();
                            }
                            $_SESSION['geo_data'] = $geo_data;
                        }
                        $askquestion->hChek();
                        $askquestion->saveAskquestion();
                        $askquestion->mailAskquestion();
                        $rez = ['succes' => true, 'message' => $message];
                    }
                }
            }
            exit(json_encode($rez));
        }
        // $this->loadView('modalaskquestion', compact('data', 'id', 'name', 'dann'));
        throw new \Exception('Страница не найдена', 404);
    }

}
