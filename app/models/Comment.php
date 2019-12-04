<?php

namespace app\models;

use ishop\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Comment extends AppModel {

    public $attributes = [
        'social' => 0,
        'author_name' => '',
        'author_avatar' => '',
        'author_provider' => '',
        'author_uid' => '',
        'commen_text' => '',
        'ip' => 0,
    ];
    public $rules = [
        'required' => [
            ['author_name'],
            ['commen_text'],
        ],
        'lengthMax' => [
            ['author_name', 100],
            ['commen_text', 2000],
        ],
    ];
    public $label = [
        'author_name' => 'Имя',
        'commen_text' => 'Текст отзыва',
    ];
    public $hpole = [
        'author_name',
        'commen_text',
        'author_uid',
    ];

    public function hChek() {
        foreach ($this->hpole as $v) {
            $this->attributes[$v] = h($this->attributes[$v]);
        }
    }

    public function saveComment() {
        $geo_data = \ishop\Geoip::getIp();
        $this->attributes['ip'] = $geo_data['ip_int'];

        $comment = \R::dispense('comment');
        foreach ($this->attributes as $k => $v) {
            $comment->$k = $v;
        }
        $comment_id = \R::store($comment);
        return true;
    }

    public function mailComment() {

        // Create the Transport
        $transport = (new Swift_SmtpTransport(App::$app->getProperty('smtp_host'), App::$app->getProperty('smtp_port'), App::$app->getProperty('smtp_protocol')))
                ->setUsername(App::$app->getProperty('smtp_login'))
                ->setPassword(App::$app->getProperty('smtp_password'))
        ;
        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        $attributes = $this->attributes;
        ob_start();
        extract($attributes);
        require APP . '/views/mail/mail_comment.php';
        $body = ob_get_clean();

        $message_admin = (new Swift_Message("Добавлен отзыв на сайте"))
                ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
                ->setTo(App::$app->getProperty('admin_email'))
                ->setBody($body, 'text/html')
        ;

        $result = $mailer->send($message_admin);
    }

}
