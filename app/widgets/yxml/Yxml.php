<?php

namespace app\widgets\yxml;

use ishop\App;
use app\models\Product;
use ishop\libs\URL;
use ishop\Link;
use app\widgets\filter\Filter;

class Yxml {

    protected $file = WWW . '/yxml_jkzthzvptm.xml';
    protected $products;
    protected $currencyId;
    protected $categories;

    public function __construct($file = false) {
        if ($file) {
            $this->file = $file;
        }
        $sql_part = "SELECT `code` FROM `currency` WHERE `base` = '1'";
        $this->currencyId = \R::getCell($sql_part);
        $this->run();
    }

    protected function run() {
        $this->getCategories();
        $this->getProducts();
        $this->getYxml();
        echo "OK";
    }

    protected function getCategories() {
        $sql_part = "SELECT `id`,`title`,`parent_id` FROM `category` ORDER BY `parent_id`, `sortvalue`";
        $data = \R::getAll($sql_part);
        $this->categories = $data;
    }

    protected function getProducts() {
        $currencyId = $this->currencyId;
        $product_ids = Product::getAllProductsIds(false);
        $sql_part = 'SELECT id, title, alias, seolink, sku, price, old_price, color_default, product_model, gender, content, sizesgroup_id, brand_id, category_id';
        $sql_part .= ' FROM product';
        $sql_part .= " WHERE id IN (" . \R::genSlots($product_ids) . ")";
        $products = \R::getAssoc($sql_part, $product_ids);
        $vendors = \R::getAssoc('SELECT `id`, `title` FROM `brand`');
        foreach ($products as $key => &$product) {

            $product['id'] = $key;
            Product::getColorsProduct($product);
            $filterContent = new Filter([
                'ids' => $key,
                'tpl' => WWW . '/filter/oneproductnocolapse_filter_tpl.php',
                'oneproduct' => true,
            ]);
            // $filterContent = new Filter($key, false, WWW . '/filter/oneproductnocolapse_filter_tpl.php', true, true, false, false);
            $contData['content'] = $product['content'];
            $patternData = $filterContent->getPatternArray();
            Product::addProductPattern($product, $patternData);
            $contData = prRep($contData, $patternData);
            $contData['filter'] = $filterContent->getHtml();

            $product['content'] = $contData['content'] . $contData['filter'];
            $product['title'] = $product['title'] . ' ' . $product['gender'];
            if (($product['gender'] == 'Женские') || ($product['gender'] == 'Мужские')) {
                $product['vozrast'] = 'Взрослый';
                $product['gender'] = $product['gender'] == 'Женские' ? 'Женский' : 'Мужской';
            } else if ($product['gender'] == 'Детские') {
                $product['gender'] = 'Унисекс';
                if ($product['sizesgroup_id'] == 5) {
                    $product['vozrast'] = 'Для малышей';
                } else {
                    $product['vozrast'] = 'Детский';
                }
            }
            if (isset($vendors[$product['brand_id']])) {
                $product['brand_title'] = $vendors[$product['brand_id']];
            }
            $product_link = ltrim(Link::getLinkProduct($product), '/');
            $product['baselink'] = PATH . '/' . $product_link;
        }
        $sql_part = 'SELECT modification.id AS modification_id, color.alias, colorya.title AS ytitle, color.title, color.rustitle, price,';
        $sql_part .= ' old_price, color_id, product_id, img FROM modification';
        $sql_part .= ' JOIN color ON modification.color_id = color.id';
        $sql_part .= ' JOIN colorya ON color.tablegr_id = colorya.id';
        $sql_part .= " WHERE modification.status = '1'";
        $sql_part .= " AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id IN (" . \R::genSlots($product_ids) . ") AND amount > 0)";
        $sql_part .= ' ORDER BY color.sortvalue';

        $modifications = \R::getAssoc($sql_part, $product_ids);
        $mod_ids = [];
        foreach ($modifications as $k => &$modification) {
            $mod_ids[] = $k;
            $modification['id'] = $k;
            if ($products[$modification['product_id']]['color_default'] === $modification['alias']) {
                $modification['baselink'] = $products[$modification['product_id']]['baselink'];
            } else {
                $modification['baselink'] = URL::addGET($products[$modification['product_id']]['baselink'], 'color', $modification['alias']);
            }
            $gallery = Product::getGallery($k, $modification['img']);
            $modification['price'] = $modification['price'] ? $modification['price'] : $products[$modification['product_id']]['price'];
            $modification['old_price'] = $modification['old_price'] ? $modification['old_price'] : $products[$modification['product_id']]['old_price'];
            $img = [];
            foreach ($gallery as $item) {
                $img[] = PATH . $item['img_main'];
            }
            $modification['img'] = $img;
        }
        sort($mod_ids);
        $sql_part = 'SELECT modificationregistr.id AS modifregistr_id, sizes.alias, sizes.title, sizes.rudop, modificationregistr.modification_id FROM modificationregistr';
        $sql_part .= ' JOIN sizes ON modificationregistr.sizes_id = sizes.id';
        $sql_part .= " WHERE modificationregistr.id IN (SELECT modificationregistr_id FROM `registr` WHERE modification_id IN (" . \R::genSlots($mod_ids) . ") AND amount > 0)";
        $sql_part .= ' ORDER BY sizes.sortvalue';

        $modificationregistrs = \R::getAssoc($sql_part, $mod_ids);


        foreach ($modificationregistrs as &$modificationregistr) {

            // $modificationregistr['baselink'] = URL::addGET($modifications[$modificationregistr['modification_id']]['baselink'], 'sizes', $modificationregistr['alias']);
            $modificationregistr['baselink'] = $modifications[$modificationregistr['modification_id']]['baselink'];
            if ($modificationregistr['rudop']) {
                $modificationregistr['title'] = $modificationregistr['rudop'];
            }
        }
        $sql_part = 'SELECT `id`, `product_id`, `modification_id`, `modificationregistr_id`, `amount` FROM `registr` WHERE `amount` > 0 ORDER BY `product_id`, `modification_id`, `modificationregistr_id`';
        $registrs = \R::getAll($sql_part);
        $data = [];
        foreach ($registrs as $registr) {

            $dataitem = [];
            if (!isset($products[$registr['product_id']]) || !isset($modifications[$registr['modification_id']])) {
                continue;
            }
            $product_model = $products[$registr['product_id']]['product_model'];

            if (($product_model == '1' || $product_model == '4') && !isset($modificationregistrs[$registr['modificationregistr_id']])) {
                continue;
            }

            $dataitem['id'] = $registr['id'];
            $dataitem['group_id'] = $registr['product_id'];
            $dataitem['currencyId'] = $currencyId;
            $dataitem['url'] = '';
            $dataitem['price'] = $modifications[$registr['modification_id']]['price'];
            $dataitem['oldprice'] = $modifications[$registr['modification_id']]['old_price'];
            $dataitem['currencyId'] = $currencyId;
            $dataitem['categoryId'] = $products[$registr['product_id']]['category_id'];
            $dataitem['picture'] = $modifications[$registr['modification_id']]['img'];
            $dataitem['vendor'] = $products[$registr['product_id']]['brand_title'];
            $dataitem['vendorCode'] = $products[$registr['product_id']]['sku'];
            $dataitem['model'] = $products[$registr['product_id']]['title'];
            $dataitem['description'] = $products[$registr['product_id']]['content'];
            $dataitem['params'] = [];
            $i = 0;
            if (isset($products[$registr['product_id']]['gender'])) {
                $dataitem['params'][$i]['name'] = 'Пол';
                $dataitem['params'][$i]['value'] = $products[$registr['product_id']]['gender'];
                $i++;
            }
            if (isset($products[$registr['product_id']]['gender'])) {
                $dataitem['params'][$i]['name'] = 'Возраст';
                $dataitem['params'][$i]['value'] = $products[$registr['product_id']]['vozrast'];
                $i++;
            }
            switch ($product_model) {
                case '1':
                    $dataitem['url'] = $modificationregistrs[$registr['modificationregistr_id']]['baselink'];
                    $dataitem['params'][$i]['name'] = 'Цвет';
                    $dataitem['params'][$i]['value'] = $modifications[$registr['modification_id']]['ytitle'];
                    $i++;
                    $dataitem['params'][$i]['name'] = 'Размер';
                    $dataitem['params'][$i]['value'] = $modificationregistrs[$registr['modificationregistr_id']]['title'];
                    $i++;
                    break;
                case '2':
                    $dataitem['url'] = $modifications[$registr['modification_id']]['baselink'];
                    $dataitem['params'][$i]['name'] = 'Цвет';
                    $dataitem['params'][$i]['value'] = $modifications[$registr['modification_id']]['ytitle'];
                    $i++;
                    break;
                case '3':
                    $dataitem['url'] = $products[$registr['product_id']]['baselink'];
                    break;
                case '4':
                    $dataitem['url'] = $modificationregistrs[$registr['modificationregistr_id']]['baselink'];
                    $dataitem['url'] = URL::deleteGET($dataitem['url'], 'color');
                    $dataitem['params'][$i]['name'] = 'Размер';
                    $dataitem['params'][$i]['value'] = $modificationregistrs[$registr['modificationregistr_id']]['title'];
                    $i++;
                    break;
            }

            $data[] = $dataitem;
        }

        $this->products = $data;
    }

