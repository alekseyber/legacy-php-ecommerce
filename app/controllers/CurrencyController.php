<?php

namespace app\controllers;

use app\models\Cart;

class CurrencyController extends AppController {

    public function changeAction(){
        self::uriChek();
        $getArr = \ishop\App::$app->getProperty('chek_get');
        $currency = !empty($getArr['curr']) ? $getArr['curr'] : null;
        if($currency){
            $curr = \R::findOne('currency', 'code = ?', [$currency]);
            if(!empty($curr)){
                setcookie('currency', $currency, time() + 3600*24*7, '/');
                Cart::recalc($curr);
            }
        }
        redirect();
    }

}