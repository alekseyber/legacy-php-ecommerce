<?php

namespace app\controllers\admin;

class ParamsController extends AppController {

    public function indexAction() {

        $params = \R::getAll('SELECT * FROM `params` WHERE `status` = 1');

        $this->setMeta('Глобальные настройки');
        $this->set(compact('params'));
    }

}
