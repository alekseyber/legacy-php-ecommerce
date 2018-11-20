<?php

namespace app\models\admin;

use app\models\AppModel;

class Lastmodified extends AppModel {

    public $attributes = [
        'expires' => '',
        'expires_index' => '',
        'update_at' => '',        
        'description' => '',
    ];
    public $rules = [
        'integer' => [
            ['expires'],
            ['expires_index'],
        ]
    ];

}
