<?php

namespace app\widgets\delivery;

use ishop\Calculatepricedeliverycdek;
use ishop\App;

class Delivery {

    public $js = '';
    public $html = '';
    public $err = false;
    public $deliveryPriceValue = 0;
    public $deliveryPeriodMin = 1;
    public $deliveryPeriodMax = 2;
    public $geo_city = 'Москва';
    protected $null_city_delivery = [];
    protected $data = [];
    protected $modal = false;
    protected $addHeader = true;
    protected $typeahead = true;
    protected $receiverCityId = 0;
    protected $getReceiverCityId = 0;
    protected $tpl_js_typeahead = __DIR__ . '/delivery_tpl/typeahead_js_tpl.php';
    protected $tpl_js_totlip = __DIR__ . '/delivery_tpl/totlip_js_tpl.php';
    protected $tpl_header = __DIR__ . '/delivery_tpl/header_tpl.php';
    protected $tpl_typeahead = __DIR__ . '/delivery_tpl/typeahead_tpl.php';
    protected $tpl_inf = __DIR__ . '/delivery_tpl/inf_tpl.php';
    protected $inf_html = '';
    protected $addHtml = true;

    public function __construct($options = []) {

        $this->getOptions($options);
        $this->null_city_delivery = App::$app->getProperty('null_city_delivery');

        if (!isset($_SESSION['cart.currency'])) {
            $_SESSION['cart.currency'] = App::$app->getProperty('currency');
        }

        $this->run();
    }

    protected function getOptions($options) {
        $chek = [
            'modal' => true,
            'addHeader' => true,
            'typeahead' => true,
            'getReceiverCityId' => true,
            'receiverCityId' => true,
            'addHtml' => true
        ];

        foreach ($options as $k => $v) {
            if (isset($chek[$k])) {
                $this->$k = $v;
            }
        }

        $rezult = $options['rezult'] ?? 0;
        if ($rezult) {
            $this->typeahead = 0;
            $this->addHeader = 0;
        }
    }

    protected function run() {
        $this->setData();
        if ($this->addHtml) {
            $this->setJs();
            $this->setHtml();
        }
    }

    public function getPriceCurrency() {
        $symbol_left = $_SESSION['cart.currency']['symbol_left'] ?? null;
        $symbol_right = $_SESSION['cart.currency']['symbol_right'] ?? null;

        return $symbol_left . $this->deliveryPriceValue . ' ' . $symbol_right;
    }

    public function getPeriod($max = true) {
        //' ' . getWord($val, ["рабочий", "рабочих", "рабочих"]) .
        $val = $max ? $this->deliveryPeriodMax : $this->deliveryPeriodMin;
        return $val . ' ' . getWord($val, ["день", "дня", "дней"]);
    }

    protected function setData() {

        if ($this->getReceiverCityId > 0) {
            $this->setDefaultCity($this->getReceiverCityId);
        } else if ($this->receiverCityId > 0) {
            $this->setDefaultCity($this->receiverCityId);
        } else if (isset($_SESSION['receiverCityId_default'])) {
            $this->receiverCityId = $_SESSION['receiverCityId_default'];
        }
        $this->geo_city = $_SESSION['city_default'] ?? 'Москва';
        // echo $this->receiverCityId;
        // echo $this->getReceiverCityId;
        // die;
        if ($this->receiverCityId === 0) {

            $cityData = self::getCityData();

            $this->receiverCityId = $cityData['receiverCityId'];
            $this->geo_city = $cityData['geo_city'];
        }

        $this->data = $this->getDivelCalc($this->receiverCityId, $this->geo_city);
    }

    protected function setDefaultCity($candidateId) {
        $sql = 'SELECT `CityName` FROM `cityrus` WHERE `ID` = ? LIMIT 1';
        $geo_city = \R::getCell($sql, [$candidateId]);

        if (!$geo_city) {
            $geo_city = 'Москва';
            $this->receiverCityId = 44;
            $_SESSION['city_default'] = $geo_city;
            $_SESSION['receiverCityId_default'] = $this->receiverCityId;
        } else {
            $this->receiverCityId = $candidateId;
            $_SESSION['city_default'] = $geo_city;
            $_SESSION['receiverCityId_default'] = $this->receiverCityId;
        }
    }

