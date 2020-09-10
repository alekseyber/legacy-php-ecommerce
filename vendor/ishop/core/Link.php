<?php

namespace ishop;

use ishop\App;

class Link {

    static public function getLinkProduct($product = [], $id = 0, $pr = 'product') {

        if (!$product && !$id)
            throw new \Exception('class Link не передан массив $product и $id', 500);
        if (!isset($product['alias']) && !$id)
            throw new \Exception('class Link не передан атрибут alias', 500);
        if (!isset($product['seolink']) && !$id)
            throw new \Exception('class Link не передан атрибут seo', 500);

        if ($id)
            $product = \R::getRow("SELECT alias, seolink FROM $pr WHERE id = ? LIMIT 1", [$id]);

        $seo_link_on = \ishop\App::$app->getProperty('seo_link_on');
        if ($seo_link_on) {
            return self::getLinkPrefix() . $product['seolink'];
        } else {
            return self::getLinkPrefix() . $pr . '/' . $product['alias'];
        }
    }

    static public function getLink($link = '') {
        if (!$link) {
            return false;
        }
        $link = ltrim($link, '/');
        return self::getLinkPrefix() . $link;
    }

    static public function getSocialLink($link = '') {

        if (!$link)
            return false;

        $link = urlencode($link);
        $rezult['vkontakte'] = ['icon' => "vk", 'link' => 'https://vkontakte.ru/share.php?url=' . $link];
        $rezult['odnoklassniki'] = ['icon' => "ok", 'link' => 'https://connect.ok.ru/offer?url=' . $link];
        $rezult['facebook'] = ['icon' => "fb", 'link' => 'https://www.facebook.com/sharer.php?u=' . $link];

        return $rezult;
    }

    static protected function getLinkPrefix() {
        $prefix_link_on = App::$app->getProperty('prefix_link_on');
        if ($prefix_link_on) {
            return '/';
        }
    }

}
