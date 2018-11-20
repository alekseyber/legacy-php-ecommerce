<?php

namespace app\models\admin;

use app\models\AppModel;

class Page extends AppModel {

    public $attributes = [
        'name' => '',
        'title' => '',
        'promo' => '',
        'content' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'status' => '',
        'update_at' => '',
    ];
    public $rules = [
        'required' => [
            ['title'],            
        ],
        'integer' => [
            ['status'],
        ],
    ];

}
