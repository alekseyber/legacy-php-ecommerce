<?php

namespace app\controllers\admin;

use app\models\admin\FilterAttr;
use app\models\admin\FilterGroup;
use app\models\admin\Color;

class FilterController extends AppController {

    public function groupDeleteAction() {
        $id = $this->getRequestID();
        $count = \R::count('attribute_value', 'attr_group_id = ?', [$id]);
        $errormsg = 'Удаление не возможно:';
        $errormsg .= $count ? ' (в группе есть атрибуты)' : '';
        $countdelnot = \R::count('attribute_group', 'id = ? AND delnot = "1"', [$id]);
        $errormsg .= $countdelnot ? ' (группа запрещена для удаления)' : '';

        if ($count || $countdelnot) {
            $_SESSION['error'] = $errormsg;
            redirect();
        }
        \R::exec('DELETE FROM attribute_group WHERE id = ?', [$id]);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    public function attributeDeleteAction() {
        $id = $this->getRequestID();

        \R::exec("DELETE FROM attribute_value WHERE id = ?", [$id]);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    public function attributeEditAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $attr = new FilterAttr();
            $data = $_POST;
            $attr->load($data);
            if (!$attr->validate($data)) {
                $attr->getErrors();
                redirect();
            }
            if ($attr->update('attribute_value', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID();
        $attr = \R::load('attribute_value', $id);
        $attrs_group = \R::findAll('attribute_group');
        $this->setMeta('Редактирование атрибута');
        $this->set(compact('attr', 'attrs_group'));
    }

    public function attributeAddAction() {

        if (!empty($_POST)) {

            $attr = new FilterAttr();
            $data = $_POST;
            $attr->load($data);
            if (!$attr->validate($data)) {
                $attr->getErrors();
                redirect();
            }

            $sdvig = \R::getCol('SELECT sdvig FROM attribute_value WHERE attr_group_id = ? ORDER BY sdvig', [$attr->attributes['attr_group_id']]);
            $sortvaluenew = count($sdvig);
            $i = 0;
            $sost = false;

            foreach ($sdvig as $k => $v) {

                if ($k != $v) {
                    $sdvigadd = $k;
                    $sost = true;
                    break;
                }
            }
            if (!$sost)
                $sdvigadd = $sortvaluenew;

            $sortvaluenew += 1;
            $attr->attributes['sdvig'] = $sdvigadd;
            $attr->attributes['sortvalue'] = $attr->attributes['sdvig'] ? $attr->attributes['sdvig'] : $sortvaluenew;


            if ($attr->save('attribute_value', false)) {
                $_SESSION['success'] = 'Атрибут добавлен';
                redirect();
            }
        }
        $group = \R::findAll('attribute_group');
        $this->setMeta('Новый фильтр');
       // $this->set(compact('group', 'sortvaluenew'));
        $this->set(compact('group'));
    }

    public function groupEditAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $group = new FilterGroup();
            $data = $_POST;
            $group->load($data);
            if (!$group->validate($data)) {
                $group->getErrors();
                redirect();
            }
            $count = \R::count('attribute_group', 'alias = ? AND id <> ?', [$data['alias'], $id]);
            if ($count) {
                $_SESSION['error'] = 'Алиас ' . $data['alias'] . ' уже существует в БД';
                redirect();
            }
            if ($group->update('attribute_group', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID();
        $group = \R::load('attribute_group', $id);
        $this->setMeta("Редактирование группы {$group->title}");
        $this->set(compact('group'));
    }

    public function groupAddAction() {
        if (!empty($_POST)) {
            $group = new FilterGroup();
            $data = $_POST;
            $group->load($data);
            if (!$group->validate($data)) {
                $group->getErrors();
                redirect();
            }
            $count = \R::count('attribute_group', 'alias = ?', [$data['alias']]);
            if ($count) {
                $_SESSION['error'] = 'Алиас ' . $data['alias'] . ' уже существует в БД';
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($group->save('attribute_group', false)) {
                $_SESSION['success'] = 'Группа добавлена';
                redirect();
            }
        }
        $this->setMeta('Новая группа фильтров');
    }

    public function attributeGroupAction() {
        $attrs_group = \R::findAll('attribute_group');
        $this->setMeta('Группы фильтров');
        $this->set(compact('attrs_group'));
    }

    public function attributeAction() {
        $id = $_GET['id'] ?? 0;
        $id = (int) $id;
        if ($id) {
            $attrs = \R::getAssoc("SELECT attribute_value.*, attribute_group.title FROM attribute_value JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id WHERE attribute_group.id = ?", [$id]);
        } else {
            $attrs = \R::getAssoc("SELECT attribute_value.*, attribute_group.title FROM attribute_value JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id");
        }
        $this->setMeta('Фильтры');
        $this->set(compact('attrs'));
    }

    public function colorAction() {
        $colors = \R::findAll('color');
        $this->setMeta('Цвета');
        $this->set(compact('colors'));
    }

    public function colorEditAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $color = new Color();
            $data = $_POST;
            $color->load($data);
            if (!$color->validate($data)) {
                $color->getErrors();
                redirect();
            }
            $count = \R::count('color', 'alias = ? AND id <> ?', [$data['alias'], $id]);
            if ($count) {
                $_SESSION['error'] = 'Алиас ' . $data['alias'] . ' уже существует в БД';
                redirect();
            }
            if ($color->update('color', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = $this->getRequestID();
        //$color = \R::load('color', $id);
        $obj = new Color();
        $obj->loadTableRow($id, 'color');
        $obj->loadSelectData();
        $arr2 = $obj->data;

        $title = $arr2['title']['value'];
        $formparams['validate'] = true;
        $formparams['method'] = 'post';
        $formparams['action'] = ADMIN . '/filter/color-edit';
        $form = new \app\widgets\formprocessor\Formprocessor($arr2, $formparams);
        $this->setMeta("Редактирование цвета $title");
        $this->set(compact('form', 'title'));
    }

    public function colorAddAction() {
        if (!empty($_POST)) {
            $color = new Color();
            $data = $_POST;
            $color->load($data);
            if (!$color->validate($data)) {
                $color->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            $count = \R::count('color', 'alias = ?', [$data['alias']]);
            if ($count) {
                $_SESSION['error'] = 'Алиас ' . $data['alias'] . ' уже существует в БД';
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $color->save('color', false)) {
                //$alias = AppModel::createAlias('color', 'alias', $data['title'], $id);
                // $p = \R::load('color', $id);
                //  $p->alias = $alias;
                //   \R::store($p);

                $_SESSION['success'] = 'Цвет добавлен';
                redirect();
            }
        }
        $colorgrups = \R::getAssoc("SELECT `id`,`title` FROM `colorya` ORDER BY `sortvalue`");
        $this->setMeta('Новый цвет');
        $this->set(compact('colorgrups'));
    }

}
