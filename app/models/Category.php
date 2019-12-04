<?php

namespace app\models;

use ishop\App;

class Category extends AppModel {

    public $attributes = [
        'title' => '',
        'parent_id' => '',
        'keywords' => '',
        'description' => '',
        'alias' => '',
    ];
    public $rules = [
        'required' => [
            ['title'],
        ]
    ];

    public function getIds($id) {
        $cats = App::$app->getProperty('cats');
        $ids = null;
        foreach ($cats as $k => $v) {
            if ($v['parent_id'] == $id) {
                $ids .= $k . ',';
                $ids .= $this->getIds($k);
            }
        }
        return $ids;
    }

    public static function getPattern($data = [], $get = false) {
        $result['shop_name'] = \ishop\App::$app->getProperty('shop_name');
        $result['shop_tel'] = \ishop\App::$app->getProperty('shop_tel');
        $result['filter'] = '';
        $currency = App::$app->getProperty('currency');

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $key => $val) {
                    if ($k == 'filter') {
                        $result['filter'] .= "$key: $val, ";
                    } else {
                        $result[$k . '_' . $key] = $val;
                    }
                }
            } else {

                $result[$k] = $v;
            }
        }
        $result['filter'] = rtrim($result['filter'], ', ');

        if ($result['filter'] && !$get)
            $result['filter'] = 'Доступны товары со следующими параметрами: ' . $result['filter'] . '.';
        if ($result['filter'] && $get)
            $result['filter'] = 'В текущей категории заданы следующие параметры фильтрации: ' . $result['filter'] . '.';
        if (isset($result['color']) && $result['color'] && !$get)
            $result['color'] = 'Присутствуют следующие варианты цветов: ' . $result['color'] . '.';
        if (isset($result['color']) && $result['color'] && $get)
            $result['color'] = 'Заданы варианты цветов: ' . $result['color'] . '.';
        if (isset($result['sizes'])) {
            if ($result['sizes'] && !$get)
                $result['sizes'] = 'Можно выбрать следующие размеры: ' . $result['sizes'] . '.';
            if ($result['sizes'] && $get)
                $result['sizes'] = 'Заданы варианты размеров: ' . $result['sizes'] . '.';
        }
        $result['count_modif_val'] = $result['count_product'];
        if ($result['count_modif'] > $result['count_product']) {
            $result['count_modif_val'] = $result['count_modif'];
            $result['count_modif'] = ' и ' . $result['count_modif'] . ' их цветовых модификаций';
        } else {
            $result['count_modif'] = '';
        }
        $result['curfr_symbol_right'] = $result['price_pricefr'] > 0 ? $currency['symbol_right'] : '';
        $result['curto_symbol_right'] = $result['price_priceto'] > 0 ? $currency['symbol_right'] : '';
        //$result['curfr_symbol_left'] = $result['price_pricefr'] > 0 ? $currency['symbol_left'] : '';
        //$result['curto_symbol_left'] = $result['price_priceto'] > 0 ? $currency['symbol_left'] : '';
        $result['cur_code'] = $currency['code'];
        $result['cur_symbol_right'] = $currency['symbol_right'];

        $result['price_pricefr'] = $result['price_pricefr'] > 0 ? 'от ' . $result['price_pricefr'] : '';
        $result['price_priceto'] = $result['price_priceto'] > 0 ? 'до ' . $result['price_priceto'] : '';

        $result['.+'] = '';
        return $result;
    }

}
