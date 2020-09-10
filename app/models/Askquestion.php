<?php

namespace app\models;

use ishop\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Askquestion extends AppModel {

    public $attributes = [
        'name' => '',
        'phone' => '',
        'note' => '',
        'acquirers_id' => 0,
        'geo_city' => '',
        'geo_region' => '',
        'ip' => '',
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
        'note',
    ];
    public $acquirers_status = false;

    public function hChek() {
        foreach ($this->hpole as $v) {
            $this->attributes[$v] = h($this->attributes[$v]);
        }
        $this->attributes['phone'] = phoneFormat($this->attributes['phone']);
    }

    public function saveAskquestion() {

        if (isset($_SESSION['geo_data'])) {
            $this->attributes['ip'] = $_SESSION['geo_data']['ip_int'];
            $this->attributes['geo_city'] = $_SESSION['geo_data']['geo_city'];
            $this->attributes['geo_region'] = $_SESSION['geo_data']['geo_region'];
        }
        $acquirers = \R::findOne('acquirers', 'phone = ?', [$this->attributes['phone']]);
        
        if ($acquirers) {
            $this->attributes['acquirers_id'] = $acquirers->id;
            $this->acquirers_status = $acquirers->status;
        }
       $askquestion = \R::dispense('askquestion');
        foreach ($this->attributes as $k => $v) {
            $askquestion->$k = $v;
        }        
        $askquestion_id = \R::store($askquestion);
        return true;
    }

    public function mailAskquestion() {
        

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
        $product = null;
        ob_start();
        extract($attributes);
        require APP . '/views/mail/mail_askquestion.php';
        $body = ob_get_clean();

        $message_admin = (new Swift_Message("Заявка на обратный звонок на сайте"))
                ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
                ->setTo(App::$app->getProperty('admin_email'))
                ->setBody($body, 'text/html')
        ;

        $result = $mailer->send($message_admin);
    }

}
