<?php

namespace app\models\admin;

use app\models\AppModel;

class Citypage extends AppModel {

    public $attributes = [
        'city_name' => '',
        'city_bname' => '',
        'city_cname' => '',
        'city_dname' => '',
        'city_iname' => '',
        'city_id' => '',
        'title' => '',
        'promo' => '',
        'content' => '',
        'meta_title' => '',
        'meta_keywords' => '',
        'meta_description' => '',
        'status' => 1,
        'content_on' => 0,
        'update_at' => '',
    ];
    public $rules = [
        'required' => [
            ['title'],
            ['city_name'],
            ['city_id'],
        ],
        'integer' => [
            ['status'],
            ['contnt_on'],
            ['city_id'],
        ],
    ];

}
