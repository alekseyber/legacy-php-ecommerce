<?php

namespace app\models\admin;

use app\models\AppModel;

class Sizes extends AppModel {

    public $attributes = [
        'title' => '',
        'alias' => '',
        'sortvalue' => 0,
        'tags' => '',
    ];
    public $rules = [
        'required' => [
            ['title'],
            ['alias'],
        ],
        'integer' => [
            ['sortvalue'],
        ],
    ];

}
