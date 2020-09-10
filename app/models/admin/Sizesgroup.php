<?php

namespace app\models\admin;

use app\models\AppModel;

class Sizesgroup extends AppModel {

    public $attributes = [
        'title' => '',
        'prefix' => '',
        'link_title' => '',
        'content' => '',
    ];
    public $rules = [
        'required' => [
            ['title'],
           // ['prefix'],
            ['link_title'],
        ]
    ];

    public function editSizes($id, $data) {
        $sizes = \R::getCol('SELECT sizes_id FROM sizesgroupselect WHERE sizesgroup_id = ?', [$id]);

        // если изменились optionsgrupp - удалим и запишем новые
        if (!empty($data['sizes'])) {

            $result = array_diff($sizes, $data['sizes']);

            if (!empty($result) || count($sizes) != count($data['sizes'])) {
                $sizes = \R::getAssoc('SELECT sizesgroup_id, sizes_id FROM sizesgroupselect WHERE sizesgroup_id = ?', [$id]);
                \R::exec("DELETE FROM sizesgroupselect WHERE sizesgroup_id = ?", [$id]);
                $sql_part = '';
                foreach ($data['sizes'] as $v) {
                    $v = (int) $v;
                    $sql_part .= "($id, $v),";
                }
                $sql_part = rtrim($sql_part, ',');


                \R::exec("INSERT INTO sizesgroupselect (sizesgroup_id, sizes_id) VALUES $sql_part");
            }
        }
    }

}
