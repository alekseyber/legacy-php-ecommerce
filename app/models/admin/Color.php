<?php

namespace app\models\admin;

use app\models\AppModel;

class Color extends AppModel {

    public $attributes = [
        'title' => '',
        'rustitle' => '',
        'sortvalue' => '',
        'search_str' => '',
        'alias' => '',
        'tablegr_id' => '',
        'colorkey' => '',        
    ];
    public $rules = [
        'required' => [
            ['title'],
            ['rustitle'],
            ['alias'],
            ['tablegr_id'],
            ['colorkey'],
        ],
        'integer' => [
            ['sortvalue'],
            ['tablegr_id'],
        ]
    ];
    public $label = [
        'title' => 'Имя Лат',
        'rustitle' => 'Имя Рус',
        'sortvalue' => 'Порядок сортировки',
        'search_str' => 'Для поиска',
        'alias' => 'Alias',
        'tablegr_id' => 'Группа',
        'colorkey' => 'Код цвета',
    ];
    public $default = [
        'title' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'rustitle' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'sortvalue' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'search_str' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'alias' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'colorkey' => ['type' => 'text', 'default_v' => '', 'parentclass' => 'form-group'],
        'tablegr_id' => ['type' => 'radio', 'default_v' => '', 'selectdata' => ['colorya', 'title'], 'parentclass' => 'form-group'],
        'id' => ['type' => 'hidden'],        
    ];

}

//     =====================
//     public $default = []; | $default[$attributes_name]['type' => '', 'default_v' ='', 'select' = []]
//     типы: text - input, echo (в теге div текст), hidden, pasword, submit, file, select, selectmultiple-(select2) (требуется параметр с ключем select - массив значений)
//     , textarea, checkbox, radio, tagstart - (параметр default_v - название тега и можно задать class одноименным ключем) - (обязательно закрыть tagend),
//     tagend - (параметр default_v - название тега), range, html - выводит содержимое ключа default_v,
//     img - с поддержкой dropZone (атрибут $form['img'] = присвоить - true)
//     может содержать массив data с html data атрибутами (если ключ oldvalue - присваивается значение value),
//     может содержать ключ class со значением названия класса и ключ parentclass с названием родительского класса
//     может содержать ключ id со значением id элемента
//     может содержать ключ parentclass - оборачивает элемент в div с этим классом
//     может содержать ключ messegeerror со значением текста в случае отрицательной валидации только bootstrap4
//     может содержать ключ rows для textarea (если не задан то 3)
//     может содержать ключ bgrstatus - для select задает класс обертки в зависимости от значения selecta
//     может содержать ключ disabled - поле заблокировано
//     может содержать ключ name2 - для подгрузки value из поля таблицы с этим значением (отличается имя name)
//     может содержать ключ name3 - значение в ключе name заменится этим значением
//     может содержать ключ checked - переводит checkbox значение - true в состояние checked, пусто - снимает checked
//     может содержать ключ pattern - добавляет в массив rules пару ключ значение (если в бекэнде не задано или заменяет)
//     может содержать ключ selectdata - selectdata => [tablename, polename] - при вызове $this->loadSelectData добавит ключ select c данными 
//     из таблицы tablename (id, polename)
//     может содержать ключ label2 - имеет приоритет над $this->label
//     =======================