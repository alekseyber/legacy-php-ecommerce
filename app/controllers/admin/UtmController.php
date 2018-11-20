<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;
use app\models\admin\Utm;

class UtmController extends AppController {

    public function indexAction() {
        $this->setMeta('Список UTM');
        $id = $_GET['id'] ?? 0;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 50;
        if ($id) {
            $count = 1;
            $utms = \R::find('utm', 'id = ?', [$id]);
        } else {
            $count = \R::count('utm');
            $utms = \R::find('utm');
        }
        $pagination = new Pagination($page, $perpage, $count, true);
        $start = $pagination->getStart();

        $this->set(compact('utms', 'pagination', 'count'));
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $utm = \R::load('utm', $id);
        \R::trash($utm);
        $_SESSION['success'] = "UTM $id удалена";
        redirect();
    }

    public function addAction() {
        if (!empty($_POST)) {
            $utm = new Utm();
            $data = $_POST;
            $utm->load($data);
            if (!$utm->validate($data)) {
                $utm->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            $count = \R::count('utm', 'key_item = ?', [$data['key_item']]);
            if ($count) {
                $_SESSION['error'] = 'Код UTM ' . $data['key_item'] . ' уже существует в БД';
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $utm->save('utm')) {
                $_SESSION['success'] = 'UTM добавлена';
                redirect(ADMIN . '/utm/edit?id=' . $id);
            }
            redirect();
        }
        $this->setMeta('Новая UTM');
    }
    public function copyutmAction() {
        if (!empty($_POST)) {
            $utm = new Utm();
            $data = $_POST;
            $utm->load($data);
            if (!$utm->validate($data)) {
                $utm->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            $count = \R::count('utm', 'key_item = ?', [$data['key_item']]);
            if ($count) {
                $_SESSION['error'] = 'Код UTM ' . $data['key_item'] . ' уже существует в БД';                
                redirect();
            }
            if ($id = $utm->save('utm')) {
                $_SESSION['success'] = 'UTM добавлена';
                redirect(ADMIN . '/utm/edit?id=' . $id);
            }
            redirect();
        }
        $this->setMeta('Новая UTM');
        $id = $this->getRequestID();
        $utm = \R::load('utm', $id);
        $this->setMeta("Копирование UTM {$utm->title}");
        $this->set(compact('utm'));
    }
    public function editAction() {
        if (!empty($_POST)) {
            $utm = new Utm();
            $data = $_POST;
            $utm->load($data);
            if (!$utm->validate($data)) {
                $utm->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }
            $count = \R::count('utm', 'key_item = ? AND id <> ?', [$data['key_item'], $data['id']]);
            if ($count) {
                if ($this->isAjax()) {
                    $res = ['succes' => false, 'title' => 'Код уже существует', 'message' => 'Код UTM ' . $data['key_item'] . ' уже существует в БД'];
                    exit(json_encode($res));
                } else {
                    $_SESSION['error'] = 'Код UTM ' . $data['key_item'] . ' уже существует в БД';
                    redirect();
                }
            }
            if ($utm->update('utm', $data['id'])) {
                if ($this->isAjax()) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'UTM сохранена'];
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
        $utm = \R::load('utm', $id);
        $this->setMeta("Редактирование UTM {$utm->title}");
        $this->set(compact('utm'));
    }

    public function changeStatusAction() {
        if (!empty($_POST)) {
            $id = $_POST['id'] ?? 0;
            $id = (int) $id;
            $value = $_POST['value'] ?? 0;
            $value = (int) $value;
            $name = $_GET['name'] ?? '';

            if ($id && ($name == 'status' || $name == 'all_on')) {
                $res = Utm::changeStatus($value, $id, $name);
            } else {
                $res = ['succes' => false, 'title' => 'Данные не переданы', 'message' => 'Данные не переданы'];
            }
            exit(json_encode($res));
        }
        die;
    }

    public function typeaheadAction() {
        if ($this->isAjax()) {
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
            if ($query) {
                $utm = \R::getAll("SELECT id, key_item FROM utm WHERE key_item LIKE ? LIMIT 11", ["%{$query}%"]);
                echo json_encode($utm);
            }
        }
        die;
    }

}
