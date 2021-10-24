<?php

namespace app\widgets\greatjs;

class Greatjs {

    protected $args = [];
    protected $argsNames=[];
    protected $body = '';
    protected $tpl = __DIR__ . '/tpl.php';
    public $jsString = '';
    public $jsData = [];

    //protected $params = [];

    public function __construct($args = [], $argsNames = [], $body = "") {
        $this->args = is_array($args) ? $args : [];
        $this->argsNames = is_array($argsNames) ? $argsNames : [];
        $this->body = is_string($body) ? $body : '';
        $this->run();
    }

    protected function run() {
        $this->jsData = [
            'args' => $this->args,
            'argsNames' => $this->argsNames,
            'body' => $this->body,
        ];

        $paramsJson = h(json_encode($this->jsData));

        ob_start();
        require $this->tpl;
        $this->jsString = ob_get_clean();
    }

    public function getJsData() {
        return $this->jsData;
    }

    public function __toString() {
        return $this->jsString;
    }

}
