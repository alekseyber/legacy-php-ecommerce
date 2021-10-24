<?php

namespace app\models\admin;

use app\models\AppModel;

class Cupons extends AppModel {

    public $attributes = [
        'code' => '',
        'value' => 1,
        'description' => '',
        'status' => 1,
        // 'date_start' => '',
        'date_end' => '',
    ];
    public $rules = [
        'required' => [
            ['code'],
            ['value'],
            ['date_end'],
        ],
        'integer' => [
            ['status'],
        ],
        'numeric' => [
            ['value'],
        ],
        'max' => [
            ['value', 1],
        ],
        'date' => [
            ['date_end'],
        ]
    ];

    public static function normalizeValue($value, $name) {

        $rez = [
            'valid' => false,
            'value' => ''
        ];

        if (!$value) {
            return $rez;
        }
        switch ($name) {
            case 'value':
                $value = (int) $value;
                if ($value <= 100) {
                    $rez['value'] = 1 - $value / 100;
                    $rez['valid'] = true;
                }
                break;
            case 'date_end':
                $pattern = '/^(\d{2}\.\d{2}\.\d{4})$/';
                if (preg_match($pattern, $value)) {
                    $rez['value'] = datexplrus($value);
                    $rez['valid'] = true;
                }

                break;
        }
        return $rez;
    }

    public static function changeData($value, $id, $name) {

        $valid = false;

        if ($value) {
            $resValue = self::normalizeValue($value, $name);
            $valid = $resValue['valid'];
            $value = $resValue['value'];
        } else {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => "значение не передано"];
            return $res;
        }

        if (!$valid) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => "ошибка валидации $value | $name | $id"];
            return $res;
        }
        $cupons = \R::load('cupons', $id);
        if (!$cupons) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'такого купна не сущуствует'];
            return $res;
        }
        $cupons->$name = $value;
        \R::store($cupons);

        $res = ['succes' => true, 'title' => 'Данные изменены', 'message' => "данные изменены успешно"];
        return $res;
    }

}
