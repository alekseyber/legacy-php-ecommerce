<?php

namespace app\controllers\admin;

use app\models\User;
use ishop\libs\Pagination;

class UserController extends AppController {

    public function indexAction() {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 3;
        $count = \R::count('user');
        $pagination = new Pagination($page, $perpage, $count);
        $start = $pagination->getStart();
        $users = \R::findAll('user', "LIMIT $start, $perpage");
        $this->setMeta('Список пользователей');
        $this->set(compact('users', 'pagination', 'count'));
    }

    public function addAction() {
        $this->setMeta('Новый пользователь');
    }

    public function addBlockAction() {
        if (!empty($_POST)) {
            $ip_str = $_POST['ip'] ?? '';
            $ip = ip2long($ip_str);
            if ($ip) {
                $blockip = \R::dispense('blockip');
                $blockip->ip = $ip;
                $blockip->ipstr = $ip_str;
                \R::store($blockip);
                error_log("[" . date('Y-m-d H:i:s') . "] Администратор внес IP в блок | IP: {$ip_str} |\n=================\n", 3, ROOT . '/tmp/addblockip.log');
                $_SESSION['success'] = 'Изменения сохранены';
            } else {
                $_SESSION['error'] = 'IP адрес введен не верно';
            }
            redirect();
        }
        $this->setMeta('Внести IP в блок');
    }

    public function editAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $user = new \app\models\admin\User();
            $data = $_POST;
            $user->load($data);
            if (!$user->attributes['password']) {
                unset($user->attributes['password']);
            } else {
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
            }
            if (!$user->validate($data) || !$user->checkUnique()) {
                $user->getErrors();
                redirect();
            }
            if ($user->update('user', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
            }
            redirect();
        }

        $user_id = $this->getRequestID();
        $user = \R::load('user', $user_id);

        //$orders = \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` FROM `order`
        //JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
        //WHERE user_id = {$user_id} GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id`");

        $this->setMeta('Редактирование профиля пользователя');
        $this->set(compact('user', 'orders'));
    }

    public function loginAdminAction() {
        $ip_str = detectIp();
        $ip = ip2long($ip_str);
        $block_ip = \R::count('blockip', 'ip = ?', [$ip]);
        if ($block_ip) {
            throw new \Exception('IP в block_ip', 503);
        }
        if (!empty($_POST)) {
            $user = new User();
            if (!$user->login(true)) {
                $_SESSION['error'] = 'Логин/пароль введены неверно';
                if (isset($_SESSION['error_login'])) {
                    if ($_SESSION['error_login'] < 5) {
                        $_SESSION['error_login'] ++;
                    } else {
                        $blockip = \R::dispense('blockip');
                        $blockip->ip = $ip;
                        $blockip->ipstr = $ip_str;
                        \R::store($blockip);
                        throw new \Exception('IP внесен в block_ip', 503);
                    }
                } else {
                    $_SESSION['error_login'] = 1;
                }

                $login = $_POST['login'] ?? 'не получен';
                error_log("[" . date('Y-m-d H:i:s') . "] Неудачная аутентификация | IP: {$ip_str} | login: {$login} |\n=================\n", 3, ROOT . '/tmp/errorslogin.log');
            }
            if (User::isAdmin()) {
                $login = $_POST['login'] ?? '';
                error_log("[" . date('Y-m-d H:i:s') . "] Удачная аутентификация | IP: {$ip_str} | IP_long: {$ip} | login: {$login} |\n=================\n", 3, ROOT . '/tmp/successlogin.log');
                redirect(ADMIN);
            } else {
                redirect();
            }
        }
        $this->layout = 'login';
    }

}
