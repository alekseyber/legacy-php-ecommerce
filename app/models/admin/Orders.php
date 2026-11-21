<?php

namespace app\models\admin;

use app\models\AppModel;

class Orders extends AppModel {

    public $attributes = [
        'name' => '',
        'phone' => '',
        'address' => '',
        'note' => '',
        'geo_city' => '',
        'address' => '',
        'note_admin' => '',
        'dat_deliv' => '',
        'metro' => '',
        'cdek' => '',
        'delivery' => '',
        'delivery_pvz' => '',
        'update_at' => '',
        'time_deliv' => '',
    ];
    public $rules = [
        'required' => [
            ['name'],
            ['phone'],
        ]
    ];

    public function saveCart($id, $data) {
        $new = false;
        $cart = $data['cart'] ?? [];        
        
        foreach ($cart as $k => $v) {
            if ($v['qty'] == 0) {
                $new = true;
                $item = \R::load('orderproduct', $k);
                \R::trash($item);
                continue;
            }
            $v['nosizeselect'] = isset($v['nosizeselect']);
            $this->updateData('orderproduct', $v, $k);
        }
        if (isset($data['newproduct'])) {
            $res = json_decode($data['newproduct'], true);
            $title = $res['title'];
            $newproduct = explode(',', $res['key']);
            $newproduct[2] = (int) $newproduct[2];
            $newproduct[1] = (int) $newproduct[1];
            if ($newproduct[2]) {
                $reg_id = $newproduct[2];
            } else {
                $reg_id = \R::getCell('SELECT `id` FROM `registr` WHERE `modification_id` = ?', [$newproduct[1]]);
            }
            $orderproduct = \R::dispense('orderproduct');
            $orderproduct->registr_id = (int) $reg_id;
            $orderproduct->order_id = $id;
            $orderproduct->title = $title;
            $product_id = \R::getCell('SELECT `product_id` FROM `registr` WHERE `id` = ?', [$reg_id]);
            $price = \R::getCell('SELECT `price` FROM `product` WHERE `id` = ?', [$product_id]);
            $orderproduct->price = $price;
            $orderproduct->qty = 1;
            $mod_id = \R::getCell('SELECT `modification_id` FROM `registr` WHERE `id` = ?', [$reg_id]);
            //$img_thumb
            $img = \R::getCell('SELECT `img` FROM `modification` WHERE `id` = ?', [$mod_id]);
            if ($img) {
                $dan['img'] = $img;
                \app\models\Product::getImgFullPath($dan);
                $orderproduct->img_thumb = $dan['img_thumb'];
            }

            \R::store($orderproduct);
            $new = true;
        }

        return $new;
    }

