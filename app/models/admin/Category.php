<?php

namespace app\models\admin;

use app\models\AppModel;

class Category extends AppModel {

    public $attributes = [
        'title' => '',
        'htitle' => '',
        'parent_id' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'promo' => '',
        'content' => '',
        'sortvalue' => '',
        'status' => '',
        'maintop' => '',        
        'update_at' => '',
        'cat_default' => '',
        'img' => '',
        'img_thumb' => '',        
    ];
    public $rules = [
        'required' => [
            ['title'],
            ['parent_id'],
        ],
        'min' => [  
            ['parent_id', 1],
        ],
        'integer' => [
            ['parent_id'],
            ['sortvalue'],
            ['status'],
        ],
    ];

}