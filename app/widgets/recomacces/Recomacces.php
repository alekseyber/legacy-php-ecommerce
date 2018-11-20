<?php

namespace app\widgets\recomacces;

use ishop\App;
use ishop\Cache;
use \app\models\Product;

class Recomacces {

    protected $tpl = __DIR__ . '/recomacces_tpl/recomacces.php';
    protected $productsHtml = '';
    protected $products = [];

    public function __construct($tpl = '') {
        if ($tpl)
            $this->tpl = $tpl;

        if ($_SESSION['recomacces'] > 0) {
            $_SESSION['recomacces'] --;
            $this->run();
        }
    }

    protected function run() {
        $cache = Cache::instance();
        $this->productsHtml = $cache->get('recomacces');
        if (!$this->productsHtml) {
            $products = [];
            $cachetime = App::$app->getProperty('cachetime_recomacces');
            $ids = \R::getCol('SELECT id FROM product WHERE cart_on = "2"');
            shuffle($ids);
            $count = App::$app->getProperty('recomacces_count');
            $i = 0;
            $p_model = new Product();
            $p_widgets = new \app\widgets\product\Product();
            foreach ($ids as $v) {
                if ($i === $count) {
                   break; 
                }                    
                $products[$v] = $p_model->setProduct(false, [], false, $v);
                if ($products[$v]) {
                    $products[$v] = $p_widgets->toString($products[$v], 2);
                    $i++;
                }
            }            
            $this->products = $products;
            $this->productsHtml = $this->productsHtml();

            $cache->set('recomacces', $this->productsHtml, $cachetime, 'recomacces');
        }
    }

    public function __toString() {
        return $this->productsHtml;
    }

    protected function productsHtml() {
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }

}
