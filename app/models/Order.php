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

            // Аудит 2026-11: значения подставлялись в запрос строкой, поэтому товар
            // с апострофом в названии («O'Neil») ломал оформление заказа.
            // Теперь — плейсхолдеры, число строк собирается отдельно.
            $rows = [];
            $bind = [];
            foreach ($_SESSION['cart'] as $product) {
                $rows[] = '(?, ?, ?, ?, ?, ?, ?, ?)';
                $bind[] = $order_id;
                $bind[] = (int) $product['id'];
                $bind[] = $product['img_thumb'];
                $bind[] = (int) $product['qty'];
                $bind[] = $product['title'];
                $bind[] = $product['price'];
                $bind[] = $product['link'];
                $bind[] = (int) $product['nosizeselect'];
            }
            if ($rows) {
                \R::exec('INSERT INTO orderproduct (order_id, registr_id, img_thumb, qty, title, price, link, nosizeselect) VALUES ' . implode(',', $rows), $bind);
            }

            \R::commit();
        } catch (\Throwable $e) {
            // Аудит 2026-11: раньше исключение гасилось, метод всё равно возвращал true,
            // и клиент получал подтверждение несуществующего заказа, а админ — письмо о нём.
            \R::rollback();
            error_log('[' . date('Y-m-d H:i:s') . '] заказ не сохранён: ' . $e->getMessage() . "\n=================\n", 3, ROOT . '/tmp/errors.log');
            return false;
        }
        $_SESSION['order_num'] = $this->attributes['order_num'];
        return true;
    }

    public function mailOrder() {
        $order_num = $this->attributes['order_num'];

        // Create the Transport
        // Аудит 2026-11: локальный SMTP (mailpit) работает без авторизации и шифрования.
        // Раньше логин подставлялся всегда, и стенд падал на аутентификации.
        $protocol = App::$app->getProperty('smtp_protocol');
        $transport = new Swift_SmtpTransport(App::$app->getProperty('smtp_host'), App::$app->getProperty('smtp_port'), $protocol ? $protocol : null);
        if (App::$app->getProperty('smtp_login')) {
            $transport->setUsername(App::$app->getProperty('smtp_login'));
            $transport->setPassword(App::$app->getProperty('smtp_password'));
        }
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

        // Аудит 2026-11: без авторизации логин пуст, и пустой From не проходит проверку адреса.
        $from = App::$app->getProperty('smtp_login') ? App::$app->getProperty('smtp_login') : App::$app->getProperty('shop_email');
        $message_admin = (new Swift_Message("Сделан заказ №{$order_num}"))
                ->setFrom([$from => App::$app->getProperty('shop_name')])
                ->setTo(App::$app->getProperty('admin_email'))
                ->setBody($body, 'text/html')
        ;

        // Send the message
        // $result = $mailer->send($message_client);
        $result = $mailer->send($message_admin);
    }

}
