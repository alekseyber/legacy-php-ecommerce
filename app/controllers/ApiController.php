<?php

namespace app\controllers;

use app\models\Api;

class ApiController extends AppController {

    public function indexAction() {
        if (isset($_GET['key'])) {
            $key = !empty(trim($_GET['key'])) ? trim($_GET['key']) : null;
            echo new Api($key);
        }

        die;
    }

}
