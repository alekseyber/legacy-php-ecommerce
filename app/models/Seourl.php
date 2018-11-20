<?php

namespace app\models;

class Seourl extends AppModel {

    public function getAlias($url = '') {
        $rezult = '';

        if ($url)
            $rezult = \R::getCell('SELECT `alias` FROM `sef` WHERE `link` = ?', [$url]);

        return $rezult;
    }

}
