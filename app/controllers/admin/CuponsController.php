<?php

namespace app\controllers\admin;

use ishop\libs\Pagination;
use app\models\admin\Cupons;

class CuponsController extends AppController {

    public function indexAction() {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 50;

        $count = \R::count('cupons');
        $pagination = new Pagination($page, $perpage, $count);
        $start = $pagination->getStart();

        $cupons = \R::getAll("SELECT * FROM `cupons` ORDER BY `id` DESC LIMIT $start, $perpage");
        $cuponsIds = \R::getCol("SELECT id FROM `cupons` LIMIT $start, $perpage");
        $countOrders = \R::getAssoc("SELECT `cupon_id`, COUNT(`id`) AS `count_orders` FROM `orders` WHERE `cupon_id` IN (1,2) Group By `cupon_id`", $cuponsIds);

        $this->setMeta('Список купонов');
        $this->set(compact('cupons', 'pagination', 'count', 'countOrders'));
    }

    public function changeDataAction() {
        if (!empty($_POST)) {
            $id = $_POST['id'] ?? 0;
            $id = (int) $id;
            $value = $_POST['value'] ?? "";
            $name = $_POST['name'] ?? '';

            $res = Cupons::changeData($value, $id, $name);
            exit(json_encode($res));
        }
        die;
    }

    public function addAction() {
        if (!empty($_POST)) {
            $cupons = new Cupons();
            $data = $_POST;

            $userValid = true;
            $fields = ['value', 'date_end'];

            foreach ($fields as $field) {
                $resValue = Cupons::normalizeValue($data[$field], $field);
                if (!$resValue['valid']) {
                    $userValid = false;
                } else {
                    $data[$field] = $resValue['value'];
                }
            }
            if (!$userValid) {
                $_SESSION['error'] = 'Не верно значение или дата';
                redirect();
            }

            $cupons->load($data);
            if (!$cupons->validate($data)) {
                $cupons->getErrors();
                //  $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $cupons->save('cupons')) {
                $_SESSION['success'] = 'Купон добавлен';
                redirect(ADMIN . '/cupons');
            }
            redirect();
        }
        $this->setMeta('Новый промокод');
    }

    public function deleteAction() {
        $cupons_id = $this->getRequestID();
        $order = \R::load('cupons', $cupons_id);

        \R::trash($order);
        $_SESSION['success'] = 'Купон удален';
        redirect(ADMIN . '/cupons');
    }

}
