<?php

namespace app\widgets\cartmini;

class Cartmini {

    public static function toString($mini = true, $deliv = false) {
        if ($deliv) {
            return self::delivinfHtml();
        }

        if ($mini) {
            return self::cartminiHtml();
        }
        return self::cartHtml();
    }

    protected static function cartminiHtml() {
        ob_start();
        $cartEmpty = self::cartEmptyHtml();
        require __DIR__ . '/cartmini_tpl/cartmini.php';
        return ob_get_clean();
    }

    protected static function cartHtml() {
        ob_start();
        $delivinfCont = self::delivinfHtml();
        $cartEmpty = self::cartEmptyHtml();
        require __DIR__ . '/cartmini_tpl/cart.php';
        return ob_get_clean();
    }

    protected static function delivinfHtml() {
        ob_start();
        require __DIR__ . '/cartmini_tpl/cart_delivery.php';
        return ob_get_clean();
    }

    protected static function cartEmptyHtml() {
        ob_start();
        require __DIR__ . '/cartmini_tpl/cart_empty.php';
        return ob_get_clean();
    }

}
