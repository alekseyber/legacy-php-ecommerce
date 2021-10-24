<?php

namespace app\models\admin;

use app\models\AppModel;

class Headerstr extends AppModel {

    public $attributes = [
        'header' => '',
        'footer' => '',
        'orderdone' => '',
        
    ];
//    public $rules = [
//        'integer' => [
//            ['expires'],
//            ['expires_index'],
//        ]
//    ];

}
