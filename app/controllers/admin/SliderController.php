<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use app\models\admin\Slider;
use ishop\Cache;

class SliderController extends AppController {

    public function indexAction() {
        $this->setMeta('Список Слайдов');
        $slidervalue = \R::getAll('SELECT * FROM `slidervalue`');
        $this->set(compact('slidervalue'));
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $slidervalue = \R::load('slidervalue', $id);
        \R::trash($slidervalue);
        $_SESSION['success'] = 'Слайд удален';
        $cache = Cache::instance();
        $cache->deleteMask('slider');
        redirect();
    }

    public function addAction() {
        if (!empty($_POST)) {
            $slider = new Slider();
            $data = $_POST;
            $slider->load($data);
            if (!$slider->validate($data)) {
                $slider->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $slider->save('slidervalue')) {
                $_SESSION['success'] = 'Слайд добавлен';
                $cache = Cache::instance();
                $cache->deleteMask('slider');
                redirect(ADMIN . '/slider/edit?id=' . $id);
            }
            redirect();
        }
        $this->setMeta('Новый слайд');
    }

    public function editAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $slider = new Slider();
            $data = $_POST;
            $slider->load($data);
            if (!$slider->validate($data)) {
                $slider->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }
            if ($slider->update('slidervalue', $id)) {
                $cache = Cache::instance();
                $cache->deleteMask('slider');
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
        $form = new \app\widgets\formprocessor\Formprocessor([], [], 'Slider', $id, 'slidervalue');
        $this->setMeta("Редактирование слайда {$form->name}");
        $this->set(compact('form'));
    }

}
