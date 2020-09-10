<?php

namespace app\controllers\admin;

use app\models\admin\Headerstr;

class HeaderstrController extends AppController {

    public function indexAction() {

        $headerstr = \R::findOne('headerstr', "current = 1");
        $this->setMeta('Headerstr');
        $this->set(compact('headerstr'));
    }

    public function editAction() {
        if ($this->isAjax()) {
            if (!empty($_POST)) {
                $id = $this->getRequestID(false);
                $headerstr = new Headerstr();                

                $data = $_POST;
                $headerstr->load($data);
                if (!$headerstr->validate($data)) {
                    $lastmodified->getErrors();
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                }
                if ($headerstr->update('headerstr', $id)) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => "Изменения сохранены успешно", 'id' => $id];
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
