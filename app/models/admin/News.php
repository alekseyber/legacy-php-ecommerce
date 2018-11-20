<?php

namespace app\models\admin;

use app\models\AppModel;

class News extends AppModel {

    public $attributes = [        
        'title' => '',
        'promo' => '',
        'content' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'status' => '',
        'update_at' => '',
        'img' => '',
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
