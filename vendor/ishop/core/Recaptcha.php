<?php

/**
 *    - Documentation and latest version
 *          https://developers.google.com/recaptcha/docs/php
 *    - Get a reCAPTCHA API Key
 *          https://www.google.com/recaptcha/admin#list

 *

 */

namespace ishop;

class Recaptcha {

    private $siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify";
    private $secret;
    public $rezult = ['success' => '', 'errorCodes' => []];

    use TSingletone;

    protected function __construct() {
        $reCAPTCHA = \ishop\App::$app->getProperty('reCAPTCHA');
        $this->secret = $reCAPTCHA['secret'];
    }

    private function submitHTTPPost($post_data) {
        $url = $this->siteVerifyUrl;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $output = curl_exec($ch);
            curl_close($ch);
        } catch (Exception $ex) {
            $this->rezult['success'] = false;
            $this->rezult['errorCodes'] = $e->getMessage();
            return false;
        }

        if ($output) {
            $rezult = json_decode($output, true);
        } else
            return false;

        if (isset($rezult['success'])) {
            $this->rezult['success'] = $rezult['success'];
            $this->rezult['errorCodes'] = $rezult['error-codes"'] ?? '';
        } else
            return false;

        if ($this->rezult['success'] === true) {
            return true;
        } else
            return false;
    }

    public function verifyResponse($response = null, $remoteIp = 0) {
        if ($remoteIp == 0)
            $remoteIp = detectIp();
        
        if ($response == null || strlen($response) == 0) {
            $this->rezult['success'] = false;
            $this->rezult['errorCodes'] = ['missing-input'];
            return false;
        }

        $post_data = [
            "secret" => $this->secret,
            "response" => $response,
            "remoteip" => $remoteIp
        ];
        $rezult = $this->submitHTTPPost($post_data);

        return $rezult;
    }

}
