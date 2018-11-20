<?php

namespace app\models\admin;

use app\models\AppModel;

class Utm extends AppModel {

    public $attributes = [
        'key_item' => '',
        'title' => '',
        'promo' => '',
        'status' => '',
        'all_on' => '',
        'description' => '',
    ];
    public $rules = [
        'required' => [
            ['key_item'],
        ],
        'integer' => [
            ['status'],
            ['all_on'],
        ],
    ];

    public static function changeStatus($status, $id, $name) {
        $class = false;
        $utm = \R::load('utm', $id);
        if (!$utm) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'такого UTM не сещуствует'];
            return $res;
        }
        $utm->$name = $status;
        \R::store($utm);

        $res = ['succes' => true, 'title' => 'Статус UTM изменен', 'message' => "$name UTM $id изменен", 'class' => $class];
        return $res;
    }

}
