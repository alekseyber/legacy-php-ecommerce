<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use app\models\admin\Brand;
use ishop\Cache;

class BrandController extends AppController {

    public function indexAction() {
        $this->setMeta('Список Брендов');
        $brands = \R::getAll('SELECT * FROM `brand`');
        $this->set(compact('brands'));
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $brand = \R::load('brand', $id);
        \R::trash($brand);
        $_SESSION['success'] = 'Бренд удален';
        redirect();
    }

    public function addAction() {
        if (!empty($_POST)) {
            $brand = new Brand();
            $data = $_POST;
            $brand->load($data);
            if (!$brand->validate($data)) {
                $brand->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $brand->save('brand')) {
                $_SESSION['success'] = 'Бренд добавлен';
                redirect(ADMIN . '/brand/add');
            }
            redirect();
        }
        $this->setMeta('Новый Бренд');
    }

    public function editAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $brand = new Brand();
            $data = $_POST;
            $brand->load($data);
            if (!$brand->validate($data)) {
                $brand->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }
            if ($brand->update('brand', $id)) {                
                if ($this->isAjax()) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Слайд сохранен'];
                    exit(json_encode($res));
                } else {
                    $_SESSION['success'] = 'Изменения сохранены';
                    redirect();
                }
            }
            if ($this->isAjax()) {
                $res = ['succes' => false, 'title' => 'Изменения не сохранены', 'message' => 'Ошибка БД'];
                exit(json_encode($res));
            } else {
                redirect();
            }
        }
        $id = $this->getRequestID();
        $form = new \app\widgets\formprocessor\Formprocessor([], [], 'Brand', $id, 'brand');
        $this->setMeta("Редактирование слайда {$form->name}");
        $this->set(compact('form'));
    }

}