    public static function getCityData() {
        $rezult = [
            'receiverCityId' => 44,
            'geo_city' => 'Москва',
            'receiverCityId_default' => 44,
            'city_default' => 'Москва'
        ];

        if (isset($_SESSION['geo_city'])) {
            $rezult['geo_city'] = $_SESSION['geo_city'];
        }

        if (isset($_SESSION['receiverCityId_default']) && $_SESSION['city_default']) {
            $rezult['receiverCityId_default'] = $_SESSION['receiverCityId_default'];
            $rezult['city_default'] = $_SESSION['city_default'];
            $rezult['receiverCityId'] = $_SESSION['receiverCityId_default'];
            $rezult['city_default'] = $_SESSION['city_default'];

            return $rezult;
        }

        if (!isset($_SESSION['geo_data'])) {
            if (App::$app->getProperty('detectadresip_on')) {
                $geo = \ishop\Geoip::instance();
                $geo_data = $geo->get();
            } else {
                $geo_data = \ishop\Geoip::getIp();
            }
            $_SESSION['geo_data'] = $geo_data;
        }


        if (isset($_SESSION['geo_data']['geo_city']) && !empty($_SESSION['geo_data']['geo_city'])) {

            $data = 0;
            $geo_city = $_SESSION['geo_data']['geo_city'];
            $geo_reg = $_SESSION['geo_data']['geo_reg'];
            $geo_reg = preg_replace('/область/i', '', $geo_reg);
            $geo_reg = preg_replace('/республика/i', '', $geo_reg);
            $geo_reg = preg_replace('/край/i', '', $geo_reg);
            $geo_reg = trim($geo_reg);

            $sql_city = '%' . $geo_city . '%';
            $sql_reg = '%' . $geo_reg . '%';
            $arr = [$sql_city, $sql_reg];

            $sql = 'SELECT `ID`, `CityName` FROM `cityrus` WHERE `CityName` LIKE ? AND `OblName` LIKE ? LIMIT 1';

            $data = \R::getRow($sql, $arr);
            if ($data) {
                $_SESSION['receiverCityId'] = $data['ID'];
                $_SESSION['geo_city'] = $geo_city;
                $_SESSION['city_default'] = $data['CityName'];
                $_SESSION['receiverCityId_default'] = $data['ID'];

                $rezult['geo_city'] = $geo_city;
                $rezult['city_default'] = $data['CityName'];
                $rezult['receiverCityId_default'] = $data['ID'];
                $rezult['receiverCityId'] = $data['ID'];
            } else {
                $_SESSION['receiverCityId'] = 44;
                $_SESSION['geo_city'] = 'Москва';
                $_SESSION['city_default'] = 'Москва';
                $_SESSION['receiverCityId_default'] = 44;
            }
        } else {
            $_SESSION['receiverCityId'] = 44;
            $_SESSION['geo_city'] = 'Москва';
            $_SESSION['city_default'] = 'Москва';
            $_SESSION['receiverCityId_default'] = 44;
        }

        return $rezult;
    }

    protected function upadateDateTime($res) {
        $dayStart = floor((time() - mktime(0, 0, 0)) / 3600);
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Moscow'));

        if ($res['receiverCityId'] == 44) { //МСк
            $res['null_price'] = 1;
            if ($dayStart < ($res['divel_hour_podtv'] - 1)) {
                $res['deliveryDateMin'] = $date->format('d.m.Y');
                $date->add(new \DateInterval('P1D'));
                $res['deliveryDateMax'] = $date->format('d.m.Y');
            } else {
                $date->add(new \DateInterval('P1D'));
                $res['deliveryDateMin'] = $date->format('d.m.Y');
                $res['deliveryDateMax'] = $date->format('d.m.Y');
            }
            $res['price'] = 0;
        } else { //Другие города
            if (isset($res['deliveryDateMin']) && isset($res['deliveryDateMax'])) {
                $res['deliveryDateMin'] = datexpl($res['deliveryDateMin']);
                $res['deliveryDateMax'] = datexpl($res['deliveryDateMax']);
                $res['null_price'] = 0;
                if (isset($this->null_city_delivery[$res['receiverCityId']])) {
                    $res['price'] = 0;
                    $res['null_price'] = 1;
                } else {
                    $res['price'] = $res['price'] + $res['divel_add'];
                }
            }
        }

        $end_time = time() + (5 * 60 * 60);

        $res['end_time'] = $end_time;

        return $res;
    }

    protected function setResSettings($res) {
        $this->geo_city = $res['geo_city'];
        $this->deliveryPriceValue = $res['price'] ?? 0;
        $this->deliveryPeriodMin = $res['deliveryPeriodMin'] ?? 1;
        $this->deliveryPeriodMax = $res['deliveryPeriodMax'] ?? 0;
        $this->receiverCityId = $res['receiverCityId'];

        return $res;
    }

