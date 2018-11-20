<?php

namespace app\controllers\admin;

use app\models\admin\Lastmodified;

class LastmodifiedController extends AppController {

    public function indexAction() {

        $lastmodified = \R::getAssoc('SELECT * FROM `lastmodified` LIMIT 15');

        $this->setMeta('Lastmodified');
        $this->set(compact('lastmodified'));
    }

    public function allsettimeAction() {
        \R::exec('UPDATE `lastmodified` SET `update_at`= ?', [date('Y-m-d H:i:s')]);
        redirect();
    }

    public function editAction() {
        if ($this->isAjax()) {
            if (!empty($_POST)) {
                $id = $this->getRequestID(false);
                $lastmodified = new Lastmodified();
                $data = $_POST['data'][$id];
                if (isset($data['up_dat'])) {
                    $data['update_at'] = date('Y-m-d H:i:s');
                }
                $lastmodified->load($data);
                if (!$lastmodified->validate($data)) {
                    $lastmodified->getErrors();
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                }
                if ($lastmodified->update('lastmodified', $id)) {
                    $update_at = \R::getCell('SELECT `update_at` FROM `lastmodified` WHERE `id` = ?', [$id]);
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => "Lastmodified $id сохранен", 'id' => $id, 'update_at' => $update_at];
                    exit(json_encode($res));
                }
                $res = ['succes' => false, 'title' => 'Изменения не сохранены', 'message' => 'Ошибка БД'];
                exit(json_encode($res));
            }
        } else {
            die;
        }
    }

}
