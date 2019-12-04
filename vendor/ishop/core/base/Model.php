<?php

/*     =====================
  может получить данные из предварительно созданных объектов классов моделей на входе первый массив inputs и $formparams, inputs берется из параметра класса $default
  либо вручную загрузить инпуты пример из класса:

  $obj = new Product();
  $obj->loadTableRow(32218936, 'product');
  $obj->loadSelectData();
  $arr2 = $obj->data;
  $formparams['action'] = ADMIN.'/ajax/form';
  $formparams['method'] = 'post';
  $form = new \app\widgets\formprocessor\Formprocessor($arr2, $formparams);

  -------
  может получить данный из класса и сам выгрузить пример ($formparams - может быть получено из класса либо подано в переменной $formparams - более высоки приоритет):
  $formparams['action'] = ADMIN.'/ajax/form';
  $formparams['method'] = 'post';
  $form = new \app\widgets\formprocessor\Formprocessor([], $formparams, 'Product', 32218936, 'product');
  -------
  может принять класс модели, название таблицы и id строки и сам создать класс и выгрузить данные
  public $default = []; | $default[$attributes_name]['type' => '', 'default_v' ='', 'select' = []]
  типы: text - input, echo (в теге div текст из value), hidden, pasword, submit, file, select, selectmultiple-(select2)
  (требуется параметр с ключем select - массив значений), button
  может содержать ключ selectdata - selectdata => [tablename, polename] - при вызове $this->loadSelectData добавит ключ select c данными из таблицы
  может содержать ключ selectzero - добавит ключ 0 со значением не выбрано к массиву select
  , textarea, checkbox (при добавлении ключа select ,будет selectmultiple из checkbox), radio (при добавлении ключа select ,будет select из radio),
  tagstart - (параметр default_v - название тега и можно задать class одноименным ключем) - (обязательно закрыть tagend),
  тип checkbox может содержать 2 ключа: swith и selectedon со значением (сравнивается с value) при котором считать его включенным
  пример: 'status' => ['type' => 'checkbox', 'default_v' => '', 'parentclass' => 'form-group', 'swith' => '', 'selectedon' => '1'],
  tagend - (параметр default_v - название тега), range,
  html - выводит содержимое ключа default_v,
  img - с поддержкой dropZone (атрибут $form['img'] = присвоить - true),
  typeahead (ключ changid при наличии заменит value на id), (ключ remote - содержит строку |tablename,поле в котором искать title,ключ - по умолчанию id,| и selectdata см ниже - не обязательно)
  пример: $default [
  'category_id' => ['type' => 'typeahead', 'default_v' => 'Ничните ввод', 'id' => 'category_id',  'remote' => 'category,title', 'parentclass' => 'form-group', 'changid' => '', 'selectdata' => ['category', 'title']],

  href - ссылка 'label2' - текст ссылки , (default_v, name2 - не бязательно (подгрузка см ниже)) - ссылка,
  пример 'linkhref' => ['type' => 'href', 'label2' => 'Редактировать', 'default_v' => '/admin/product/edit?id=', 'name2' => 'id', 'parentclass' => 'form-group'],
  может содержать массив data с html data атрибутами (если ключ oldvalue - присваивается значение value),
  может содержать ключ class со значением названия класса и ключ parentclass с названием родительского класса
  может содержать ключ id со значением id элемента
  может содержать ключ parentclass - оборачивает элемент в div с этим классом
  может содержать ключ messegeerror со значением текста в случае отрицательной валидации только bootstrap4
  может содержать ключ rows для textarea (если не задан то 3)
  может содержать ключ bgrstatus - для select задает класс обертки в зависимости от значения selecta
  может содержать ключ disabled - поле заблокировано
  может содержать ключ name2 - для подгрузки value из поля таблицы с этим значением (отличается имя name)
  может содержать ключ name3 - значение в ключе name заменится этим значением
  может содержать ключ checked - переводит checkbox значение - true в состояние checked, пусто - снимает checked
  может содержать ключ pattern - добавляет в массив rules пару ключ значение (если в бекэнде не задано или заменяет)
  может содержать ключ selectdata - selectdata => [tablename, polename] - при вызове $this->loadSelectData добавит ключ select c данными
  может содержать ключ selecteddata - selecteddata => [tablename, polewherename (значение подтянет из value поля  исходной таблицы заданного в name2), idname - будет ключем массива и значением] -
  при вызове $this->loadSelectData полю selected присвоит значение массива, который подтянется из таблицы tablename значениями ключ:polewherename, значение:poletitlename
  из таблицы tablename (id, polename)
  может содержать ключ label2 - имеет приоритет над $this->label
  ======================= */

namespace ishop\base;

use ishop\Db;
use Valitron\Validator;

abstract class Model {

    public $attributes = [];
    public $errors = [];
    public $rules = [];
    private $properties = [];
    public $default = []; // $default[$attributes_name]['type' => '', 'default_v' ='', 'select' = []]
    public $table = '';
    public $form = [];

    public function __construct() {
        Db::instance();
    }