    protected function getDivelCalc($receiverCityId, $geo_city) {


        if (isset($_SESSION['delivery']) && isset($_SESSION['calc_delivery_CityId']) && $receiverCityId != 44) {

            if ($_SESSION['calc_delivery_CityId'] === $receiverCityId) {
                $res = unserialize($_SESSION['delivery']);
                $end_time = $res['end_time'] ?? 0;

                if ($end_time && $end_time > time()) {
                    $res = $this->setResSettings($res);
                    return $res;
                }
            }
        }



        $res = [];
        $allert = '';
        $chek = true;
        $err = false;
        $res['null_price'] = 0;


        if ($receiverCityId == 44) {
            $chek = false;
            $res['null_price'] = 1;
            $res['tariffId'] = 0;
            $res['price'] = 0;
            $res['deliveryPeriodMin'] = 0;
            $res['deliveryPeriodMax'] = 0;
        }



        if ($receiverCityId > 0 && $chek) {

            try {
                $calc = Calculatepricedeliverycdek::instance();
                //устанавливаем город-получатель
                $calc->setReceiverCityId($receiverCityId);
                //устанавливаем дату планируемой отправки
                $ttt = time() + 86400;
                $calc->setDateExecute(date('Y-m-d', $ttt));
                //устанавливаем тариф по-умолчанию
                $calc->setTariffId(233);
                if ($calc->calculate() === false) {
                    $calc->setTariffId(137);
                }
                if ($calc->calculate() === true) {
                    $res = $calc->getResult();
                    if (isset($res['result'])) {
                        if (array_key_exists('cashOnDelivery', $res['result'])) {
                            $allert = 'В данном населенном пункте действует ограничение оплаты наложенного платежа, от: ' . $res['result']['cashOnDelivery'] . ' ₽.';
                        }
                        $res = $res['result'];
                        // $_SESSION['city_default'] = $geo_city;
                        // $_SESSION['receiverCityId_default'] = $receiverCityId;
                    }
                } else {
                    $err = true;
                }
            } catch (\Exception $e) {
                $err = true;
                $this->err = true;
                // echo 'Ошибка: ' . $e->getMessage() . "<br />";
            }
            if ($err) {
                $receiverCityId = 44;
                $geo_city = 'Москва';
                $chek = false;
            }
        }

        $res['receiverCityId'] = $receiverCityId;
        $res['geo_city'] = $geo_city;
        $res['chek'] = $chek;
        $res['err'] = $err;
        $res['allert'] = $allert;
        $res['divel_hour_podtv'] = App::$app->getProperty('divel_hour_podtv');
        $res['divel_add'] = App::$app->getProperty('divel_add');
        $res = $this->upadateDateTime($res);
        $_SESSION['delivery'] = serialize($res);
        $_SESSION['calc_delivery_CityId'] = $res['receiverCityId'];
        $_SESSION['city_default'] = $res['geo_city'];
        $_SESSION['receiverCityId_default'] = $res['receiverCityId'];

        $res = $this->setResSettings($res);

        return $res;
    }

    protected function setJs() {
        $jscontent = '';
        $modal = $this->modal;
        if ($this->typeahead) {

            ob_start();
            require $this->tpl_js_typeahead;
            $jscontent = ob_get_clean();
        }
        if ($modal) {
            ob_start();
            require $this->tpl_js_totlip;
            $jscontent .= ob_get_clean();
        }

        $this->js = $jscontent;
    }

    protected function setHtml() {
        $content = '';
        if ($this->addHeader) {

            ob_start();
            require $this->tpl_header;
            $content = ob_get_clean();
        }

        $res = $this->data;
        $modal = $this->modal;
        //        $divel_hour_podtv = App::$app->getProperty('divel_hour_podtv');
        //        $divel_add = App::$app->getProperty('divel_add');

        ob_start();
        require $this->tpl_inf;
        $this->inf_html = ob_get_clean();

        if ($this->typeahead) {
            $idcity = $this->modal ? 'typeahead-city-modal' : 'typeahead-city';
            $iddiv = $this->modal ? 'delivery-modal-content' : 'delivery-no-modal-content';
            $inf_html = $this->inf_html;

            ob_start();
            require $this->tpl_typeahead;
            $content .= PHP_EOL . ob_get_clean();
        } else {
            $content .= PHP_EOL . $this->inf_html;
        }

        $this->html = $content;
    }

    public function __toString() {
        return $this->html;
    }

}
