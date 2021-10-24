<?php

namespace app\models;

use ishop\App;
use app\models\Product;
use ishop\Link;
use ishop\libs\URL;
use app\widgets\sitemap\Sitemap;
use app\widgets\yxml\Yxml;

class Api {

    protected $answer = '';
    protected $key = '';

    public function __construct($key = '') {
        if ($key) {
            $this->key = $key;
            $this->run();
        }
    }

    public function __toString() {
        return $this->answer;
    }

    protected function run() {
        $metod = $this->getMetod();

        if (method_exists($this, $metod)) {
            $this->$metod();
        }
    }

    protected function getMetod() {

        return \R::getCell('SELECT apimetods.name FROM api JOIN apimetods ON api.metods_id = apimetods.id WHERE api.key = ? AND api.status = 1 AND apimetods.status = 1', [$this->key]);
    }

    protected function greatIndex() {
        //`ids`, `sortvalue`, `index_search` FROM `indexproduct`
        \R::exec('DELETE FROM indexproduct');
        $serch_base_str = App::$app->getProperty('serch_base_str');
        $serch_descr_cat_on = App::$app->getProperty('serch_descr_cat_on');
        $serch_title_rus_on = App::$app->getProperty('serch_title_rus_on');
        $serch_title_del = App::$app->getProperty('serch_title_del');

        $data = [];
        $sortv = 1;

        $category = $serch_descr_cat_on ? ', category.content AS cat_cont' : '';

        $sql_parth = 'SELECT product.id, product.brand_id, product.title, sku, gender, price, product.content AS pr_cont, product.alias, product.seolink' . $category;
        $sql_parth .= ' FROM product ';
        $sql_parth .= $serch_descr_cat_on ? ' JOIN category ON product.category_id = category.id' : '';
        $sql_parth .= ' WHERE product.id IN (SELECT DISTINCT product_id FROM registr WHERE amount > 0) AND product.status = 1';

        $products = \R::getAll($sql_parth);
        $serch_base_str_no_ugg = preg_replace('/угги/i', '', $serch_base_str);
        $serch_base_str_no_ugg = preg_replace('/угг/i', '', $serch_base_str_no_ugg);
        $serch_base_str_no_ugg = preg_replace('/ugg/i', '', $serch_base_str_no_ugg);
        $serch_base_str_no_ugg = preg_replace('/сапожки/i', '', $serch_base_str_no_ugg);

        foreach ($products as $product) {
            $serch_base_str_rez = $serch_base_str_no_ugg;
            if ($product['brand_id'] == 1) {
                $serch_base_str_rez = $serch_base_str;
            }
            $ids = $product['id'] . ',0,0';
            $data[$ids]['ids'] = $ids;
            $data[$ids]['sortvalue'] = $sortv;
            $data[$ids]['title'] = $product['title'];
            if ($serch_title_rus_on)
                $data[$ids]['title'] = preg_replace("/[а-яё]/iu", "", $data[$ids]['title']);

            foreach ($serch_title_del as $it) {
                $data[$ids]['title'] = preg_replace('/' . $it . '/i', "", $data[$ids]['title']);
            }

            $data[$ids]['title'] = preg_replace("/ {2,}/", " ", $data[$ids]['title']);
            $data[$ids]['title'] = trim($data[$ids]['title'], ' ');
            $data[$ids]['title'] = $data[$ids]['title'] . ' ' . $product['gender'];
          //  $data[$ids]['link'] = PATH . Link::getLinkProduct($product);
            $data[$ids]['link'] = Link::getLinkProduct($product);

            $category_cont = $product['cat_cont'] ?? '';
            $category_cont = $category_cont ? ' ' . strip_tags($category_cont) : '';
            $product['pr_cont'] = $product['pr_cont'] ? ' ' . strip_tags($product['pr_cont']) : '';
            $data[$ids]['index_search'] = $serch_base_str_rez . ' ' . $product['title'] . ' ';
            $data[$ids]['index_search'] .= $product['sku'] . ' ' . $product['gender'] . ' цена цене ' . $product['price'] . ' ' . Product::getFilterParams($product['id']) . $product['pr_cont'] . $category_cont;
            $data[$ids]['index_search'] = words($data[$ids]['index_search'], false);
        }

        $sortv = 2;

        $sql_parth = 'SELECT modification.id, modification.product_id, color.title, color.rustitle,';
        $sql_parth .= ' color.alias, color.search_str FROM modification';
        $sql_parth .= ' JOIN color ON modification.color_id = color.id';
        $sql_parth .= ' WHERE modification.id IN (SELECT DISTINCT modification_id FROM registr WHERE amount > 0) AND modification.status = 1';

        $modifications = \R::getAll($sql_parth);

        foreach ($modifications as $modification) {
            if (isset($data[$modification['product_id'] . ',0,0'])) {
                $index_search = $data[$modification['product_id'] . ',0,0']['index_search'];
                $link = $data[$modification['product_id'] . ',0,0']['link'];
                $ids = '0,' . $modification['id'] . ',0';
                $data[$ids]['ids'] = $ids;
                $data[$ids]['sortvalue'] = $sortv;
                $data[$ids]['title'] = $data[$modification['product_id'] . ',0,0']['title'] . ' ' . mb_convert_case($modification['title'], MB_CASE_TITLE);

                $data[$ids]['link'] = URL::addGET($link, 'color', $modification['alias'], false);

                $data[$ids]['index_search'] = $index_search . ' цвет цвета цветов ' . $modification['title'] . ' ';
                $data[$ids]['index_search'] .= $modification['rustitle'] . ' ' . $modification['alias'] . ' ' . $modification['search_str'];
                $data[$ids]['index_search'] = words($data[$ids]['index_search'], false);
            }
        }

        $sortv = 3;

        $sql_parth = 'SELECT registr.id, modificationregistr.modification_id, sizes.title, sizes.alias,';
        $sql_parth .= ' sizes.search_str, sizes.description FROM modificationregistr';
        $sql_parth .= ' JOIN sizes ON modificationregistr.sizes_id = sizes.id';
        $sql_parth .= ' JOIN registr ON modificationregistr.id = registr.modificationregistr_id';
        $sql_parth .= ' WHERE registr.amount > 0';

        $sizes = \R::getAll($sql_parth);

        foreach ($sizes as $size) {
            if (isset($data['0,' . $size['modification_id'] . ',0'])) {
                $index_search = $data['0,' . $size['modification_id'] . ',0']['index_search'];
                $link = $data['0,' . $size['modification_id'] . ',0']['link'];
                $ids = '0,0,' . $size['id'];
                $data[$ids]['ids'] = $ids;
                $data[$ids]['sortvalue'] = $sortv;
                $data[$ids]['title'] = $data['0,' . $size['modification_id'] . ',0']['title'] . ' р. ' . $size['title'];

                $data[$ids]['link'] = URL::addGET($link, 'sizes', $size['alias'], false);

                $data[$ids]['index_search'] = $index_search . ' размер размера размеры размеров ' . $size['title'] . ' ';
                $data[$ids]['index_search'] .= $size['alias'] . ' ' . $size['search_str'] . ' ' . $size['description'];
                $data[$ids]['index_search'] = words($data[$ids]['index_search'], false);
            }
        }

        if ($data) {
            $sql_parth = 'INSERT INTO `indexproduct`(`ids`, `sortvalue`, `title`, `link`, `index_search`)';
            $values = '';
            $valuesarr = [];
            foreach ($data as $val) {
                $values .= '(';
                foreach ($val as $item) {
                    $values .= '?,';
                    $valuesarr[] = $item;
                }
                $values = rtrim($values, ',');
                $values .= '),';
            }
            $values = rtrim($values, ',');
            $sql_parth .= ' VALUES ' . $values;
        }

        $rez = \R::exec($sql_parth, $valuesarr);

        $this->answer = $rez . ' ОК';
    }