    protected function getYxml() {
        $shop_name = App::$app->getProperty('shop_name');
        $shop_name_rus = App::$app->getProperty('shop_name_rus');
        $dom = new \DOMDocument("1.0", "utf-8"); // Создаём XML-документ версии 1.0 с кодировкой utf-8
        $dom->formatOutput = true;
        $root = $dom->createElement("yml_catalog"); // Создаём корневой элемент
        $date = date("Y-m-d H:i");
        $root->setAttribute('date', $date);
        $dom->appendChild($root);
        $shop = $root->appendChild($dom->createElement('shop'));
        $shop->appendChild($dom->createElement('name', $shop_name));
        $shop->appendChild($dom->createElement('company', $shop_name_rus));
        $shop->appendChild($dom->createElement('url', PATH));
        $shop->appendChild($dom->createElement('enable_auto_discounts', 'yes'));
        $currencies = $shop->appendChild($dom->createElement('currencies'));
        $curr = $currencies->appendChild($dom->createElement('currency'));
        $curr->setAttribute('id', $this->currencyId);
        $curr->setAttribute('rate', '1');
        $categories = $shop->appendChild($dom->createElement('categories'));
        foreach ($this->categories as $category) {
            $itemcat = $categories->appendChild($dom->createElement('category', $category['title']));
            $itemcat->setAttribute('id', $category['id']);
            if ($category['parent_id']) {
                $itemcat->setAttribute('parentId', $category['parent_id']);
            }
        }
        $offers = $shop->appendChild($dom->createElement('offers'));
        foreach ($this->products as $product) {
            $offer = $offers->appendChild($dom->createElement('offer'));
            $offer->setAttribute('id', $product['id']);
            $offer->setAttribute('type', 'vendor.model');
            $offer->setAttribute('available', 'true');
            $offer->setAttribute('group_id', $product['group_id']);
            $offer->appendChild($dom->createElement('url', $product['url']));
            $offer->appendChild($dom->createElement('price', $product['price']));
            if ($product['oldprice']) {
                $offer->appendChild($dom->createElement('oldprice', $product['oldprice']));
            }
            $offer->appendChild($dom->createElement('currencyId', $product['currencyId']));
            $offer->appendChild($dom->createElement('categoryId', $product['categoryId']));
            foreach ($product['picture'] as $img) {
                $offer->appendChild($dom->createElement('picture', $img));
            }
            $offer->appendChild($dom->createElement('vendor', $product['vendor']));
            $offer->appendChild($dom->createElement('vendorCode', $product['vendorCode']));
            $offer->appendChild($dom->createElement('model', $product['model']));
            $description = $offer->appendChild($dom->createElement('description'));
            $description->appendChild(new \DOMCdataSection($product['description']));
            $offer->appendChild($dom->createElement('sales_notes', 'Доставка по России с примеркой, 2 пары на выбор!'));
            foreach ($product['params'] as $param) {
                $paramitem = $offer->appendChild($dom->createElement('param', $param['value']));
                $paramitem->setAttribute('name', $param['name']);
                if ($param['name'] == 'Размер') {
                    $paramitem->setAttribute('unit', 'RU');
                }
            }
        }
        $dom->save($this->file); // Сохраняем полученный XML-документ в файл
    }

}