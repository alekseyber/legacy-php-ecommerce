<?php

namespace app\controllers\admin;

use app\models\admin\Sizesgroup;
use app\models\admin\Sizes;
use app\models\admin\OptionsGroup;
use app\models\admin\OptionsValue;

class SizesController extends AppController {

    public function indexAction() {
        $sizesgroup = \R::findAll('sizesgroup');
        $this->set(compact('sizesgroup'));
        $this->setMeta('Группы размеров');
    }

    public function gruppEditAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);


            $attr = new Sizesgroup();
            $data = $_POST;


            $attr->load($data);
            if (!$attr->validate($data)) {
                $attr->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }
            if ($attr->update('sizesgroup', $id)) {
                $attr->editSizes($id, $data);

                if ($this->isAjax()) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Группа размеров сохранена'];
                    exit(json_encode($res));
                } else {
                    $_SESSION['success'] = 'Изменения сохранены';
                    redirect();
                }
            }
        }
        $id = $this->getRequestID();
        $sizesgroup = \R::load('sizesgroup', $id);



        $sizes = \R::getCol('SELECT sizes_id FROM sizesgroupselect WHERE sizesgroup_id = ?', [$id]);
        $select_list = \R::getAssoc('SELECT id, title FROM sizes');

        //   debug($select_list,1);

        $inputs['sizes'] = [
            'type' => 'selectmultiple',
            'name' => 'sizes',
            'default_v' => '',
            "select" => $select_list,
            'parentclass' => 'form-group',
            'selected' => $sizes,
            'label' => 'Размеры',
            'id' => 'input-sizes',
        ];

        $selectSizes = new \app\widgets\formprocessor\Formprocessor($inputs);

        $this->setMeta('Редактирование группы размеров');
        $this->set(compact('sizesgroup', 'selectSizes'));
    }

    public function sizeListAction() {
        $id = $_GET['id'] ?? 0;
        $sql = 'SELECT `sizes`.* FROM `sizes`';
        $params = [];
        $str = '';
        $grname = '';
        if ($id) {
            $sql .= ' JOIN `sizesgroupselect` ON `sizes`.`id` = `sizesgroupselect`.`sizes_id`';
            $sql .= ' WHERE `sizesgroupselect`.`sizesgroup_id` = ? ';
            $params = [$id];
            $grname = \R::getCell('SELECT `title` FROM `sizesgroup` WHERE `id` = ?', $params);
            $str = ' ' . $grname;
        }
        $sizelist = \R::getAll($sql, $params);

//        if (!$sizelist) {
//            throw new \Exception('Страница не найдена', 404);
//        }

        $this->setMeta('Размеры' . $str);
        $this->set(compact('sizelist', 'grname'));
    }

    public function sizeAddAction() {

        if (!empty($_POST)) {

            $attr = new Sizes();
            $data = $_POST;

            $attr->load($data);
            if (!$attr->validate($data)) {
                $attr->getErrors();
                redirect();
            }

            if ($attr->save('sizes', false)) {
                $_SESSION['success'] = 'Размер добавлен';
                redirect();
            }
        }

        $this->setMeta('Создать размер');
    }

    public function optionsgruppListAction() {

        $optionslist = \R::getAll('SELECT * FROM `optionsgrupp`');

        // debug($optionslist,1);

        $this->setMeta('Опции товара');
        $this->set(compact('optionslist'));
    }

    public function optionsgruppAddAction() {

        if (!empty($_POST)) {

            $attr = new OptionsGroup();
            $data = $_POST;

            $attr->load($data);
            if (!$attr->validate($data)) {
                $attr->getErrors();
                redirect();
            }

            if ($attr->save('optionsgrupp', false)) {
                $_SESSION['success'] = 'Опция добавлена';
                redirect();
            }
        }


        $this->setMeta('Создать опцию');
    }

    public function optionsgruppDeleteAction() {

        $id = $this->getRequestID();

        \R::exec("DELETE FROM optionsgrupp WHERE id = ?", [$id]);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    public function optionsListAction() {
        $id = $_GET['id'] ?? 0;
        $id = (int) $id;
        //  $id = $this->getRequestID();
        $error = false;
        if ($id) {

            $count = \R::count('optionsgrupp', ' id = ? ', [$id]);
            if ($count) {
                $attrs = \R::getAll("SELECT * FROM `options` WHERE optionsgrupp_id = ?", [$id]);
                $titleGr = \R::getCell("SELECT `title` FROM `optionsgrupp` WHERE id = ?", [$id]);
            } else {
                $error = true;
            }
        }
        if ($error) {
            $_SESSION['error'] = 'Опции с этим ID нет';
            redirect(ADMIN . '/sizes/optionsgrupp-list');
        }

        $this->setMeta('Значения ' . $titleGr);
        $this->set(compact('attrs', 'titleGr', 'id'));
    }

    public function optionsSetdefAction() {


        $group = $_GET['group'] ?? 0;
        $group = (int) $group;

        $id = $_GET['id'] ?? 0;
        $id = (int) $id;
        

        if ($id && $group) {
            $count = \R::count('optionsgrupp', ' id = ? ', [$group]);
            if ($count) {
                $countValue = \R::count('options', ' id = ? ', [$id]);
                if ($countValue) {

                    \R::exec("UPDATE `options` SET `default_v`=0 WHERE `optionsgrupp_id` = ?", [$group]);
                    $option = \R::load('options', $id);
                    $option->default_v = 1;
                    \R::store($option);
                    redirect(ADMIN . '/sizes/options-list?id=' . $group);
                }
            }
        }

        $_SESSION['error'] = 'Опции с этим ID нет';
        redirect(ADMIN . '/sizes/optionsgrupp-list');
    }

    public function optionsAddAction() {

        $error = true;

        $id = $_GET['id'] ?? 0;
        $id = (int) $id;

        //   $id = $this->getRequestID();

        if ($id) {
            $count = \R::count('optionsgrupp', ' id = ? ', [$id]);
            if ($count) {
                $error = false;
                $titleGr = \R::getCell("SELECT `title` FROM `optionsgrupp` WHERE id = ?", [$id]);
            }
        }
        if ($error) {
            $_SESSION['error'] = 'Опции с этим ID нет';
            redirect(ADMIN . '/sizes/optionsgrupp-list');
        }


        if (!empty($_POST)) {

            $attr = new OptionsValue();
            $data = $_POST;
            $data['optionsgrupp_id'] = $id;


            $attr->load($data);
            if (!$attr->validate($data)) {
                $attr->getErrors();
                redirect(ADMIN . '/sizes/options-list?id=' . $id);
            }

            if ($attr->save('options', false)) {
                $_SESSION['success'] = 'Значение добавлено';
                redirect(ADMIN . '/sizes/options-list?id=' . $id);
            }
        }

        $this->setMeta('Добавить значение опции');
        $this->set(compact('titleGr', 'id'));
    }

    public function optionsDeleteAction() {

        $id = $this->getRequestID();

        \R::exec("DELETE FROM options WHERE id = ?", [$id]);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

}
