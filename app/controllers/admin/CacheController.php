<?php

namespace app\controllers\admin;

use ishop\Cache;
use ishop\App;
use app\models\admin\Modification;

class CacheController extends AppController {

    public function indexAction() {
        $cache = Cache::instance();
        $keys = $cache->getAllKey();

        $this->setMeta('Очистка кэша');
        $this->set(compact('keys'));
    }

    public function deleteAction() {
        $key = isset($_GET['key']) ? $_GET['key'] : null;
        $cache = Cache::instance();

// 1       'cachetime_filter' => 10,
// 1       'cachetime_category' => 10,
// 1       'cachetime_productids' => 30,
//  1      'cachetime_getparams' => 30,
//   2     'cachetime_menu' => 30,
//   1     'cachetime_sort' => 30,
//   1     'cachetime_top_slider' => 30,
//    1    'cachetime_maincontent' => 30,
//     1   'cachetime_brand' => 30,
//    1    'cachetime_sizegroup' => 30,
//     1   'cachetime_promo' => 30,
//    1    'cachetime_recomacces' => 30,
//        'cachetime_product_content' => 3600,
        $rezult = false;
        switch ($key) {
            case 'category':
                $cache->delete('cats');
                $cache->delete('ishop_menu');
                $cache->delete('top_menu');
                $cache->delete('menu_tree');
                $rezult = true;
                break;
            case 'filter':
                //$cache->delete('filter_group');
                //$cache->delete('filter_attrs');
                $cache->deleteMask('filter');
                $rezult = true;
                break;
            case 'product_ids':
                $cache->delete('product_ids');
                $rezult = true;
                break;
            case 'getparams':
                $cache->deleteMask('paramstable');
                $rezult = true;
                break;
            case 'sort':
                $cache->deleteMask('sort');
                $rezult = true;
                break;
            case 'top_slider':
                $cache->deleteMask('slider');
                $rezult = true;
                break;
            case 'maincontent':
                $cache->deleteMask('maincontent');
                $rezult = true;
                break;
            case 'brand':
                $cache->deleteMask('brand');
                $rezult = true;
                break;
            case 'sizegroup':
                $cache->deleteMask('sizesgroup');
                $rezult = true;
                break;
            case 'promo':
                $cache->deleteMask('promo');
                $rezult = true;
                break;
            case 'recomacces':
                $cache->delete('recomacces');
                $rezult = true;
                break;
            case 'product_content':
                $cache->deleteMask('product');
                $rezult = true;
                break;
            case 'all_cache':
                $cache->deleteAllMask();
                $rezult = true;
                break;
        }

        if ($this->isAjax()) {
            $res = ['succes' => $rezult, 'title' => 'Выбранный кэш удален', 'message' => $key . ' кэш удален'];
            exit(json_encode($res));
        } else {
            $_SESSION['success'] = 'Выбранный кэш удален';
            redirect();
        }
    }

    public function clearimgsAction() {
        $pattern = '/\.[a-zA-Z]{1,4}$/';
        $params = App::$app->getProperty('img_property');
        $imgIndex = [];
        $countDel = 0;
        $countForDel = 0;

        $imgs = \R::getCol('SELECT `img` FROM `gallery`');

        $countImgs = count($imgs);

        foreach ($imgs as $img) {
            $key = preg_replace($pattern, '', $img);
            $imgIndex[$key] = 1;
        }

        foreach ($params as $item) {
            $dir = WWW . $item['path'];
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && $file !== 'no_image.jpg' && $file !== 'index.html') {
                    $key = preg_replace($pattern, '', $file);
                    if (!isset($imgIndex[$key])) {
                        $countForDel++;
                        $fullPath = $dir . $file;
                        $rezult = unlink($fullPath);
                        if ($rezult) {
                            $countDel++;
                        }
                    }
                }
            }
        }

        $_SESSION['success'] = "Используется фото: $countImgs, найдено не использованных файлов: $countForDel, удалено: $countDel";
        redirect(ADMIN . '/cache');
    }

    public function updatewebpAction() {
        set_time_limit(0);
        $timestartsec = time();

        $params = [];
        $countDel = 0;
        $countForDel = 0;
        $countAdd = 0;

        foreach (App::$app->getProperty('img_property') as $item) {
            $params[] = WWW . $item['path'];
        }
        $img_category_property = App::$app->getProperty('img_category_property');
        $img_category_path = null;
        if ($img_category_property) {
            $key = array_key_first($img_category_property);
            $img_category_path = $img_category_property[$key]['path'];
            $params[] = WWW . $img_category_path;
        }
        $img_thumb_category_property = App::$app->getProperty('img_thumb_category_property');
        if ($img_thumb_category_property) {
            $key = array_key_first($img_thumb_category_property);
            $img_thumb_category_path = $img_thumb_category_property[$key]['path'];
            if ($img_category_path !== $img_thumb_category_path) {
                $params[] = WWW . $img_thumb_category_path;
            }
        }
        $img_news_property = App::$app->getProperty('img_news_property');
        if ($img_news_property) {
            $key = array_key_first($img_news_property);
            $img_news_property_path = $img_news_property[$key]['path'];
            $params[] = WWW . $img_news_property_path;
        }

        $pattern = '/\.webp$/';

        debug($params, 1);
//==================================================
        foreach ($params as $dir) {

            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && $file !== 'index.html') {

                    if (preg_match($pattern, $file)) {
                        $countForDel++;
                        $fullPath = $dir . $file;
                        $rezult = unlink($fullPath);
                        if ($rezult) {
                            $countDel++;
                        }
                    }
                }
            }
        }

        $patternimg = "/\.(png|jpg|gif)$/i";
        foreach ($params as $dir) {
            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && $file !== 'index.html') {

                    if (preg_match($patternimg, $file)) {

                        $fullPath = $dir . $file;
                        $rezult = Modification::greatWebp($fullPath);

                        if ($rezult) {
                            $countAdd++;
                        }
                    }
                }
            }
        }

        $timemin = (time() - $timestartsec) / 60;
        $_SESSION['success'] = "Найдено webp файлов: $countForDel, удалено webp файлов: $countDel, создано webp файлов: $countAdd, время выполнения: $timemin минут.";
        redirect(ADMIN . '/cache');
    }

}
