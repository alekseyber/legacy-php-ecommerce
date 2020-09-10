<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\User;
use ishop\base\Controller;
use ishop\App;

class AppController extends Controller {

    public $layout = 'admin';

    public function __construct($route) {
        parent::__construct($route);
        if (!User::isAdmin() && $route['action'] != 'login-admin') {
            redirect(ADMIN . '/user/login-admin'); // UserController::loginAdminAction
        }
        self::uriChek();
        new AppModel();
    }

    public function getRequestID($get = true, $id = 'id') {
        if ($get) {
            $data = $_GET;
        } else {
            $data = $_POST;
        }
        $id = !empty($data[$id]) ? (int) $data[$id] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }
        return $id;
    }

    public function editValueAction() {
        if (!empty($_POST)) {
            $id = $_POST['id'] ?? 0;
            $id = (int) $id;
            $value = $_POST['value'] ?? '';
            $name = $_POST['name'] ?? '';
            $table = $_POST['table'] ?? '';
            if ($id && $name && $table) {
                $data = \R::load($table, $id);
                if ($data) {
                    $data->$name = $value;
                    \R::store($data);
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Изменения сохраненs успешно'];
                } else {
                    $res = ['succes' => false, 'title' => 'Изменения не сохранены', 'message' => 'Запись в БД не найдена'];
                }
            } else {
                $res = ['succes' => false, 'title' => 'Данные не переданы', 'message' => 'Данные не переданы'];
            }
            exit(json_encode($res));
        }
        die;
    }

    protected static function uriChek() {
        $uri = '';
        //$get = $_GET;
        $get = $_REQUEST;
        //$getparams = [];
        $controlleraction = App::$app->getProperty('controlleraction');

        if ($controlleraction) {
            unset($get[$controlleraction]);
            $controlleraction = mb_strtolower($controlleraction);
            $uri = $controlleraction;
        }

        App::$app->setProperty('chek_uri', urldecode($uri));
    }

}
