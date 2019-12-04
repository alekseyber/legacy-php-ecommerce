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
  может содержать ключ selecteddata - selecteddata => [tablename, polewherename (значение выберет из заданой таблицы where = value заданного в name2), idname - будет ключем массива и значением] -
  при вызове $this->loadSelectData полю selected присвоит значение массива, который подтянется из таблицы tablename значениями ключ:polewherename, значение:poletitlename
  из таблицы tablename (id, polename)
  может содержать ключ label2 - имеет приоритет над $this->label
  ======================= */

namespace app\widgets\formprocessor;

use ishop\App;

class Formprocessor {

    public $title = '';
    protected $form = [
        'form' => true,
        'formclass' => '',
        'formid' => '',
        'parentformclass' => '',
        'enctype' => '',
        'action' => '',
        'method' => '',
        'check' => false,
        'validate' => false,
        'header' => '',
        'message' => '',
        'formname' => '',
        'adminlte' => true, //Bootstrap 3 - true
        'dataattr' => [],
        'img' => false,
    ];
    public $inputs = [];
    protected $arrtype = ['text', 'hidden', 'href', 'html', 'pasword', 'submit', 'file', 'img', 'select', 'selectmultiple', 'textarea', 'checkbox', 'checkboxselect', 'radio', 'range', 'echo', 'tagstart', 'tagend', 'typeahead', 'button'];
    protected $html = '';

    public function __construct($inputs = [], $form = [], $modelclass = false, $id = 0, $table = false) {

        if ($modelclass && (int) $id && $table) {
            $modelclass = 'app\models\admin\\' . $modelclass;
            if (class_exists($modelclass)) {
                $modelclass_obj = new $modelclass();
                if ($modelclass_obj) {
                    if ($id && $table) {
                        $modelclass_obj->loadTableRow($id, $table);
                    }
                    $modelclass_obj->loadSelectData();
                    $inputs = $modelclass_obj->data;
                    if ($modelclass_obj->form) {
                        $form = $modelclass_obj->form;
                    }
                }
            }
        }
        if ($form) {
            foreach ($form as $k => $v) {
                if (isset($this->form[$k])) {
                    $this->form[$k] = $v;
                    if ($k == 'method') {
                        $v = mb_strtolower($v);
                        if ($v != 'post') {
                            $this->form[$k] = '';
                        }
                    }
                    if ($k == 'dataattr') {
                        if (!is_array($v)) {
                            $this->form[$k] = '';
                        }
                    }
                }
            }
        }
        if (isset($this->form['img']) && $this->form['img']) {
            $this->form['formclass'] .= ' fileupload';
            $this->form['method'] = 'post';
            $this->form['enctype'] = 'multipart/form-data';
            if ($this->form['formid']) {
                $this->form['dataattr']['id'] = $this->form['formid'];
            }
        }
        if (empty($this->form['formclass']) && $this->form['validate'] == 'true') {
            $this->form['formclass'] = 'formclass';
        }

        if (!is_array($inputs)) {
            return false;
        }

        foreach ($inputs as $key => $val) {
            if ((isset($val['type'])) && (array_search($val['type'], $this->arrtype) !== FALSE)) {
                $this->inputs[$key] = $val;
            }
        }
        $this->title = $this->inputs['title']['value'] ?? '';
    }

    public function __toString() {
        $this->html = $this->getHtml();
        return $this->html;
    }

    protected function getHtml() {
        $content = '';
        $inputs = $this->inputs;
        if (!$inputs) {
            return $content;
        }
        extract($this->form);
        ob_start();
        require __DIR__ . '/formprocessor_tpl/form.php';
        $content = ob_get_clean();
        return $content;
    }

    public function addInput($inputs = [], $poleprepend = '') {
        if (!$inputs) {
            return false;
        }
        if (!$poleprepend) {
            foreach ($inputs as $key => $val) {
                $this->inputs[$key] = $val;
            }
            return true;
        }
       
        foreach ($this->inputs as $k => $v) {
            $newinputs[$k] = $v;
            if ($k == $poleprepend) {
               
                foreach ($inputs as $key => $val) {
                    $newinputs[$key] = $val;
                }
            }
        }
        $this->inputs = $newinputs;
    }

    public function delInput($key = '', $poleprepend = '') {
        if ($poleprepend) { //удалить все после этого поля
            foreach ($this->inputs as $k => $v) {
                $newinputs[$k] = $v;
                if ($k == $poleprepend) {
                    break;
                }
            }
            $this->inputs = $newinputs;
            return true;
        }

        if (isset($this->inputs[$key])) {
            unset($this->inputs[$key]);
            return true;
        }
        return false;
    }

    public function __get($name = '') {
        $rezult = $this->inputs[$name]['value'] ?? '';
        return $rezult;
    }

    public function __set($name = false, $value = '') {
        if ($name && isset($this->form[$name])) {
            $this->form[$name] = $value;
            return true;
        }
        return false;
    }

}
