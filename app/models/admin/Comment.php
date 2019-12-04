<?php

namespace app\models\admin;

use app\models\AppModel;

class Comment extends AppModel {

    public $attributes = [
        'social' => 0,
        'author_name' => '',
        'author_avatar' => '',
        'author_provider' => '',
        'author_uid' => '',
        'commen_text' => '',
        'ip' => 0,
    ];
    public $rules = [
        'required' => [
            ['author_name'],
            ['commen_text'],
        ],
    ];
//     =====================
//     public $default = []; | $default[$attributes_name]['type' => '', 'default_v' ='', 'select' = []]
//     типы: text - input, echo (в теге div текст), hidden, pasword, submit, file, select (требуется параметр с ключем select - массив значений)
//     , textarea, checkbox, radio, tagstart - (параметр default_v - название тега и можно задать class одноименным ключем) - (обязательно закрыть tagend),
//     tagend - (параметр default_v - название тега)
//     может содержать массив data с html data атрибутами (если ключ oldvalue - присваивается значение value),
//     может содержать ключ class со значением названия класса и ключ parentclass с названием родительского класса
//     может содержать ключ parentclass - оборачивает элемент в div с этим классом
//     может содержать ключ messegeerror со значением текста в случае отрицательной валидации
//     может содержать ключ rows для textarea (если не задан то 3)
//     может содержать ключ bgrstatus - для select задает класс обертки в зависимости от значения selecta
//     может содержать ключ disabled - поле заблокировано
//     может содержать ключ name2 - для подгрузки value из поля таблицы с этим значением (отличается имя name)
//     может содержать ключ name3 - значение в ключе name заменится этим значением
//     может содержать ключ checked - переводит checkbox в состояние checked
//     может содержать ключ pattern - добавляет в массив rules пару ключ значение (если в бекэнде не задано или заменяет)
//     может содержать ключ selectdata - selectdata => [tablename, polename] - при вызове $this->loadSelectData добавит ключ select c данными 
//     из таблицы tablename (id, polename)
//     =======================

    public static function changeStatus($status, $id, $name) {
        $class = false;
        $comment = \R::load('comment', $id);
        if (!$comment) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'такого Comment не сещуствует'];
            return $res;
        }
        $comment->$name = $status;
        \R::store($comment);

        $res = ['succes' => true, 'title' => 'Статус Comment изменен', 'message' => "$name Comment $id изменен", 'class' => $class];
        return $res;
    }

}
