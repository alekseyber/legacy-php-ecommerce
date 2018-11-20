<?php

namespace app\models\admin;

use app\models\AppModel;

class FilterGroup extends AppModel {

    public $attributes = [
        'title' => '',
        'filter' => '',
        'cartproduct' => '',
        'alias' => '',
        'sortvalue' => '',
        'status' => '',
    ];
    public $rules = [
        'required' => [
            ['alias'],
        ],
        'integer' => [
            ['sortvalue'],
        ],
    ];

}
