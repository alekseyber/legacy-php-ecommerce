<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;
use app\models\admin\Gallery;

class ParserController extends AppController {

    public function indexAction() {


        
        die;
        
        $id = $_REQUEST['id'] ?? 0;
        $id = (int) $id;

        if (!$id) {
            die;
        }

////       ===========================Скачивает фото товаров=======================================
//        set_time_limit(600);
//        $limit = 100;
//        $timestart = date('l jS \of F Y h:i:s A');
//        $timestartsec = time();

        $sql = 'SELECT `modification`.`id`, `color`.`alias`, `product`.`sku`';
        $sql .= '  FROM `product` JOIN `modification` ON `product`.`id` = `modification`.`product_id`';
        $sql .= '  JOIN `color` ON `modification`.`color_id` = `color`.`id`';
        $sql .= '  WHERE `product`.`id` = ?';
        $data = \R::getAssoc($sql, [$id]);
        $uploaddir = WWW . \ishop\App::$app->getProperty('img_parsimg_path');
        $arr_rezult = [];
        if ($data) {
            foreach ($data as $id => $item) {
                $color = mb_strtoupper($item['alias']);
                for ($i = 1; $i <= 6; $i++) {                    
                    $fileurl = 'https://www.ugg.com/dw/image/v2/AAFF_PRD/on/demandware.static/-/Sites-masterCatalogUgg/default/dwb9ac1ca1/images/grey/' . $item['sku'] . '-' . $color . "_$i.jpg?sw=1500";
                    $name = md5(uniqid(rand(), true) . time()) . ".jpg";
                    $file = $uploaddir . $name;
                    $rezult = file_get_img_file_curl($fileurl, $file);
                    $arr_rezult[$id][$i] = [
                        'rezult' => $rezult,
                        'file' => $file,
                        'color' => $color,
                        'fileurl' => $fileurl
                    ];
                    //sleep(1);
                }
            }
        }
        debug($arr_rezult, 1);
//        $col = 1;
//        if ($data) {
//            foreach ($data as $val) {
//                sleep(1);
//                $url = 'https://sunglasses-online.ru/products/' . $val['alias'];
//                echo $url . '<br>';
//                $result = file_get_content_curl($url);
//                $pq = \phpquery::newDocument($result);
//                $imgs = $pq->find('.product-media-slider__slide .fancy-img');
//
//                $i = 0;
//                $q = 0;
//                $modification_id = $val['modification_id'];
//                $mainimg = '';
//                $rezultimg = [];
//                foreach ($imgs as $img) {
//                    $pqimg = pq($img);
//                    $fileurl = ltrim($pqimg->attr('href'), '//');
//                    $name = '';
//                    $uploaddir = WWW . '/' . \ishop\App::$app->getProperty('img_parsimg_path');
//                    $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $fileurl));
//                    if ($ext != 'jpg') {
//                        if ($ext != 'png') {
//                            $ext = 'jpg';
//                        }
//                    }
//                    $name = md5(uniqid(rand(), true) . time()) . ".$ext";
//                    $file = $uploaddir . $name;
//                    $rezult = file_get_img_file_curl($fileurl, $file);
//                    if (file_exists($file)) {
//                        echo $name . '<br>';
//                        if ($i == 0) {
//                            $rezultimg[$i]['main'] = 1;
//                            $mainimg = $name;
//                        } else {
//                            $rezultimg[$i]['main'] = 0;
//                        }
//                        $rezultimg[$i]['img'] = $name;
//                        $i++;
//                    }
//                    $q++;
//                }
//
//                if ($i == $q) {
//                    echo "--УСПЕХ--$col-<br> записано $i из $q";
//                    foreach ($rezultimg as $k => $v) {
//                        $tbl = \R::dispense('gallery');
//                        $tbl->modification_id = $modification_id;
//                        $tbl->img = $v['img'];
//                        $tbl->main = $v['main'];
//                        $tbl->sortvalue = $k;
//                        \R::store($tbl);
//                    }
//                    $bean = \R::load('modification', $modification_id);
//                    $bean->img = $mainimg;
//                    \R::store($bean);
//                } else {
//                    echo "--ОТКАЗ--$col-<br> записано $i из $q";
//                }
//                echo '=================<br>';
//                $col++;
//            }
//        } else {
//            echo "-----НЕТ ТОВАРОВ БЕЗ ФОТО------<br>";
//        }
//        echo "--Время начала работы: $timestart<br>";
//        $timeend = date('l jS \of F Y h:i:s A');
//        echo "--Время окончания работы: $timeend<br>";
//        $timeendsec = time();
//        $ttt = $timestartsec - $timeendsec;
//        echo "--Время работы: $ttt - сек<br>";
//        if ($col) {
//            $col--;
//        }
//        echo "--Обработано: $col - товаров<br>";
//       =====================================================
    }


}
