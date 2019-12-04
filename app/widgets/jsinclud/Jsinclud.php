<?php

namespace app\widgets\jsinclud;

use ishop\App;

class Jsinclud {

    protected $tplArr = [];
    protected $tpl = 0;
    public $js;
    protected $params = [];

    public function __construct($ind = 0, $params = []) {
        $this->tplArr[0] = __DIR__ . '/jsinclud_js/jsinclud_0.php';
        $this->tplArr[1] = __DIR__ . '/jsinclud_js/jsinclud_1.php';
        $this->tplArr[2] = __DIR__ . '/jsinclud_js/jsinclud_2.php';
        $this->tplArr[3] = __DIR__ . '/jsinclud_js/jsinclud_3.php';
        if ($ind > 0 && isset($this->tplArr[$ind])) {
            $this->tpl = $this->tplArr[$ind];
        } else {
            $this->tpl = $this->tplArr[0];
        }
        if ($params)
            $this->params = $params;

        $this->js = $this->getHtml();
    }

    public function __toString() {
        return $this->js;
    }

    protected function getHtml() {
        $content = '';
        if ($this->params)
            extract($this->params);
        ob_start();
        require $this->tpl;
        $content = ob_get_clean();
        return $content;
    }

}
