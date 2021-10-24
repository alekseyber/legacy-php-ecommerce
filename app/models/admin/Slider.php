<?php

namespace app\models\admin;

use app\models\AppModel;

class Slider extends AppModel {

    public $attributes = [
        'slider_id' => 1,
        'name' => '',
        'img' => '',
        'href' => '',
        'content' => '',
        'status' => 0,
        'sortvalue' => 0,
    ];
    public $rules = [
        'required' => [
            ['slider_id'],
            ['img'],
            ['content'],
        ],
        'integer' => [
            ['slider_id'],
            ['sortvalue'],
        ]
    ];
    public $label = [
        'slider_id' => 'slider_id',
        'name' => 'Имя',
        'img' => 'Фоновое изображение',
        'href' => 'Ссылка',
        'content' => 'Контент',
        'status' => 'Вкл/Выкл',
        'sortvalue' => 'Порядок сортировки',
    ];
    public $form = [
        'action' => ADMIN . '/slider/edit',
        'method' => 'post',
        'formclass' => 'post',
        'formclass' => 'js-form-edit-product',
        'dataattr' => ['toggle' => 'validator', 'posthangler' => 'false'],
    ];
    public $default = [
        'name' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'img' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'href' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'content' => ['type' => 'textarea', 'default_v' => '', 'parentclass' => 'form-group', 'rows' => '5'],
        'status' => ['type' => 'checkbox', 'default_v' => '', 'parentclass' => 'form-group', 'swith' => '', 'selectedon' => '1'],
        'sortvalue' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'id' => ['type' => 'hidden'],
        'slider_id' => ['type' => 'hidden'],
    ];

}
