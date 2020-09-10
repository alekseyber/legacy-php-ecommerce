<?php

namespace app\widgets\product;

class Product
{

    protected $tpl = '';
    protected $tplArr = [];
    protected $options = [];
    protected $productHtml = '';
    protected $web_on = 0;

    public function __construct()
    {
        $this->tplArr[0] = __DIR__ . '/product_tpl/product.php';
        $this->tplArr[1] = __DIR__ . '/product_tpl/product_model_1.php';
        $this->tplArr[2] = __DIR__ . '/product_tpl/product_model_2.php';
        $this->tplArr[3] = __DIR__ . '/product_tpl/product_full_mini.php';
        $this->tplArr[4] = __DIR__ . '/product_tpl/product_full_primary.php';
        $this->tplArr[5] = __DIR__ . '/product_tpl/product_full_primary.php'; //modal product

        $this->tpl = $this->tplArr[0];
        $this->options['curr'] = \ishop\App::$app->getProperty('currency');
        $this->options['main'] = false;
        $this->options['webp_on'] = \ishop\App::$app->getProperty('webp_on');       
    }

    public function toString($product = [], $tpl = 0)
    {
        if (!$product)
            return false;
        if ((int) $tpl <= 5) {
            $tpl = (int) $tpl;
            $this->tpl = $this->tplArr[$tpl];
        } else {
            return false;
        }

        if ($tpl === 4) {
            $this->options['main'] = true;
        }

        $this->options['product'] = $product;

        $this->productHtml = $this->productHtml();
        return $this->productHtml;
    }

    protected function productHtml()
    {
        ob_start();        
        extract($this->options);
        require $this->tpl;
        return ob_get_clean();
    }
}
