<?php

namespace app\models\admin;

use app\models\AppModel;

class Csv extends AppModel {

    public $utm;

    public function updateTable($data, $table, $key_name) {
        $fields = \R::inspect($table);
        foreach ($data as $item) {
            $dat = \R::findOne($table, "$key_name = ?", [$item[$key_name]]);
            if ($dat) {
                foreach ($dat as $k => $v) {
                    if ($k == 'id')
                        continue;
                    if ($table == 'product' && ($k == 'seolink' || $k == 'alias'))
                        continue;
                    if ($k != $key_name && array_key_exists($k, $item)) {
                        $dat->$k = $item[$k];
                    }
                }
                \R::store($dat);
            } else {
                if ($table == 'product')
                    continue;
                $bean_new = \R::dispense($table);
                foreach ($fields as $field => $type) {
                    if ($field == 'id')
                        continue;
                    if (array_key_exists($field, $item)) {
                        $bean_new->$field = $item[$field];
                    }
                }

                \R::store($bean_new);
            }
        }
    }

}
