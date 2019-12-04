<?php

namespace app\models\admin;

use app\models\AppModel;

class Brand extends AppModel {

    public $attributes = [
        'title' => '',
        'alias' => '',
        'img' => '',
        'description' => '',  
        'content' => '',              
    ];
    public $rules = [
        'required' => [
            ['title'],
            ['alias'],            
        ]
    ];
    public $label = [
        'title' => 'Title',
        'alias' => 'Аlias',
        'img' => 'Изображение',
        'content' => 'Контент',        
        'description' => 'описание',
    ];
    public $form = [
        'action' => ADMIN . '/brand/edit',
        'method' => 'post',
        'formclass' => 'post',
        'formclass' => 'js-form-edit-product',
        'dataattr' => ['toggle' => 'validator', 'posthangler' => 'false'],
    ];
    public $default = [
        'title' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'img' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'alias' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'description' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'content' => ['type' => 'textarea', 'default_v' => '', 'parentclass' => 'form-group', 'rows' => '3'],        
        'id' => ['type' => 'hidden'],        
    ];

}