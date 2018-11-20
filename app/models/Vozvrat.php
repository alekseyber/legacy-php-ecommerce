<?php

namespace app\models;

use ishop\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Vozvrat extends AppModel {

    public $attributes = [
        'phone' => '',
        'acquirers_id' => 0,
        'geo_city' => '',
        'geo_region' => '',
        'ip' => '',
        'orders' => '',
        'action' => '',
    ];
    public $rules = [
        'required' => [
            ['phone'],
        ],
        'phone' => [
            ['phone'],
        ],
    ];
    public $label = [
        'phone' => 'Телефон',
    ];

    public function saveVozvrat() {
        
        if (isset($_SESSION['geo_data'])) {
            $this->attributes['ip'] = $_SESSION['geo_data']['ip_int'];
            $this->attributes['geo_city'] = $_SESSION['geo_data']['geo_city'];
            $this->attributes['geo_region'] = $_SESSION['geo_data']['geo_region'];
        }
        $vozvrat = \R::dispense('vozvrats');
        foreach ($this->attributes as $k => $v) {
            $vozvrat->$k = $v;
        }
        $vozvrat_id = \R::store($vozvrat);
        return true;
    }

    public function mailVozvrat($act = 'возврат') {


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
        require APP . '/views/mail/mail_vozvrat.php';
        $body = ob_get_clean();

        $message_admin = (new Swift_Message("Заявка на $act на сайте"))
                ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
                ->setTo(App::$app->getProperty('admin_email'))
                ->setBody($body, 'text/html')
        ;

        $result = $mailer->send($message_admin);
    }

}
