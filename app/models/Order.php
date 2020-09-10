<?php

namespace app\models;

use ishop\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Order extends AppModel {

    public $attributes = [
        'name' => '',
        'phone' => '',
        'address' => '',
        'note' => '',
        'acquirers_id' => '',
        'currency' => '',
        'order_num' => '',
        'geo_city' => '',
        'geo_region' => '',
        'ip' => '',
        'cupon_id' => 0,
        'delivery' => '',
        'delivery_pvz' => '',
        'utm_id' => 0,
    ];
    public $rules = [
        'required' => [
            ['name'],
            ['phone'],
        ],
        'phone' => [
            ['phone'],
        ],
    ];
    public $label = [
        'name' => 'Имя',
        'phone' => 'Телефон',
    ];
    public $hpole = [
        'name',
        'address',
        'note',
    ];
    public $acquirers_status = false;

    public function hChek() {
        foreach ($this->hpole as $v) {
            $this->attributes[$v] = h($this->attributes[$v]);
        }

        $this->attributes['phone'] = phoneFormat($this->attributes['phone']);
    }

    public function saveOrder() {
        $this->attributes['currency'] = $_SESSION['cart.currency']['code'];
        $this->attributes['delivery'] = $_SESSION['delivery'] ?? '';
        $this->attributes['delivery_pvz'] = $_SESSION['delivery_pvz'] ?? '';

        if (isset($_SESSION['geo_data'])) {
            $this->attributes['ip'] = $_SESSION['geo_data']['ip_int'];

            $this->attributes['geo_city'] = $_SESSION['geo_data']['geo_city'];
            if ($this->attributes['geo_city'] === null) {
                $this->attributes['geo_city'] = '';
            }
            $this->attributes['geo_region'] = $_SESSION['geo_data']['geo_region'];
            if ($this->attributes['geo_region'] === null) {
                $this->attributes['geo_region'] = '';
            }
        }
        if (isset($_SESSION['promo_id']) && $_SESSION['promo_id'] > 0)
            $this->attributes['cupon_id'] = $_SESSION['promo_id'];
        if (isset($_SESSION['utm_id']) && $_SESSION['utm_id'] > 0)
            $this->attributes['utm_id'] = $_SESSION['utm_id'];

        \R::begin();
        try {
            $acquirers = \R::findOne('acquirers', 'phone = ?', [$this->attributes['phone']]);
            if ($acquirers) {
                $this->attributes['acquirers_id'] = $acquirers->id;
                $this->acquirers_status = $acquirers->status;
            } else {
                $acquirers = \R::dispense('acquirers');
                $acquirers['phone'] = $this->attributes['phone'];
                $this->attributes['acquirers_id'] = \R::store($acquirers);
            }

            $order_num = \R::findOne('ordernum', 'active = 1');
            $order_num->order_num++;
            \R::store($order_num);
            $this->attributes['order_num'] = $order_num->order_num . '-' . mt_rand(10, 99);
            $order = \R::dispense('orders');
            foreach ($this->attributes as $k => $v) {
                $order->$k = $v;
            }
            $order_id = \R::store($order);

            $sql_part = '';

            foreach ($_SESSION['cart'] as $product) {
                $sql_part .= "($order_id, {$product['id']}, ";
                $sql_part .= " '{$product['img_thumb']}', {$product['qty']}, '{$product['title']}', {$product['price']}, '{$product['link']}', {$product['nosizeselect']}),";
            }
            $sql_part = rtrim($sql_part, ',');

            \R::exec("INSERT INTO orderproduct (order_id, registr_id, img_thumb, qty, title, price, link, nosizeselect) VALUES $sql_part");

            \R::commit();
        } catch (Exception $e) {
            \R::rollback();
        }
        $_SESSION['order_num'] = $this->attributes['order_num'];
        return true;
    }

    public function mailOrder() {
        $order_num = $this->attributes['order_num'];

        // Create the Transport
        $transport = (new Swift_SmtpTransport(App::$app->getProperty('smtp_host'), App::$app->getProperty('smtp_port'), App::$app->getProperty('smtp_protocol')))
                ->setUsername(App::$app->getProperty('smtp_login'))
                ->setPassword(App::$app->getProperty('smtp_password'))
        ;
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $attributes = $this->attributes;
        $acquirers_status = $this->acquirers_status;
        ob_start();
        extract($attributes);
        require APP . '/views/mail/mail_order.php';
        $body = ob_get_clean();

//        $message_client = (new Swift_Message("Вы совершили заказ №{$order_num} на сайте " . App::$app->getProperty('shop_name')))
//                ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
//                ->setTo($user_email)
//                ->setBody($body, 'text/html')
//        ;

        $message_admin = (new Swift_Message("Сделан заказ №{$order_num}"))
                ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
                ->setTo(App::$app->getProperty('admin_email'))
                ->setBody($body, 'text/html')
        ;

        // Send the message
        // $result = $mailer->send($message_client);
        $result = $mailer->send($message_admin);
    }

}