    public function load($data) {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    public function save($table, $valid = true) {
        return $this->saveData($table, $this->attributes, $valid);
    }

    public function saveData($table, $data, $valid = true) {
        if ($valid) {
            $tbl = \R::dispense($table);
        } else {
            $tbl = \R::xdispense($table);
        }
        foreach ($data as $name => $value) {
            $tbl->$name = $value;
        }
        return \R::store($tbl);
    }

    public function update($table, $id) {
        return $this->updateData($table, $this->attributes, $id);
    }

    public function updateData($table, $data, $id) {
        $bean = \R::load($table, $id);

        foreach ($data as $name => $value) {
            $bean->$name = $value;
        }
        return \R::store($bean);
    }

    public function setTimestamp($table, $id) {
        $data['update_at'] = date('Y-m-d H:i:s');
        return $this->updateData($table, $data, $id);
        //2018-09-07 23:09:41
    }

    public function validate($data) {
        Validator::langDir(WWW . '/validator/lang');
        Validator::lang('ru');
        Validator::addRule('phone', function($field, $value) {
            return (bool) preg_match('#^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$#', $value);
        }, 'номер введен не верно');

        $v = new Validator($data);
        $v->rules($this->rules);

        if (isset($this->label))
            $v->labels($this->label);

        if ($v->validate()) {
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }

    public function getErrors() {
        $errors = '<ul>';
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $errors .= "<li>$item</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors;
    }

    final protected function add($name, $default = null, $is_array = false) {
        $this->properties[$name]["is_array"] = $is_array;
        if ($is_array && $default == null)
            $this->properties[$name]["value"] = array();
        else
            $this->properties[$name]["value"] = $default;
    }

    final public function __get($name) {
        if (array_key_exists($name, $this->properties))
            return $this->properties[$name]["value"];
        return null;
    }

    final public function __set($name, $value) {
        if (array_key_exists($name, $this->properties)) {
            if (is_array($this->properties[$name]["value"])) {
                if (is_array($value))
                    $this->properties[$name]["value"] = $value;
                else
                    $this->properties[$name]["value"][] = $value;
            } else
                $this->properties[$name]["value"] = $value;
        } else
            return false;
    }

    final protected function getProperties() {
        $ret = array();
        foreach ($this->properties as $name => $value) {
            $ret[$name] = $value["value"];
        }
        return $ret;
    }

    final protected function addObj($name, $field, $obj) {
        if (array_key_exists($name, $this->properties))
            $this->properties[$name]["value"][$field] = $obj;
    }

    public function loadForm() {
        $this->properties['data'] = [];
        if (empty($this->default)) {
            return $this->properties['data'];
        }
        foreach ($this->default as $k => $v) {
            $this->properties['data']['value'][$k] = $v;
            $this->properties['data']['value'][$k]['value'] = $this->attributes[$k] ?? '';
            $this->properties['data']['value'][$k]['name'] = $k;
            if (!isset($this->properties['data']['value'][$k]['default_v'])) {
                $this->properties['data']['value'][$k]['default_v'] = '';
            }
            if (isset($this->properties['data']['value'][$k]['name3'])) {
                $this->properties['data']['value'][$k]['name'] = $this->properties['data']['value'][$k]['name3'];
            }
            if (isset($this->properties['data']['value'][$k]['value2'])) {
                $this->properties['data']['value'][$k]['value'] = $this->properties['data']['value'][$k]['value2'];
            }
            if (isset($this->label[$k])) {
                $this->properties['data']['value'][$k]['label'] = $this->label[$k];
            } else {
                $this->properties['data']['value'][$k]['label'] = $k;
            }
            $this->properties['data'][$k]['value']['rules'] = [];
            foreach ($this->rules as $key => $val) {
                if (array_search($k, array_column($val, 0)) !== FALSE) {
                    $this->properties['data']['value'][$k]['rules'][$key] = $val[array_search($k, array_column($val, 0))][1] ?? '';
                }
            }
            if (isset($this->properties['data']['value'][$k]['pattern'])) {
                $this->properties['data']['value'][$k]['rules']['pattern'] = $this->properties['data']['value'][$k]['pattern'];
            }
            if (isset($this->properties['data']['value'][$k]['label2'])) {
                $this->properties['data']['value'][$k]['label'] = $this->properties['data']['value'][$k]['label2'];
            }
        }
        return $this->properties['data']['value'];
    }

    public function loadTableRow($id = 0, $table = '') {

        if (!$id || !$table) {
            return false;
        }
        if (empty($this->default)) {
            return false;
        }
        $data = \R::getRow("SELECT * FROM $table WHERE `id` = ? LIMIT 1", [(int) $id]);

        if (!$data) {
            return false;
        }
        $this->loadForm();

        foreach ($this->properties['data']['value'] as $k => &$v) {
            if (isset($data[$k])) {
                $v['value'] = $data[$k];
            }
            if ((isset($v['name2'])) && (isset($data[$v['name2']]))) {
                $v['value'] = $data[$v['name2']];
            }
            if ((isset($v['value2']))) {
                $v['value'] = $v['value2'];
            }
        }
    }

    public function loadSelectData() {
        if (empty($this->default) && empty($this->properties['data']['value'])) {
            return false;
        }
        foreach ($this->properties['data']['value'] as $k => &$v) {
            if (isset($v['selectdata'])) {
                if (is_array($v['selectdata'])) {
                    $table = $v['selectdata'][0] ?? '';
                    $pole = $v['selectdata'][1] ?? '';
                    if ($table && $pole) {
                        $data = \R::getAssoc("SELECT id, $pole FROM $table");
                        if ($data) {
                            $v['select'] = $data;
                        }
                        if (isset($v['selectzero'])) {
                            $v['select'][0] = 'Не выбрано';
                        }                        
                    }
                }
            }
            if (isset($v['selecteddata']) && isset($v['name2'])) {
                if (is_array($v['selecteddata'])) {
                    $table = $v['selecteddata'][0] ?? '';
                    $idname = $v['selecteddata'][1] ?? '';
                    $polewherename = $v['selecteddata'][2] ?? '';
                    if ($table && $idname && $polewherename) {
                        $data = \R::getAssoc("SELECT $idname FROM $table WHERE $polewherename = '{$v['value']}'");
                        if ($data) {
                            $v['selected'] = $data;
                        }
                    }
                }
            }
        }
    }

}