    protected function greatSitemap() {
        new Sitemap();
    }

    protected function greatYxml() {
        new Yxml();
    }

    protected function getJsonData() {
        $json = '';
        $data = [];
//        $oldproducts = \R::getAll('SELECT `id`, `category`, `name` AS title, `description`, `content`, `price`, `price_n`,  `uid` AS sku, `descrip`,`prod_seo_name` AS seolink FROM `phpshop_products`');
//
//        //  debug($oldproducts);
//        $json = json_encode($oldproducts, JSON_UNESCAPED_UNICODE);
//
//        // debug($json, 1);
//        $this->answer = $json;
//        return true;
        //  die;
//=========установить главное фото============================         
//        $modification = \R::getAssoc('SELECT id FROM `modification`');
//        foreach ($modification as $item) {
//
//            $gallery = \R::getAll('SELECT img FROM `gallery` WHERE modification_id = ? AND main = 1', [$item]);
//            if ($gallery) {
//                debug($gallery[0]['img']);
//                $itemBean = \R::load('modification', $item);
//                $itemBean->img = $gallery[0]['img'];
//                \R::store($itemBean);
//            }
//        }
//        die;
//=========фото============================ 
//        $gallery = \R::getAssoc('SELECT * FROM `gallery`');
//        $params = \ishop\App::$app->getProperty('img_property');
//        //debug($params);
//        foreach ($gallery as $key => $item) {
//            $oldImg = $item['img'];
//            $newImg = md5(uniqid(rand(), true) . time()) . ".jpg";
//            $rezulttext = '';
//            $status = 1;
//            foreach ($params as $k => $v) {
//                $statustext = 'ОК';
//                $path = WWW . $v['path'];
//                $oldImgItemFull = $path . $oldImg;
//                $newImgItemFull = $path . $newImg;
//
//                if (file_exists($oldImgItemFull)) {
//
//                    $rezult = rename($oldImgItemFull, $newImgItemFull);
//                    if ($rezult) {
//                        echo "$oldImgItemFull - OK <br>";
//                    } else {
//                        echo "$oldImgItemFull - NO <br>";
//                        $statustext = 'NO';
//                        $status = 0;
//                    }
//                }
//                $rezulttext .= "$k-$statustext, ";
//            }
//            $itemBean = \R::load('gallery', $key);
//            $itemBean->statusrename = $status;
//            $itemBean->statustext = $rezulttext;
//            $itemBean->img = $newImg;
//            \R::store($itemBean);
//        }
//        die;
//=========Цвета============================ 
//        $colors = [];
//        $colorya = \R::getAssoc('SELECT * FROM `colorya` ORDER BY `sortvalue`');
//
//        $colorkeys = [
//            "blk" => "00000",
//            "che" => "c09853",
//            "cho" => "604c3d",
//            "grey" => "9e9e9e",
//            "san" => "dbcfa7",
//            "navy" => "1c4e88",
//            "ldfg" => "BCAEB3",
//            "blu" => "96c6ee",
//            "dus" => "d4a190",
//            "gld" => "beaf90",
//            "pink" => "f3e4df",
//            "port" => "521928",
//            "red" => "d32f2f",
//            "wht" => "e5e1d6",
//            "fuks" => "F754E1",
//            "gys" => "B6B6B4",
//            "pearl" => "D1D0CE",
//            "beig" => "F5F5DC",
//            "dri" => "d4a190",
//            "grv" => "eaf3ff",
//            "ltgr" => "BBBBBB",
//            "ppf" => "dbcfa7",
//            "skbl" => "BCC5C7",
//            "blu" => "96c6ee",
//            "wht" => "e5e1d6",
//            "soch" => "E4C8C5",
//            "aqu" => "d9f7eb",
//            "irg" => "d8dce7",
//            "yel" => "FFFF00",
//            "grn" => "008000",
//            "sftgld" => "ecdebb",
//            "tms" => "ff463a",
//            "rdw" => "5E2129",
//            "orng" => "FFA500",
//            "lnt" => "da685d",
//            "rzcv" => "A7FC00",
//            "slt" => "99FF99",
//            "slv" => "c9c9cb",
//            "drgr" => "013220",
//            "drcho" => "654321",
//            "drgrey" => "49423D",
//            "drnavy" => "002137",
//            "fiol" => "8B00FF",
//            "haki" => "806B2A",
//            "oliv" => "808000"
//        ];
//
//        foreach ($colorya as $id => $val) {
//            $children = \R::getAll('SELECT `alias` AS `aliasitem`, `title`, `rustitle`, `sortvalue`, `search_str` FROM `color` WHERE `tablegr_id` = ? ORDER BY `sortvalue`', [$id]);
//            // $keys = array_keys($children);
//            foreach ($children as $k => $item) {
//                //  $children[$item]['colorkey'] = strtolower($colorkeys[$item]);
//                $children[$k]['colorkey'] = strtolower($colorkeys[$item['aliasitem']]);
//            }
//
//            $colors[] = [
//                'alias' => $val['alias'],
//                'title' => $val['title'],
//                'sortvalue' => $val['sortvalue'],
//                'tags' => $val['tags'],
//                'colorkey' => strtolower($colorkeys[$val['alias']]),
//                'children' => $children,
//            ];
//        }
//        //  debug($colors);
//        $json = json_encode($colors, JSON_UNESCAPED_UNICODE);
//        exit($json);
//        // debug($colors);
//        // debug($json, 1);
//        // $this->answer = $json;
//        die;
//=========размеры============================        
//        //  $sizesGroup = \R::getAssoc('SELECT `id`, `mongo_id` FROM `sizesgroup`');
//        $datesizes = [];
//        $sizes = \R::getAssoc('SELECT `id`, `title`, `alias`, `description`, `sortvalue`, `search_str`, `rudop`, `tags`, `nosize` FROM `sizes` ORDER BY `sortvalue`');
//        $i = 0;
//        foreach ($sizes as $id => $item) {
//            $datesizes[$i] = $item;
//            $sql_part = 'SELECT `sizesgroup`.`mongo_id`  FROM `sizesgroup`';
//            $sql_part .= ' JOIN sizesgroupselect ON sizesgroup.id = sizesgroupselect.sizesgroup_id';
//            $sql_part .= ' WHERE sizesgroupselect.`sizes_id` = ?';
//            $sizesgroupselect = \R::getCol($sql_part, [$id]);
//            $datesizes[$i]['group'] = $sizesgroupselect;
//            $i++;
//        }
//        $datesizes[] = [
//            'title' => 'Нет размеров',
//            'alias' => 'nosize',
//            'description' => '',
//            'sortvalue' => 0,
//            'search_str' => '',
//            'rudop' => '',
//            'nosize' => true,
//            'group' => ['5d6668a694c9d40cd87424bb']
//        ];
//        //    $sizesgroupselect = \R::getCol('SELECT `sizesgroup_id`  FROM `sizesgroupselect` WHERE `sizes_id` = ?', [$id]);
//        //  $sizes = \R::getAll('SELECT `title`, `content`, `nosize` FROM `sizesgroup`');
//        $json = json_encode($datesizes, JSON_UNESCAPED_UNICODE);
//        exit($json);
//        //  debug($datesizes);
//
//        die;
//=========категории============================
        //    debug($arr_category_new_ids, 1);
//        $category = \R::getAll('SELECT `id` AS `old_id`, `title`, `htitle`, `alias`, `parent_id` AS `parent_id_old`, `meta_title`, `meta_keywords`, `meta_description`, `promo`, `content`, `img`, `cat_default`, `sortvalue`, `status` FROM `category` ORDER BY `sortvalue`');
//
//        foreach ($category as &$item) {
//            $meta = [];
//            $meta['title'] = $item['meta_title'];
//            unset($item['meta_title']);
//            $meta['description'] = $item['meta_description'];
//            unset($item['meta_description']);
//            $meta['keywords'] = $item['meta_keywords'];
//            unset($item['meta_keywords']);
//            $item['meta'] = $meta;
//            // unset($item['content']);
//        }
//
//        //   debug($category, 1);
//        $json = json_encode($category, JSON_UNESCAPED_UNICODE);
//        exit($json);
//        $category = new \app\widgets\menu\Menu([
//            'cache' => 0,
//            'admin' => true,
//        ]);
//
//        $img_category_property = App::$app->getProperty('img_category_property');
//        $img_category_property = 'https://demo-shop.example' . $img_category_property['150']['path'];
//
//        $data = $category->getJson();
//        $newdata = [];
//        foreach ($data as $id => &$item) {
////            unset($item['content']);
////            unset($item['promo']);
////            unset($item['meta_title']);
////            unset($item['meta_keywords']);
////            unset($item['meta_description']);            
////            unset($item['sortvalue']);
////            unset($item['status']);
////            unset($item['date']);
////            unset($item['update_at']);
////             unset($item['seolink']);
////             unset($item['cat_default']);
//
//            $newdata[$id] = [
//                // '_id' => $id,
//                'alias' => $item['alias'],
//                'title' => $item['title'],
//                'parent_id' => $item['parent_id'],
//                'seolink' => $item['seolink'],
//                'img' => $img_category_property . $item['img'],
//                'childs' => []
//            ];
//        }
//        //debug($newdata, 1);
//        $data = $this->getTree($newdata);
//===========================================
//==============Корзина=======================
//debug($_SESSION['cart'], 1);
//$data = [];
//$cart = $_SESSION['cart'] ?? [];
//foreach ($cart as $item) {
//    $data[] = $item;
//}
//===========================================
//==============Фильтр=======================
//        $product_ids = App::$app->getProperty('product_ids');
//        $uri = App::$app->getProperty('filter_default_uri');
////        $filter = new \app\widgets\filter\Filter([
////            'ids' => $product_ids,
////            'uri' => $uri,
////            'tpl' => WWW . '/filter/filter_main_tpl.php'
////        ]);
////
////        $datatemp = $filter->filter; 
//
//        $datatempgr = \R::getAll('SELECT `title`, `color`, `sizes`, `filter`, `cartproduct`, `radio`, `alias`, `sortvalue`, `delnot`, `status`, `id` As `old_id` FROM `attribute_group` ORDER BY`sortvalue`');
//       
//
//
//        foreach ($datatempgr as &$item) {
//
//            $item['filter'] = $item['filter'] ? true : false;
//            $item['cartproduct'] = $item['cartproduct'] ? true : false;
//            $item['delnot'] = $item['delnot'] ? true : false;
//            $item['status'] = $item['status'] ? true : false;
//            $item['sizes'] = $item['sizes'] ? true : false;
//            $item['radio'] = $item['radio'] ? true : false;
//            //$item['old_id'] = $id;
//
//            if (!$item['color'] && !$item['sizes']) {
//                $attrs = \R::getAll('SELECT `id` AS `alias_attrs`, `value` AS `title`, `tags`, `status` AS `status_attr`, `sortvalue` AS `sortvalueitem` FROM `attribute_value` WHERE `attr_group_id` = ? ORDER BY`sortvalue` ', [$item['old_id']]);
//
//                $q = 0;
//                foreach ($attrs as &$attr) {
//                    $item['attrs'][$q] = $attr;
//                    $item['attrs'][$q]['status_attr'] = $item['attrs'][$q]['status_attr'] ? true : false;
//                    $item['attrs'][$q]['onstatus'] = true;
//                    $item['attrs'][$q]['active'] = false;
//                    $item['attrs'][$q]['active'] = false;
//                    $q++;
//                }
//                $item['attrs'] = $attrs;
//            } else {
//                $tempalias = 'sizesattraliasnullvalue';
//                if ($item['color']) {
//                    $tempalias = 'colorsattraliasnullvalue';
//                }
//                $item['attrs'][] = [
//                    'alias_attrs' => $tempalias,
//                    'title' => $tempalias,
//                    'tags' => "",
//                    'status_attr' => false,
//                    'sortvalueitem' => 0,
//                    'onstatus' => false,
//                    'active' => false,
//                    'block' => false,
//                ];
//            }
//        }
//        foreach ($datatemp as $id => $item) {
//            //  unset($item['filter']);
//            // unset($item['cartproduct']);
//            //  unset($item['delnot']);
//            //  unset($item['status']);
//            unset($item['tablegrupp']);
//            unset($item['tablenpbinary']);
//            unset($item['bitmask']);
//            unset($item['select']);
//            unset($item['count']);
//            unset($item['get']);
//            $item['filter'] = $item['filter'] ? true : false;
//            $item['cartproduct'] = $item['cartproduct'] ? true : false;
//            $item['delnot'] = $item['delnot'] ? true : false;
//            $item['status'] = $item['status'] ? true : false;
//            $item['sizes'] = $item['sizes'] ? true : false;
//            $item['radio'] = $item['radio'] ? true : false;
//
//            $item['old_id'] = $id;
//            $newattrs = [];
//            if (!$item['color'] && !$item['sizes']) {
//                $i = 0;
//                foreach ($item['attrs'] as $id_attr => $attrs) {
//                    unset($attrs['sdvig']);
//                    unset($attrs['bitvalue']);
//                    //  unset($attrs['status']);
//                    unset($attrs['link']);
//                    // $attrs['link'] = ltrim($attrs['link'], '/');
//                    $attrs['on'] = $attrs['on'] ? true : false;
//                    $attrs['active'] = $attrs['active'] ? true : false;
//                    $attrs['block'] = $attrs['block'] ? true : false;
//                    $newattrs[] = [
//                        'alias_attrs' => (String) $id_attr,
//                        'title' => $attrs['value'],
//                        'tags' => $attrs['tags'],
//                        'status_attr' => (Boolean) $attrs['status'],
//                        'sortvalueitem' => $i,
//                        'onstatus' => true,
//                        'active' => false,
//                        'block' => false,
//                    ];
//
//
//                    $i++;
//                }
//            } else {
//                $tempalias = 'sizesattraliasnullvalue';
//                if ($item['color']) {
//                    $tempalias = 'colorsattraliasnullvalue';
//                }
//                $newattrs[] = [
//                    'alias_attrs' => $tempalias,
//                    'title' => $tempalias,
//                    'tags' => "",
//                    'status_attr' => false,
//                    'sortvalueitem' => 0,
//                    'onstatus' => false,
//                    'active' => false,
//                    'block' => false,
//                ];
//            }
//            unset($item['attrs']);
//            $item['attrs'] = $newattrs;
//            $data[] = $item;
//        }
        // debug($data, 1);
        //  debug($datatempgr, 1);
        //   $json = json_encode($datatempgr, JSON_UNESCAPED_UNICODE);
        //     exit($json);
//===========================================
//==========product hit===================
        $json_category_new_ids = '{"1":"5d6d301b9cd4873e9439fe48","2":"5d6d301b9cd4873e9439fe43","3":"5d6d301b9cd4873e9439fe4a","4":"5d6d301b9cd4873e9439fe42","5":"5d6d301b9cd4873e9439fe49","6":"5d6d301b9cd4873e9439fe44","7":"5d6d301b9cd4873e9439fe4e","8":"5d6d301b9cd4873e9439fe51","10":"5d6d301b9cd4873e9439fe45","12":"5d6d301b9cd4873e9439fe4b","13":"5d6d301b9cd4873e9439fe4f","14":"5d6d301b9cd4873e9439fe46","16":"5d6d301b9cd4873e9439fe50","19":"5d6d301b9cd4873e9439fe4c","20":"5d6d301b9cd4873e9439fe47","21":"5d6d301b9cd4873e9439fe4d","22":"5d6d301b9cd4873e9439fe53","23":"5d6d301b9cd4873e9439fe52"}';
        $arr_category_new_ids = json_decode($json_category_new_ids, true);
//
//
//        $product_ids = App::$app->getProperty('product_ids');
//        // $hits = Product::getAllProducts($product_ids, '1', false, 0, 1, false, false);
//
//        $p_model = new Product();
//        //$products = $p_model->setProducts($hits);
//        //  $products = $p_model->setRezultProducts([34]);
//        $productids = App::$app->getProperty('product_ids');
//        $productids = explode(',', $productids);
//
//        $productlist = [];
//====================0-139
        $start = 0;
        $end = 10;

//
//        $input_json = '[{"_id":"5d72cac869717b3074ac46c5","old_id":34},{"_id":"5d72cb4769717b3074ac4726","old_id":35},{"_id":"5d72cb6d69717b3074ac4757","old_id":36},{"_id":"5d72cb7c69717b3074ac47b7","old_id":37},{"_id":"5d72cb8869717b3074ac47e8","old_id":38},{"_id":"5d72cc3569717b3074ac4831","old_id":39},{"_id":"5d72cc4269717b3074ac4862","old_id":40},{"_id":"5d72cc4e69717b3074ac4887","old_id":41},{"_id":"5d72cc5c69717b3074ac48c4","old_id":44},{"_id":"5d72cc6569717b3074ac4931","old_id":46},{"_id":"5d72cd5d844e861d84ca0365","old_id":47},{"_id":"5d72cd7f844e861d84ca03df","old_id":48},{"_id":"5d72cd9f844e861d84ca0444","old_id":49},{"_id":"5d72ce984dda0136045a2ddc","old_id":50},{"_id":"5d72cea44dda0136045a2e0d","old_id":51},{"_id":"5d72ceb04dda0136045a2e5f","old_id":52},{"_id":"5d72ceb84dda0136045a2e8e","old_id":53},{"_id":"5d72cec04dda0136045a2eaf","old_id":54},{"_id":"5d72ced34dda0136045a2ec5","old_id":55},{"_id":"5d72cedb4dda0136045a2ee4","old_id":56},{"_id":"5d72cee44dda0136045a2f19","old_id":57},{"_id":"5d7380b04dda0136045a2f58","old_id":58},{"_id":"5d7380b94dda0136045a2f86","old_id":59},{"_id":"5d7380c24dda0136045a2fbb","old_id":60},{"_id":"5d7380cc4dda0136045a2ff0","old_id":61},{"_id":"5d7380d34dda0136045a3025","old_id":62},{"_id":"5d7380e14dda0136045a303c","old_id":63},{"_id":"5d7380e84dda0136045a305f","old_id":64},{"_id":"5d7380ef4dda0136045a309c","old_id":65},{"_id":"5d7380f74dda0136045a30b3","old_id":66},{"_id":"5d7381014dda0136045a30be","old_id":67},{"_id":"5d73810a4dda0136045a30d7","old_id":68},{"_id":"5d7381124dda0136045a3114","old_id":69},{"_id":"5d73811c4dda0136045a312d","old_id":71},{"_id":"5d7381234dda0136045a318c","old_id":72},{"_id":"5d73812b4dda0136045a31c3","old_id":73},{"_id":"5d7381334dda0136045a31ec","old_id":75},{"_id":"5d73813e4dda0136045a3234","old_id":76},{"_id":"5d73814a4dda0136045a325f","old_id":78},{"_id":"5d7381524dda0136045a329c","old_id":79},{"_id":"5d7381594dda0136045a32d2","old_id":80},{"_id":"5d7381604dda0136045a32df","old_id":81},{"_id":"5d7381684dda0136045a32eb","old_id":82},{"_id":"5d73816f4dda0136045a32f8","old_id":83},{"_id":"5d7381774dda0136045a331d","old_id":84},{"_id":"5d7381814dda0136045a334a","old_id":85},{"_id":"5d7381894dda0136045a3369","old_id":86},{"_id":"5d7381924dda0136045a3375","old_id":87},{"_id":"5d73819f4dda0136045a3380","old_id":88},{"_id":"5d7381a64dda0136045a3397","old_id":89},{"_id":"5d7381ac4dda0136045a33b9","old_id":91},{"_id":"5d7381b44dda0136045a33ea","old_id":92},{"_id":"5d7381ba4dda0136045a33f6","old_id":93},{"_id":"5d7381c64dda0136045a340e","old_id":94},{"_id":"5d7381ce4dda0136045a341a","old_id":95},{"_id":"5d7381d54dda0136045a3428","old_id":96},{"_id":"5d7381db4dda0136045a343f","old_id":97},{"_id":"5d7381e24dda0136045a344b","old_id":98},{"_id":"5d73823f4dda0136045a3461","old_id":100},{"_id":"5d7382464dda0136045a348d","old_id":101},{"_id":"5d73824c4dda0136045a34a5","old_id":102},{"_id":"5d7382584dda0136045a34be","old_id":103},{"_id":"5d73825f4dda0136045a34fb","old_id":104},{"_id":"5d7382664dda0136045a3537","old_id":105},{"_id":"5d73826d4dda0136045a3550","old_id":106},{"_id":"5d7382754dda0136045a3569","old_id":107},{"_id":"5d73827c4dda0136045a358d","old_id":108},{"_id":"5d7382f54dda0136045a35be","old_id":109},{"_id":"5d7383554dda0136045a3608","old_id":110},{"_id":"5d73835c4dda0136045a3623","old_id":112},{"_id":"5d7383634dda0136045a3630","old_id":113},{"_id":"5d73836b4dda0136045a363d","old_id":114},{"_id":"5d7383724dda0136045a3650","old_id":115},{"_id":"5d73837a4dda0136045a365d","old_id":116},{"_id":"5d7383844dda0136045a3685","old_id":117},{"_id":"5d73838a4dda0136045a36c7","old_id":118},{"_id":"5d7383914dda0136045a36f3","old_id":119},{"_id":"5d7383984dda0136045a3719","old_id":120},{"_id":"5d7383a04dda0136045a373c","old_id":121},{"_id":"5d7383a84dda0136045a376b","old_id":124},{"_id":"5d7383af4dda0136045a379f","old_id":125},{"_id":"5d7383b64dda0136045a37e4","old_id":126},{"_id":"5d7383be4dda0136045a37fd","old_id":127},{"_id":"5d7383c54dda0136045a3822","old_id":128},{"_id":"5d7383cd4dda0136045a383a","old_id":129},{"_id":"5d7383d74dda0136045a3853","old_id":130},{"_id":"5d7383df4dda0136045a3880","old_id":131},{"_id":"5d7383e94dda0136045a3897","old_id":132},{"_id":"5d7383f24dda0136045a38ae","old_id":133},{"_id":"5d7383f84dda0136045a38d0","old_id":134},{"_id":"5d7383fe4dda0136045a38e7","old_id":135},{"_id":"5d7384054dda0136045a38f4","old_id":136},{"_id":"5d73840d4dda0136045a3909","old_id":137},{"_id":"5d7384144dda0136045a3914","old_id":138},{"_id":"5d73841d4dda0136045a3938","old_id":139},{"_id":"5d751a77c8aa9c3cdc67b989","old_id":45},{"_id":"5d751a87c8aa9c3cdc67b995","old_id":70},{"_id":"5d751a95c8aa9c3cdc67b9c3","old_id":90},{"_id":"5d751aa0c8aa9c3cdc67b9dc","old_id":99}]';
//        $saved_ids = json_decode($input_json, true);
//        $saved_ids_data = [];
//        foreach ($saved_ids as $vvv) {
//            $saved_ids_data[$vvv['old_id']] = $vvv['_id'];
//        }
//        $no_saved = [];
//        foreach ($productids as $vv) {
//            if (!isset($saved_ids_data[$vv])) {
//                $no_saved[] = $vv;
//            }
//        }
//        debug($no_saved, 1);
//echo count($productids);
//die;
        $garrent = 0;
//=====================
        $productids = App::$app->getProperty('product_ids');
        $productids = explode(',', $productids);
        $p_model = new Product();
        //  debug($productids, 1);
//        foreach ($productids as $k => $v) {
//            if ($k >= $start && $k <= $end) {
//                $productlist[] = $v;
//            }
//        }
        //    debug($productlist, 1);
//       [0] => 45
//    [1] => 70
//    [2] => 90
//    [3] => 99     
     //   $productlist[] = $productids[$garrent];
     $productlist[] = 153;
        // debug($productlist, 1);
        // $productlist = [99];

        $forFilter = 0;
        $forcontent = 0;
        $content = 1;
        $debug = 0;

        if ($forFilter) {

// для фильтра =============================
            foreach ($productlist as $productitem) {

                $product = $p_model->setProduct(false, [], false, $productitem);
                $product['content'] = '';

                if (!isset($product['id'])) {
                    continue;
                }

                //     echo $product['id'].'<br>';
                //   unset($product['price']);
                $product['price'] = (int) $product['price'];
                unset($product['old_price']);
                unset($product['seolink']);
                unset($product['sizesgroup_id']);
                unset($product['img']);
                unset($product['srcset']);
                unset($product['imgsizes']);
                unset($product['modification_id']);
                unset($product['color_title']);

                unset($product['sku']);
                unset($product['color_default']);
                unset($product['color_default_base']);
                unset($product['brand_id']);
                unset($product['gender']);
                unset($product['link']);
                //   $product['link'] = $product['alias'];
                unset($product['color_rustitle']);
                unset($product['imgto']);
                unset($product['imgtoimg']);
                unset($product['imgtoimg_srcset']);
                unset($product['imgtoimg_imgsizes']);
                unset($product['img_main']);
                unset($product['product_model']);
                unset($product['promo_id']);
                unset($product['related']);
                unset($product['img_thumb']);
                unset($product['imgtoimg_img_main']);
                unset($product['imgtoimg_img_thumb']);
                unset($product['bagdeproduct_id']);
                unset($product['bagdefixed']);
                unset($product['bagderedcolor']);
                unset($product['bagdetitle']);
                unset($product['sizes_select']);
                unset($product['meta_title']);
                unset($product['meta_description']);
                unset($product['meta_keywords']);
                unset($product['content']);
                unset($product['colors']);
                unset($product['colors_rus']);
                //   $product['sizes_select'] = '';
                unset($product['addCard']);
                //   $product['cardData'] = [];
                //      $product['category_id'] = \R::getCol('SELECT `category_id` FROM `categoryproduct` WHERE `product_id` = ?', [$product['id']]);

                unset($product['category_id']);

                $arr_all = explode(' ', $product['update_at']);
                $arr = explode('-', $arr_all[0]);
                $arr_time = explode(':', $arr_all[1]);
                $product['update_at'] = mktime($arr_time[0], $arr_time[1], $arr_time[2], $arr[1], $arr[2], $arr[0]);

                //    unset($product['update_at']);
                // debug($arr);
                // debug($arr_time, 1);
                $imgproperty = \ishop\App::$app->getProperty('img_property');
                $level2 = [];
                foreach ($product['sizesList'] as $id => &$size) {
                    unset($size['linkSizes']);
                    unset($size['description']);
                    unset($size['modifregistr_id']);
                    //  $size['selected'] = false;
                    //   $size['alias'] = $id;

                    unset($size['selected']);
                    $size = $size['title'];
                    $level2[] = (string) $id;
                }

                foreach ($product['modification'] as $alias => &$item) {
                    // $item['alias'] = $alias;
                    $item['linkColor'] = '';
                    unset($item['modification_id']);
                    // $item['img_thumb'] = 'https://demo-shop.example' . $imgproperty['300']['path'] . $item['img'];
                    $result = '';
                    $item['price'] = 0;
                    $item['old_price'] = 0;
                    foreach ($imgproperty as $k => $v) {
                        if ($v['superbig'])
                            continue;

                        $result .= 'https://demo-shop.example' . $v['path'] . $item['img'] . ' ' . $k . 'w,';
                    }
                    $result = rtrim($result, ',');
                    if ($result) {
                        //    $item['srcset'] = rtrim($result, ',');
                        //    $item['imgsizes'] = ' sizes="100vw"';
                    }


                    unset($item['imgto']);
                    unset($item['color_id']);
                    unset($item['linkColor']);
                    unset($item['bagdeproduct_id']);


                    unset($item['title']);
                    unset($item['rustitle']);
                    unset($item['img']);
                    unset($item['price']);
                    unset($item['old_price']);


                    //  $item['bagdeproduct'] = '';
                    $item['selected'] = false;
                    unset($item['selected']);
                    $item = $level2;
                }
                unset($product['sizesList']);

                $filter = new \app\widgets\filter\Filter([
                    'ids' => $product['id'],
                    'check' => false,
                    'oneproduct' => true,
                        // 'status' => false
                ]);

                $filterData = $filter->filter;
                $product['filter'] = [];
                foreach ($filterData as $filterItem) {
                    if (!$filterItem['filter']) {
                        continue;
                    }

                    foreach ($filterItem['attrs'] as $alias => $attr) {
                        if (isset($attr['on'])) {
                            if ($attr['on']) {
                                $product['filter'][$filterItem['alias']][] = (string) $alias;
                            }
                        }
                    }
                }

                //  debug($filterData, 1);
                //    unset($product['filter']);
                unset($product['cardData']);
                unset($product['id']);
                $product['level1'] = $product['modification'];
                unset($product['modification']);

                $alias = $product['alias'];
                unset($product['alias']);
                $products[$alias] = $product;
            }
        } elseif ($forcontent) {
            // для контента =============================

            foreach ($productlist as $productitem) {


                $product = $p_model->setProduct(false, [], false, $productitem);
                if (!isset($product['id'])) {
                    continue;
                }
                Product::addDannProduct($product);

                $contData['meta_title'] = $product['meta_title'];
                $contData['meta_description'] = $product['meta_description'];
                $contData['meta_keywords'] = $product['meta_keywords'];
                if (!$contData['meta_title'])
                    $contData['meta_title'] = App::$app->getProperty('product_meta_title');
                if (!$contData['meta_description'])
                    $contData['meta_description'] = App::$app->getProperty('product_meta_description');
                if (!$contData['meta_keywords'])
                    $contData['meta_keywords'] = App::$app->getProperty('product_meta_keywords');

                $contData['content'] = $product['content'];

                $filterContent = new \app\widgets\filter\Filter([
                    'ids' => $product['id'],
                    'tpl' => WWW . '/filter/oneproduct_filter_tpl.php',
                    'oneproduct' => true,
                ]);
                $patternData = $filterContent->getPatternArray();
                Product::addProductPattern($product, $patternData);
                $contData = prRep($contData, $patternData);
                $contData['filter'] = $filterContent->getHtml();
                $product['meta_title'] = $contData['meta_title'];
                $product['meta_description'] = $contData['meta_description'];
                $product['meta_keywords'] = $contData['meta_keywords'];
                $product['content'] = $contData['content'] . $contData['filter'];



                // debug($product, 1);
                //     echo $product['id'].'<br>';
                unset($product['price']);
                unset($product['old_price']);
                unset($product['seolink']);
                unset($product['sizesgroup_id']);
                unset($product['img']);
                unset($product['srcset']);
                unset($product['imgsizes']);
                unset($product['modification_id']);
                unset($product['color_title']);

                $product['link'] = $product['alias'];
                unset($product['color_rustitle']);
                unset($product['imgto']);
                unset($product['imgtoimg']);
                unset($product['imgtoimg_srcset']);
                unset($product['imgtoimg_imgsizes']);
                unset($product['img_main']);
                unset($product['product_model']);
                unset($product['promo_id']);
                unset($product['related']);
                unset($product['img_thumb']);
                unset($product['imgtoimg_img_main']);
                unset($product['imgtoimg_img_thumb']);
                unset($product['bagdeproduct_id']);
                unset($product['bagdefixed']);
                unset($product['bagderedcolor']);
                unset($product['bagdetitle']);
                unset($product['sizes_select']);


                unset($product['colors']);
                unset($product['colors_rus']);
                //   $product['sizes_select'] = '';
                unset($product['addCard']);
                //   $product['cardData'] = [];
                //      $product['category_id'] = \R::getCol('SELECT `category_id` FROM `categoryproduct` WHERE `product_id` = ?', [$product['id']]);

                unset($product['category_id']);

                unset($product['title']);
                //   unset($product['alias']);
                unset($product['sku']);
                unset($product['color_default']);
                unset($product['color_default_base']);
                unset($product['brand_id']);
                unset($product['gender']);
                unset($product['link']);






                unset($product['update_at']);
                unset($product['ajax_link']);


                foreach ($product['modification'] as $alias => &$item) {
//$item = [];
                    $sql_part = 'SELECT img FROM gallery WHERE modification_id = ? ORDER BY main ASC, sortvalue';
                    $item = \R::getCol($sql_part, [$item['modification_id']]);
                }
                unset($product['sizesList']);

                unset($product['filter']);
                unset($product['cardData']);
                unset($product['id']);
                $product['level1_data'] = $product['modification'];
                unset($product['modification']);
                unset($product['gallery']);

                $alias = $product['alias'];
                unset($product['alias']);
                $products[$alias] = $product;
            }
        } else {

            $sizesgroup = [
                '2' => '5d6668a694c9d40cd87424b7',
                '3' => '5d6668a694c9d40cd87424b8',
                '4' => '5d6668a694c9d40cd87424b9',
                '5' => '5d6668a694c9d40cd87424ba',
                '6' => '5d6668a694c9d40cd87424bb'
            ];

            foreach ($productlist as $productitem) {


                $product = $p_model->setProduct(false, [], false, $productitem);
                // $product['content'] = '';

                if (!$content) {
                    $product['content'] = '';
                }

                if (!isset($product['id'])) {
                    continue;
                }
                $product['old_id'] = $product['id'];
                //     echo $product['id'].'<br>';
                //   unset($product['price']);
                //    unset($product['old_price']);
                //  unset($product['seolink']);
                //    unset($product['sizesgroup_id']);
                // $product['seolink'] = '';
                unset($product['seolink']);
                unset($product['img']);
                unset($product['srcset']);
                unset($product['imgsizes']);
                unset($product['modification_id']);
                unset($product['color_title']);
                $product['status'] = TRUE;
                $product['hit'] = FALSE;
                $product['cart_on'] = FALSE;

                $product['sizesgroup_id'] = $sizesgroup[$product['sizesgroup_id']];
                unset($product['color_default_base']);
                unset($product['link']);
                unset($product['color_rustitle']);
                unset($product['imgto']);
                unset($product['imgtoimg']);
                unset($product['imgtoimg_srcset']);
                unset($product['imgtoimg_imgsizes']);
                unset($product['img_main']);
                //   unset($product['product_model']);
                unset($product['promo_id']);
                $product['related'] = \R::getCell('SELECT `alias` FROM `product` WHERE `id` = ?', [$product['related']]);
                //  unset($product['related']);
                unset($product['img_thumb']);
                unset($product['imgtoimg_img_main']);
                unset($product['imgtoimg_img_thumb']);
                unset($product['bagdeproduct_id']);
                unset($product['bagdefixed']);
                unset($product['bagderedcolor']);
                unset($product['bagdetitle']);
                unset($product['sizes_select']);
                $product['meta']['title'] = $product['meta_title'];
                $product['meta']['description'] = $product['meta_description'];
                $product['meta']['keywords'] = $product['meta_keywords'];
                $product['brand_id'] = '5d682010d5dc463a4425e858';
                unset($product['meta_title']);
                unset($product['meta_description']);
                unset($product['meta_keywords']);
                //  unset($product['content']);
                unset($product['colors']);
                unset($product['colors_rus']);
                //   $product['sizes_select'] = '';
                unset($product['addCard']);
                //   $product['cardData'] = [];
                //      $product['category_id'] = \R::getCol('SELECT `category_id` FROM `categoryproduct` WHERE `product_id` = ?', [$product['id']]);
                //  unset($product['category_id']);
                $product['category_id'] = $arr_category_new_ids[$product['category_id']];



                $category_ids = \R::getCol('SELECT `category_id` FROM `categoryproduct` WHERE `product_id` = ?', [$product['old_id']]);
                foreach ($category_ids as $value) {
                    $product['category_ids'][] = $arr_category_new_ids[$value];
                }
                $arr_all = explode(' ', $product['update_at']);
                $arr = explode('-', $arr_all[0]);
                $arr_time = explode(':', $arr_all[1]);
                $product['update_at'] = mktime($arr_time[0], $arr_time[1], $arr_time[2], $arr[1], $arr[2], $arr[0]);

                unset($product['update_at']);

                // debug($arr);
                // debug($arr_time, 1);
                $imgproperty = \ishop\App::$app->getProperty('img_property');
                //       debug($product['sizesList'], 1);

                foreach ($product['sizesList'] as $id => &$size) {
                    unset($size['linkSizes']);
                    unset($size['description']);
                    unset($size['modifregistr_id']);
                    //  $size['selected'] = false;
                    //   $size['alias'] = $id;
                    $sizesList[] = [
                        'level2_alias' => $id,
                        'amount' => 100
                    ];
                    unset($size['selected']);
                    $size['title'] = $size['title'];
                    $size['amount'] = 100;
                }

                foreach ($product['modification'] as $alias => &$item) {


                    // $item['alias'] = $alias;
                    $item['linkColor'] = '';

                    // $item['img_thumb'] = 'https://demo-shop.example' . $imgproperty['300']['path'] . $item['img'];
                    $result = '';
                    $item['price'] = 0;
                    $item['old_price'] = 0;
                    foreach ($imgproperty as $k => $v) {
                        if ($v['superbig'])
                            continue;

                        $result .= 'https://demo-shop.example' . $v['path'] . $item['img'] . ' ' . $k . 'w,';
                    }

                    $sql_part = 'SELECT img, main, sortvalue AS sortvalue_gallery FROM gallery WHERE modification_id = ? ORDER BY main DESC, sortvalue';
                    $gallery = \R::getAll($sql_part, [$item['modification_id']]);
                    $item['gallery'] = $gallery;
                    unset($item['modification_id']);
                    $result = rtrim($result, ',');
                    if ($result) {
                        //    $item['srcset'] = rtrim($result, ',');
                        //    $item['imgsizes'] = ' sizes="100vw"';
                    }

                    unset($item['img']);
                    unset($item['imgto']);
                    unset($item['color_id']);
                    unset($item['linkColor']);
                    unset($item['bagdeproduct_id']);
                    //  $item['bagdeproduct'] = '';
                    $item['selected'] = false;
                    unset($item['selected']);
                    //   $item['level2'] = $product['sizesList'];
                    $item['level2'] = $sizesList;


                    $level1[] = [
                        'level1_alias' => $alias,
                        'level1_status' => TRUE,
                        'price' => $item['price'],
                        'old_price' => $item['old_price'],
                        //  'bagde_id' => NULL,
                        'gallery' => $gallery,
                        'level2' => $sizesList
                    ];
                }
                unset($product['sizesList']);

                $filter = new \app\widgets\filter\Filter([
                    'ids' => $product['id'],
                    'check' => false,
                    'oneproduct' => true,
                        // 'status' => false
                ]);

                $filterData = $filter->filter;
                $product['filter'] = [];
                foreach ($filterData as $filterItem) {
//                    if (!$filterItem['filter']) {
//                        continue;
//                    }

                    foreach ($filterItem['attrs'] as $alias => $attr) {
                        if (isset($attr['on'])) {
                            if ($attr['on']) {
                                //  $product['filter'][$filterItem['alias']][] = $alias;
                                $product['filter'][] = ($alias + 300);
                            }
                        }
                    }
                }

                //  debug($filterData, 1);
                //  unset($product['filter']);
                unset($product['cardData']);
                unset($product['id']);
                unset($product['old_id']);

                //   $product['level1'] = $product['modification'];
                $product['level1_data'] = $level1;
                unset($product['modification']);

                $alias = $product['alias'];
                // $product['_id'] = $product['alias'];
                //  unset($product['alias']);
                //  unset($product['alias']);
                // $products[$alias] = $product;
                $products[] = $product;
            }
        }
        $data = $products;
        if ($debug) {
            debug($products, 1);
        }
        //   
//===========================================
        //debug($data, 1);
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        // debug($json, 1);
        $this->answer = $json;
    }

    protected function getTree($data) {
        $tree = [];

        $parent_id = '';
        foreach ($data as $id => &$node) {

//            if ($node['cat_default']) {
////                if ($this->default_view)
////                    $tree[$id] = &$node;
//                $parent_id = $id;
//                continue;
//            }

            if (!$node['parent_id'] || $parent_id == $node['parent_id']) {
                //$tree[$id] = &$node;
                $tree = &$node;
            } else {
                // $data[$node['parent_id']]['childs'][$id] = &$node;

                $data[$node['parent_id']]['childs'][] = &$node;
//                if (!isset($node['childs'])) {
//                    $node['childs'] = [];
//                }
            }
        }

        return $tree;
    }

}