    public static function changeStatus($status_id, $order_id) {
        $orders = \R::load('orders', $order_id);
        if (!$orders) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'Заказ не найден в БД'];
            return $res;
        }
        $orderstatus = \R::getAssoc('SELECT `id`, `status`, `action` FROM `orderstatus`');
        if (!isset($orderstatus[$status_id])) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'Статус заказ не найден в БД'];
            return $res;
        }

        $old_action = $orderstatus[$orders->orderstatus_id]['action'];
        $new_action = $orderstatus[$status_id]['action'];
        if ($orders->orderstatus_id == $status_id) {
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'Значения статуса не изменилось'];
            return $res;
        }
        $inf = '';
        $data = [];
        $data_no = [];
        $class = false;
        $message = '';
        \R::begin();
        try {
            $orders = \R::load('orders', $order_id);
            $orders->orderstatus_id = $status_id;
            $orders->update_at = date('Y-m-d H:i:s');
            $rrrr = \R::store($orders);
            if ($old_action || $new_action) {
                $registr_ids = \R::getAssoc('SELECT `registr_id`, qty FROM `orderproduct` WHERE `order_id` = ?', [$order_id]);
                foreach ($registr_ids as $id => $qty) {
                    $registr = \R::load('registr', $id);
                    $change = false;
                    if (!$old_action && $new_action) {
                        $registr->amount = $registr->amount - $qty;
                        $change = true;
                        $message = 'количество списано со склада';
                        $class = true;
                    }
                    if ($old_action && !$new_action) {
                        $registr->amount = $registr->amount + $qty;
                        $message = 'количество возвращено на склад';
                        $change = true;
                        $class = false;
                    }
                    if ($change) {
                        if ($registr->amount < 0) {
                            $registr->amount = 0;
                        }
                        if ($registr->amount == 0) {
                            $inf = ' ==На складе 0!==';
                            $data[$registr->id] = $registr;
                        }
                        if ($registr->amount > 0) {
                            if (isset($_SESSION['sklad'][$registr->id])) {
                                $data_no[] = $registr->id;
                            }
                        }
                        \R::store($registr);
                    }
                }
            }

            \R::commit();
        } catch (\Throwable $e) {
            \R::rollback();
            $res = ['succes' => false, 'title' => 'Ошибка', 'message' => $e];
            return $res;
        }
        foreach ($data_no as $reg) {
            unset($_SESSION['sklad'][$reg]);
        }
        foreach ($data as $k => $v) {
            $_SESSION['sklad'][$k] = $v;
        }
        if (!$message) {
            $message = 'Статус заказа изменен успешно';
        }
        $res = ['succes' => true, 'title' => 'Статус заказа изменен' . $inf, 'message' => $message, 'class' => $class];
        return $res;
    }

    public static function copyContact($id, $old_id) {
        $order = \R::load('orders', $id);
        if (!$order) {
            $_SESSION['error'] = 'Заказ получатель не найден в БД';
            return false;
        }
        $old_order = \R::getRow('SELECT `name`, `phone`, `geo_city`, `address`, `delivery`, `delivery_pvz`, `note`, `note_admin`, `metro` FROM `orders` WHERE `id` = ? LIMIT 1', [$old_id]);
        if (!$old_order) {
            $_SESSION['error'] = 'Заказ источник не найден в БД';
            return false;
        }
        foreach ($old_order as $k => $v) {
            $order->$k = $v;
        }
        \R::store($order);
        $_SESSION['success'] = 'Контакты скопированы';
    }

    public static function itemKurier($id) {
        $order = \R::getRow('SELECT * FROM `orders` WHERE `id` = ?', [$id]);
        if (!$order)
            return false;
        $rezult = '=====Заказ: ' . $order['order_num'] . '=====' . PHP_EOL;
        $rezult .= 'Клиент: ' . $order['name'] . PHP_EOL;
        $rezult .= 'Телефон: +' . $order['phone'] . PHP_EOL;
        if ($order['metro']) {
            $rezult .= 'Метро: ' . $order['metro'] . PHP_EOL;
        }
        $rezult .= 'Адрес: ' . $order['address'] . PHP_EOL;
        if ($order['dat_deliv'] && $order['dat_deliv'] != '0000-00-00') {
            $order['dat_deliv'] = datreturnexpl($order['dat_deliv']);
            $rezult .= 'Согласована дата доставки: ' . $order['dat_deliv'] . PHP_EOL;
        }
        if ($order['time_deliv'] > 0) {
            $arrtime = \ishop\App::$app->getProperty('time_delive');
            $rezult .= 'Согласовано время доставки: ' . $arrtime[$order['time_deliv']] . PHP_EOL;
        }
        if ($order['note']) {
            $rezult .= 'Сообщение покупателя: ' . $order['note'] . PHP_EOL;
        }
        if ($order['note']) {
            $rezult .= 'Заметка администратора: ' . $order['note_admin'] . PHP_EOL . PHP_EOL;
        }
        $products = \R::getAll('SELECT `title`, `qty`, `price` FROM `orderproduct` WHERE `order_id`= ?', [$order['id']]);
        if ($products) {
            $rezult .= '==Товары==' . PHP_EOL;
            $i = 1;
            foreach ($products as $product) {
                $rezult .= $i . '. ' . $product['title'] . ' цена: ' . $product['price'] . ' руб.' . PHP_EOL;
                $i++;
            }
        }
        $rezult .= '=========================' . PHP_EOL;
        return $rezult;
    }

}
