<?php

namespace app\models;

use ishop\libs\URL;
use ishop\Link;
use ishop\App;

class Product extends AppModel {

    public function setRecentlyViewed($id) {
        $recentlyViewed = $this->getAllRecentlyViewed();
        if (!$recentlyViewed) {
            setcookie('recentlyViewed', $id, time() + 3600 * 24, '/');
        } else {
            $recentlyViewed = explode('.', $recentlyViewed);
            if (!in_array($id, $recentlyViewed)) {
                $recentlyViewed[] = $id;
                $recentlyViewed = implode('.', $recentlyViewed);
                setcookie('recentlyViewed', $recentlyViewed, time() + 3600 * 24, '/');
            }
        }
    }

    public function getRecentlyViewed() {
        if (!empty($_COOKIE['recentlyViewed'])) {
            $recentlyViewed = $_COOKIE['recentlyViewed'];
            $recentlyViewed = explode('.', $recentlyViewed);
            return array_slice($recentlyViewed, -4);
        }
        return false;
    }

    public function getAllRecentlyViewed() {
        if (!empty($_COOKIE['recentlyViewed'])) {
            return $_COOKIE['recentlyViewed'];
        }
        return false;
    }

    public function setProduct($alias = '', $getArr = [], $uri = '', $id = 0, $fullModel = false, $addGetLink = false) {
        if (!$alias && !$id)
            return false;
        $color = '';
        $sizes = '';

        if (isset($getArr['color']) && $getArr['color']) {
            $color = $getArr['color'];
        }

        if (isset($getArr['sizes']) && $getArr['sizes']) {
            $sizes = $getArr['sizes'];
        }
        $sql_part = 'SELECT id, title, alias, seolink, sku, price, old_price, color_default, category_id, color_default AS color_default_base,';
        $sql_part .= ' sizesgroup_id, promo_id, related, brand_id, gender, product_model, meta_title, meta_description, meta_keywords, content, update_at FROM product';
        if ($alias) {
            $sql_part .= ' WHERE alias = ? AND status = "1" LIMIT 1';
            $product = \R::getRow($sql_part, [$alias]);
        }
        if ($id) {
            $sql_part .= ' WHERE id = ? AND status = "1" LIMIT 1';
            $product = \R::getRow($sql_part, [$id]);
            if (!$product)
                return false;
            $uri = Link::getLinkProduct($product);
            // $uri = $product ? 'product/' . $product['alias'] : '';
        }

        if (!$product)
            return false;
        $product['colors'] = '';
        $product['colors_rus'] = '';
        $product['link'] = Link::getLinkProduct($product);

        if ($addGetLink) {
            if ($color)
                $product['link'] = URL::addGET($product['link'], 'color', $color);
            if ($sizes)
                $product['link'] = URL::addGET($product['link'], 'sizes', $sizes);
        }

        $sql_part = 'SELECT color.alias, modification.id AS modification_id, color.title, color.rustitle, color.colorkey, img, imgto, price,';
        $sql_part .= ' old_price, color_id, bagdeproduct_id FROM modification';
        $sql_part .= ' JOIN color ON modification.color_id = color.id';
        $sql_part .= " WHERE product_id = ? AND status = '1'";
        $sql_part .= ' AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id = ? AND amount > 0)';
        $sql_part .= ' ORDER BY color.sortvalue';
        $modification = \R::getAssoc($sql_part, [$product['id'], $product['id']]);

        if (!$modification)
            return false;

        $datadefault = [];
        $i = 1;

        foreach ($modification as $k => &$v) {

            //            if ($product['product_model'] == '1') {
            //                $numOf = \R::count('modificationregistr', 'modification_id = ? AND amount > 0', [$v['modification_id']]);
            //            } else {
            //                $numOf = (int) $v['amount'];
            //            }
            //            if (!$numOf) {
            //                unset($modification[$k]);
            //                continue;
            //            }

            if ($i == 1 && !$color) {
                $datadefault = $v;
                $datadefault['alias'] = $k;
            }
            if ($k == $product['color_default'] && !$color) {
                $datadefault = [];
                $datadefault = $v;
                $datadefault['alias'] = $k;
            }
            if ($k == $color) {
                $datadefault = [];
                $datadefault = $v;
                $datadefault['alias'] = $k;
            }

            $product['colors'] .= $v['title'] . ', ';
            $product['colors_rus'] .= $v['rustitle'] . ', ';

            $v['linkColor'] = URL::addGET($uri, 'color', $k);
            if ($sizes)
                $v['linkColor'] = URL::addGET($v['linkColor'], 'sizes', $sizes);
            $i++;
            if ($v['price'] == 0)
                $v['price'] = $product['price'];
            if ($v['old_price'] == 0)
                $v['old_price'] = $product['old_price'];

            if ($fullModel) {
                self::getBage($v);
                self::getImgTo($v);
                self::getSrcset($v, true);
                self::getImgFullPath($v);
                self::setModifJs($product, $v, $k);
            }
        }

        $product['colors'] = rtrim($product['colors'], ', ');
        $product['colors_rus'] = rtrim($product['colors_rus'], ', ');
        if (!$datadefault)
            return false;

        self::setDefaultDann($product, $datadefault);

        $cardData['pid'] = $product['modification_id'];
        $product['sizes_select'] = '';

        if ($product['product_model'] == '1' || $product['product_model'] == '4') {
            $sql_part = 'SELECT sizes.alias, sizes.title, sizes.description, modificationregistr.id AS modifregistr_id FROM modificationregistr';
            $sql_part .= ' JOIN sizes ON modificationregistr.sizes_id = sizes.id';
            $sql_part .= ' WHERE modificationregistr.modification_id = ?';
            $sql_part .= ' AND modificationregistr.id IN (SELECT modificationregistr_id FROM `registr` WHERE modification_id = ? AND amount > 0)';
            $sql_part .= ' ORDER BY sizes.sortvalue';

            $sizesList = \R::getAssoc($sql_part, [$product['modification_id'], $product['modification_id']]);

            if (!$sizesList)
                return false;

            $sost = false;

            $qqq = 0;
            $pid = 0;
            foreach ($sizesList as $k => &$v) {
                $v['linkSizes'] = $uri;
                if ($color) {
                    $v['linkSizes'] = URL::addGET($v['linkSizes'], 'color', $color);
                }

                $v['pid'] = $v['modifregistr_id'];
                if ($qqq === 0) {
                    $pid = $v['modifregistr_id'];
                }
                $qqq++;
                if ($sizes == $k) {
                    $v['selected'] = true;
                    $sost = true;
                    //$cardData['product_id'] = $product['id'];                    
                    $cardData['pid'] = $v['modifregistr_id'];
                    $product['sizes_select'] = 'Размер: ' . $v['title'];
                    //$cardData['size'] = $v['title'];
                } else {
                    $v['selected'] = false;
                    $v['linkSizes'] = URL::addGET($v['linkSizes'], 'sizes', $k);
                }
            }

            if (!$sost) {
                $cardData['pid'] = $pid;
                $cardData['nosizeselect'] = true;
            }

            if (!$sost && $sizes) {
                $sizes = false;
                foreach ($modification as &$v) {
                    $v['linkColor'] = URL::deleteGET($v['linkColor'], 'sizes');
                }
            }
        } else {
            $sizesList = [];
            $sizes = true;
        }

        //    $product['addCard'] = $sizes ? true : false;

        $product['addCard'] = true;

        $product['modification'] = $modification;
        $product['sizesList'] = $sizesList;
        $product['cardData'] = [];

        if ($product['addCard']) {
            $cardData['product_model'] = $product['product_model'];
            $product['cardData'] = $cardData;
        }

        return $product;
    }

    public function setRezultProducts($rezult = []) {

        if (!$rezult)
            return [];

        $products = [];
        $alias = '';
        $getArr = [];
        $uri = '';

        $actionArr = ['0' => 'setProduct', '1' => 'setModifProduct', '2' => 'setRegistrProduct'];

        foreach ($rezult as $item) {
            $arrIds = explode(',', $item['ids']);
            foreach ($arrIds as $k => $v) {
                $data = [];
                if ($v > 0) {
                    $action = $actionArr[$k];
                    if ($k == 0) {
                        $data = $this->$action($alias, $getArr, $uri, $v, true);
                    } else {
                        $data = $this->$action($v);
                    }
                }

                if ($data)
                    $products[] = $data;
            }
        }
        return $products;
    }

    public function setModifProduct($id) {

        $sql_part = 'SELECT color.alias AS color_alias, modification.product_id FROM modification JOIN color ON modification.color_id = color.id WHERE  modification.id = ? LIMIT 1';
        $data = \R::getRow($sql_part, [$id]);

        if ($data) {
            $getArr['color'] = $data['color_alias'];
            $product = $this->setProduct('', $getArr, '', $data['product_id'], true, true);

            return $product;
        }
    }

    public function setRegistrProduct($id, $qty = false) {
        $products = [];

        $qty_sql = $qty ? ' AND registr.amount > 0' : '';
        $sql_part = 'SELECT registr.id, registr.product_id, color.alias AS color_alias, sizes.alias AS sizes_alias';
        $sql_part .= ' FROM registr';
        $sql_part .= ' JOIN modification ON registr.modification_id = modification.id';
        $sql_part .= ' JOIN color ON modification.color_id = color.id';
        $sql_part .= ' LEFT OUTER JOIN modificationregistr ON registr.modificationregistr_id = modificationregistr.id';
        $sql_part .= ' LEFT OUTER JOIN sizes ON modificationregistr.sizes_id = sizes.id';


        if (is_array($id)) {
            $sql_part .= ' WHERE registr.id IN (' . \R::genSlots($id) . ')' . $qty_sql;
            $data = \R::getAll($sql_part, $id);
        } else {
            $sql_part .= ' WHERE registr.id = ?' . $qty_sql . ' LIMIT 1';
            $dataRow = \R::getRow($sql_part, [$id]);
            if ($dataRow)
                $data[] = $dataRow;
        }

        if ($data) {
            foreach ($data as $v) {
                $getArr['color'] = $v['color_alias'];
                if ($v['sizes_alias'])
                    $getArr['sizes'] = $v['sizes_alias'];
                $product = $this->setProduct('', $getArr, '', $v['product_id'], true, true);

                if ($product)
                    $products[$v['id']] = $product;
            }
        }
        if (!is_array($id)) {
            foreach ($products as $v) {
                $product = $v;
            }
            $products = $product;
        }
        return $products;
    }

    public function setProducts($products, $fullModel = false, $completeCheck = false) {

        foreach ($products as $keypr => &$product) {

            $product['link'] = Link::getLinkProduct($product);
            $color_default = $product['color_default'];
            $color_def = $product['color_def'];
            $color_def_tr = false;
            $modification_default = [];

            $sql_part = 'SELECT color.alias, modification.id AS modification_id, color.title, color.rustitle, color.colorkey, img, imgto, price, old_price, color_id,';
            $sql_part .= ' bagdeproduct_id FROM modification JOIN color ON modification.color_id = color.id';
            $sql_part .= ' WHERE modification.product_id = ? AND modification.status = 1';
            $sql_part .= ' AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id = ? AND amount > 0)';
            $sql_part .= ' ORDER BY color.sortvalue';

            //   echo $sql_part;
            //  die;

            $modifications = \R::getAssoc($sql_part, [$product['id'], $product['id']]);

            if ($completeCheck) {
                if (count($modifications) == 0) {
                    unset($products[$keypr]);
                    continue;
                }
            }
            $i = 1;

            foreach ($modifications as $key => &$modification) {
                if ($i == 1)
                    $key_mod_default = $key;
                $i++;


                //                $countsizes = \R::count('registr', 'modification_id = ? AND amount > 0', [$modification['modification_id']]);
                //                if ($countsizes == 0) {
                //                    unset($modifications[$key]);
                //                    continue;
                //                }

                if ($modification['price'] == 0)
                    $modification['price'] = $product['price'];
                if ($modification['old_price'] == 0)
                    $modification['old_price'] = $product['old_price'];

                // data-thumb='{"src":"images/product/300/1016222-NAVY_1.jpg","alt":"Classic Mini II Полусапоги","title":"Classic Mini II Полусапоги","srcset":"images/product/300/1016222-NAVY_1.jpg 1x,images/product/1000/1016222-NAVY_1.jpg 2x","alt_hoverimage":""}'

                self::getBage($modification);
                self::getImgTo($modification, true);
                self::getSrcset($modification, true);
                self::getImgFullPath($modification);
                self::setModifJs($product, $modification, $key);
                $modification['linkColor'] = Link::getLinkProduct($product) . '?color=' . $key;
                // $modification['linkColor'] = 'product/' . $product['alias'] . '?color=' . $key;
                $modification['sizes'] = ''; //json_encode([]);
                //                if ($fullModel) {
                //                    $sqlParth = 'SELECT modificationregistr.sizes_id, sizes.title, sizes.alias, sizes.description';
                //                    $sqlParth .= ' FROM modificationregistr';
                //                    $sqlParth .= ' JOIN sizes ON modificationregistr.sizes_id = sizes.id';             
                //                    $sqlParth .= ' WHERE modificationregistr.modification_id = ? ORDER BY sizes.sortvalue';
                //
                //                    $sizes = \R::getAll($sqlParth, [$modification['modification_id']]);
                //                    $modification['sizes'] = $sizes;
                //                    //$modification['sizes'] = h(json_encode($sizes));
                //                }

                if ($key == $color_default) {
                    $modification_default = $modification;
                    $key_mod_default = $key;
                }
                if ($key == $color_def) {
                    $color_def_tr = true;
                }
            }

            if (count($modifications) == 0) {
                unset($products[$keypr]);
                continue;
            }

            if (count($modification_default) == 0) {
                $modification_default = $modifications[$key_mod_default];
            }

            $product['modification'] = $modifications;
            $product['color_default'] = $key_mod_default;
            if (($key_mod_default != $color_def) && ($color_def_tr)) {
                $product['link'] = $product['link'] . '?color=' . $key_mod_default;
            }
            //$product['addCard'] = false;
            self::setDefaultDann($product, $modification_default);
        }

        return $products;
    }

    public static function getAllProductsIds($implode = true, $recomacces = false) {
        $product_ids = '';
        $sql_part = 'SELECT DISTINCT product.id';
        $sql_part .= ' FROM product';
        $sql_part .= ' JOIN registr ON product.id = registr.product_id';
        $sql_part .= ' JOIN modification ON product.id = modification.product_id';
        $status = 'status';
        if ($recomacces)
            $status = 'cart_on';

        $sql_part .= ' WHERE product.' . $status . ' = "1" AND modification.status = 1 AND registr.amount > 0';

        $product_ids = \R::getCol($sql_part);
        if ($implode)
            $product_ids = implode(',', $product_ids);

        return $product_ids;
    }

    static function getAllProducts($product_ids = false, $hits = false, $pagin = false, $start = 0, $perpage = 1, $color = false, $colorSql = false, $sortSqlPart = false) {
        $products = [];
        if (!$product_ids)
            $product_ids = App::$app->getProperty('product_ids');
        if (!is_array($product_ids)) {
            $product_ids = explode(',', $product_ids);
        }

        if (!$color) {
            $sql_part = 'SELECT id, title, alias, seolink, sku, price, old_price, color_default, color_default as color_def, product_model, gender';
            $sql_part .= ' FROM product';
            if ($hits) {
                if ($hits == 1) {
                    $limit = App::$app->getProperty('hit_count');
                    $sql_part .= " WHERE hit = '1' AND id IN (" . \R::genSlots($product_ids) . ") LIMIT $limit";
                } elseif ($hits == 2) {
                    $limit = App::$app->getProperty('sales_count');
                    $sql_part .= " WHERE id IN (" . \R::genSlots($product_ids) . ") ORDER BY price LIMIT $limit";
                } elseif ($hits == 3) {
                    $limit = App::$app->getProperty('newpr_count');
                    $sql_part .= " WHERE id IN (" . \R::genSlots($product_ids) . ") ORDER BY update_at DESC LIMIT $limit";
                } else {
                    $limit = (int) $hits;
                    $sql_part .= " WHERE id IN (" . \R::genSlots($product_ids) . ") LIMIT $limit";
                }
            } else {
                $sql_part .= " WHERE id IN (" . \R::genSlots($product_ids) . ")";
                $sql_part .= $sortSqlPart ? " ORDER BY $sortSqlPart" : '';
            }
        } else {
            $sql_part = 'SELECT product.id, product.title, product.alias, product.seolink, product.sku, product.price, product.old_price, color.alias AS color_default, product.color_default as color_def, product.product_model, product.gender';
            $sql_part .= ' FROM product';
            $sql_part .= ' JOIN modification ON product.id = modification.product_id';
            $sql_part .= ' JOIN color ON modification.color_id = color.id';
            $sql_part .= " WHERE product.id IN (" . \R::genSlots($product_ids) . ")";
            $sql_part .= $colorSql ? " AND modification.color_id IN ($colorSql)" : '';
            $sql_part .= ' ORDER BY product.id, modification.color_id DESC';
            $sql_part .= $sortSqlPart ? ", $sortSqlPart" : '';
        }
        if (!$hits && $pagin)
            $sql_part .= " LIMIT $start, $perpage";

        $products = \R::getAll($sql_part, $product_ids);
        //debug($products);

        return $products;
    }

    static function getCountProducts($product_ids = false, $color = true) {
        if (!$product_ids)
            $product_ids = App::$app->getProperty('product_ids');

        if (!is_array($product_ids)) {
            $product_ids = explode(',', $product_ids);
        }

        $sql_part = 'SELECT COUNT(*)';
        $sql_part .= ' FROM product';
        if ($color) {
            $sql_part .= ' JOIN modification ON product.id = modification.product_id';
            $sql_part .= ' JOIN color ON modification.color_id = color.id';
        }

        $sql_part .= " WHERE product.id IN (" . \R::genSlots($product_ids) . ")";

        $count = \R::getCell($sql_part, $product_ids);

        return $count;
    }

    public static function getGallery($modification_id = 0, $img = '', $superbig = false) {
        $gallery = [];
        if ($modification_id) {
            $sql_part = 'SELECT id, img, main, zoomon FROM gallery WHERE modification_id = ? ORDER BY sortvalue';
            $gallery = \R::getAssoc($sql_part, [$modification_id]);

            // debug($gallery);
            $mainimg = [];
            if ($img) {
                $mainimg = ['img' => $img, 'main' => 1, 'zoomon' => 0];
            }
            foreach ($gallery as $k => &$v) {
                if ($v['main'] && $mainimg) {
                    $mainimg = $v;
                    unset($gallery[$k]);
                    continue;
                }
                self::getSrcset($v, $superbig);
                self::getImgFullPath($v);

                //второй параметр выключен , т.к. нет картинок 1500
            }

            self::getSrcset($mainimg, $superbig);
            self::getImgFullPath($mainimg);
            array_unshift($gallery, $mainimg);
        }

        return $gallery;
    }

    public static function getImgTo(&$modification = [], $superbig = false) {
        if ($modification) {
            if ($modification['imgto'] == 1) {
                $sqlParth = 'SELECT img FROM gallery WHERE modification_id = ? AND main = 0 ORDER BY sortvalue LIMIT 1';
                $galery = \R::getRow($sqlParth, [$modification['modification_id']]);
                if (isset($galery)) {
                    $modification['imgtoimg'] = $galery['img'];
                    $data = ['img' => $galery['img']];
                    self::getSrcset($data, $superbig);
                    $modification['imgtoimg_srcset'] = $data['srcset'];
                    $modification['imgtoimg_imgsizes'] = $data['imgsizes'];
                    $data = ['img' => $galery['img']];
                    self::getImgFullPath($data);
                    $modification['imgtoimg_img_main'] = $data['img_main'];
                    $modification['imgtoimg_img_thumb'] = $data['img_thumb'];
                } else {
                    $modification['imgto'] = '0';
                }
            } else {
                $modification['imgtoimg'] = '';
                $modification['imgtoimg_srcset'] = '';
                $modification['imgtoimg_imgsizes'] = '';
                $modification['imgtoimg_img_main'] = '';
                $modification['imgtoimg_img_thumb'] = '';
            }
            return true;
        }
        return false;
    }

    public static function getImgFullPath(&$data = [], $thumb = true) {
        if (isset($data['img'])) {
            $imgproperty = \ishop\App::$app->getProperty('img_property');
            foreach ($imgproperty as $v) {
                if ($v['data'] == 'main')
                    $data['img_main'] = $v['path'] . $data['img'];

                if ($v['data'] == 'thumb' && $thumb)
                    $data['img_thumb'] = $v['path'] . $data['img'];
            }
        }
        return;
    }

    public static function getSrcset(&$imgrow, $superbig = false) {
        $img_srsset_on = \ishop\App::$app->getProperty('img_srsset_on');
        $imgrow['srcset'] = '';
        $imgrow['imgsizes'] = '';
        $imgrow['srcsetsizes'] = '';
        if (!$img_srsset_on)
            return;

        $imgproperty = \ishop\App::$app->getProperty('img_property');
        $result = '';


        if ($superbig) {
            $array = [];
            $i = 0;
            foreach ($imgproperty as $k => $v) {
                $array[] = $v;
                if ($v['superbig']) {
                    $i++;
                    continue;
                }

                $item = $array[$i - 1];
                $result .= $item['path'] . $imgrow['img'] . ' ' . $k . 'w,';
                $i++;
            }
        } else {
            foreach ($imgproperty as $k => $v) {
                if (!$superbig && $v['superbig'])
                    continue;

                $result .= $v['path'] . $imgrow['img'] . ' ' . $k . 'w,';               
            }
        }

        $result = rtrim($result, ',');
        if ($result) {
            $imgrow['srcset'] = rtrim($result, ',');
            $imgrow['imgsizes'] = ' sizes="100vw"';
            $imgrow['srcsetsizes'] = '100vw';
        }

        return;
    }

    public static function getBage(&$modification = []) {
        if ($modification) {
            $modification['bagdetitle'] = '';
            $modification['bagdefixed'] = '';
            $modification['bagderedcolor'] = '';
            if ($modification['bagdeproduct_id'] > 0) {
                $sqlParth = 'SELECT title, fixed, redcolor FROM bagdeproduct WHERE id = ? AND status = "1" LIMIT 1';
                $bagde = \R::getRow($sqlParth, [$modification['bagdeproduct_id']]);
                if (isset($bagde)) {
                    $modification['bagdetitle'] = $bagde['title'];
                    $modification['bagdefixed'] = $bagde['fixed'];
                    $modification['bagderedcolor'] = $bagde['redcolor'];
                } else {
                    $modification['bagdeproduct_id'] = 0;
                }
            }


            return true;
        }
        return false;
    }

    public static function addDannProduct(&$product, $superbig = false) {
        $product['gallery'] = Product::getGallery($product['modification_id'], $product['img'], $superbig);
        $brand = Product::getTableRow('brand', $product['brand_id']);
        $product['brand_img'] = $brand['img'];
        $product['brand_title'] = $brand['title'];
        self::addAjaxLinkProduct($product);
    }

    protected static function setDefaultDann(&$product, &$modifDefault) {
        if (!isset($modifDefault['srcset']))
            self::getSrcset($modifDefault);
        if (!isset($modifDefault['img_main']))
            self::getImgFullPath($modifDefault);

        if (isset($modifDefault['alias']))
            $product['color_default'] = $modifDefault['alias'];

        // $product['price'] = $modifDefault['price'] ? $modifDefault['price'] : $product['price'];
        //  $product['old_price'] = $modifDefault['old_price'] ? $modifDefault['old_price'] : $product['old_price'];
        $product['img'] = $modifDefault['img'];

        $product['srcset'] = $modifDefault['srcset'];
        $product['imgsizes'] = $modifDefault['imgsizes'];
        $product['srcsetsizes'] = $modifDefault['srcsetsizes'];
        $product['modification_id'] = $modifDefault['modification_id'];

        $product['color_title'] = $modifDefault['title'];
        $product['color_rustitle'] = $modifDefault['rustitle'];

        if (!isset($modifDefault['bagdetitle']))
            self::getBage($modifDefault);
        if (!isset($modifDefault['imgtoimg']))
            self::getImgTo($modifDefault);

        $product['imgto'] = $modifDefault['imgto'];
        $product['imgtoimg'] = $modifDefault['imgtoimg'];
        $product['imgtoimg_srcset'] = $modifDefault['imgtoimg_srcset'];
        $product['imgtoimg_imgsizes'] = $modifDefault['imgtoimg_imgsizes'];
        $product['img_main'] = $modifDefault['img_main'];
        $product['img_thumb'] = $modifDefault['img_thumb'];
        $product['imgtoimg_img_main'] = $modifDefault['imgtoimg_img_main'];
        $product['imgtoimg_img_thumb'] = $modifDefault['imgtoimg_img_thumb'];
        $product['bagdeproduct_id'] = $modifDefault['bagdeproduct_id'];
        $product['bagdefixed'] = $modifDefault['bagdefixed'];
        $product['bagderedcolor'] = $modifDefault['bagderedcolor'];
        $product['bagdetitle'] = $modifDefault['bagdetitle'];

        $price = $modifDefault['price'];
        $old_price = $modifDefault['old_price'];

        if ($price > 0 && $product['price'] != $price)
            $product['price'] = $price;
        if ($old_price > 0 && $product['old_price'] != $old_price)
            $product['old_price'] = $old_price;
    }

    protected static function setModifJs($product, &$modif, $color) {
        $modif['data_js'] = [
            'src' => $modif['img_thumb'],
            'alt' => $product['title'] . ' ' . mb_strtoupper($color),
            'title' => $product['title'] . ' ' . mb_strtoupper($color),
            'srcset' => $modif['srcset'],
            'sizes' => $modif['srcsetsizes'],
                // 'alt_hoverimage' => $modif['imgtoimg_srcset']
        ];

        $modif['data_js'] = h(json_encode($modif['data_js']));
    }

    public static function addAjaxLinkProduct(&$product, $params = 'sizesgroup,promo') {
        $arr_ajax = explode(',', $params);
        $i = 0;
        foreach ($arr_ajax as $v) {
            $product['ajax_link'][$i] = [];
            $ajax_link = Product::getTableRow($v, $product[$v . '_id']);
            if ($ajax_link && isset($ajax_link['content']) && $ajax_link['content']) {
                $content_url = $ajax_link['content_url'] ?? '';
                //                if ($content_url) {
                //                    $content_url = \ishop\Link::getLink($content_url);
                //                }
                $product['ajax_link'][$i]['dann'] = h(json_encode(['id' => $ajax_link['id'], 'name' => $v, 'modal' => true, 'content_url' => $content_url]));
                $product['ajax_link'][$i]['link_title'] = $ajax_link['link_title'];
                $product['ajax_link'][$i]['nameid'] = $v . $ajax_link['id'];
            }
            $i++;
        }
    }

    public static function addProductPattern($product, &$result) {
        if ($product) {
            $result['shop_name'] = \ishop\App::$app->getProperty('shop_name');
            $result['shop_tel'] = \ishop\App::$app->getProperty('shop_tel');
            $result['product_name'] = preg_replace('/([а-я]+)/ui', '', $product['title']);
            $result['product_name'] = trim($result['product_name'], ' ');
            $result['product_name_full'] = $product['title'];
            $result['product_gender'] = $product['gender'];
            $result['product_gender_lower'] = mb_strtolower($product['gender']);
            $result['product_gender_pril'] = preg_replace('/(е+$)/ui', 'х', $product['gender']);
            $result['product_gender_lower_pril'] = mb_strtolower($result['product_gender_pril']);
            $result['colors'] = 'Доступные варианты цветов: ' . $product['colors'] . '.';
            $result['colors_rus'] = 'Доступные варианты цветов: ' . $product['colors_rus'] . '.';
            $result['brand_title'] = $product['brand_title'] ?? '';

            $result['sku'] = $product['sku'] ?? '';
            if ($result['sku']) {
                $result['sku'] = 'SKU: ' . $result['sku'] . ' ';
            }
            $result['product_name_full_f_lower'] = mb_strtolower(mb_substr($product['title'], 0, 1)) . mb_substr($product['title'], 1, NULL);

            $curr = \ishop\App::$app->getProperty('currency');
            $result['product_price'] = ($product['price'] * $curr['value']) . $curr['symbol_right'];
            $result['product_old_price'] = ($product['old_price'] * $curr['value']) . ' ' . $curr['code'];
            $result['product_price_discont_value'] = 0;
            if ($product['old_price'] > $product['price']) {
                $result['product_price_discont_value'] = ($product['old_price'] - $product['price']);
            }
            if ($result['product_price_discont_value'] > 0) {
                $result['product_price_discont_proc'] = $product['price'] / ($product['old_price'] / 100);
                $result['product_price_discont_proc'] = ((int) (100 - $result['product_price_discont_proc']));
                $result['product_price_text'] = 'Цена с учетом скидки ' . $result['product_price_discont_proc'] . '% - ' . $curr['symbol_left'] . $product['price'] . $curr['symbol_right'] . '. Ваша экономия составит ' . $curr['symbol_left'] . $result['product_price_discont_value'] . $curr['symbol_right'] . '.';
                $result['product_price_discont_procoi'] = ' со скидкой ' . $result['product_price_discont_proc'] . '%';
                $result['product_price_discont_proc'] = ' скидка ' . $result['product_price_discont_proc'] . '%';

                $result['product_price_discont_value'] = 'экономия ' . ($result['product_price_discont_value'] * $curr['value']) . ' ' . $curr['code'];
            } else {
                $result['product_price_discont_value'] = '';
                $result['product_price_discont_proc'] = '';
                $result['product_price_text'] = '';
                $result['product_price_discont_procoi'] = '';
            }
        }
    }

    public static function getProductAddCard($cardData) {
        $product = [];
        $sql_part = 'SELECT product.id, product.product_model, product.title, product.alias, product.seolink, product.sku, product.price, product.old_price, product.gender,';
        $sql_part .= ' modification.img, modification.price AS modification_price, modification.old_price AS modification_old_price, modification.id AS modification_id,';
        $sql_part .= ' color.title AS color_title, color.rustitle  AS color_rustitle, color.alias AS color_alias';

        if ($cardData['product_model'] == '1' || $cardData['product_model'] == '4') {
            $sql_part .= ', modificationregistr.id AS modificationregistr_id, sizes.title AS sizes_title, sizes.alias AS sizes_alias';
            $sql_part .= ' FROM product';
            $sql_part .= ' JOIN modification ON modification.product_id = product.id';
            $sql_part .= ' JOIN color ON modification.color_id = color.id';
            $sql_part .= ' JOIN modificationregistr ON modification.id = modificationregistr.modification_id';
            $sql_part .= ' JOIN sizes ON modificationregistr.sizes_id = sizes.id';
            $sql_part .= ' WHERE product.product_model = ? AND modificationregistr.id = ? LIMIT 1';
            $product = \R::getRow($sql_part, [$cardData['product_model'], $cardData['pid']]);
            $param = 'modificationregistr_id';
        } else {
            $sql_part .= ' FROM product';
            $sql_part .= ' JOIN modification ON modification.product_id = product.id';
            $sql_part .= ' JOIN color ON modification.color_id = color.id';
            $sql_part .= ' WHERE product.product_model = ? AND modification.id = ? LIMIT 1';
            $product = \R::getRow($sql_part, [$cardData['product_model'], $cardData['pid']]);
            $param = 'modification_id';
            $product['modificationregistr_id'] = 0;
            $product['sizes_title'] = '';
        }

        if ($product) {
            $product['id'] = \R::getCell("SELECT id FROM registr WHERE $param = ?", [$cardData['pid']]);

            self::getImgFullPath($product);

            if ($product['modification_price'] > 0)
                $product['price'] = $product['modification_price'];
            if ($product['modification_old_price'] > 0)
                $product['old_price'] = $product['modification_old_price'];


            $product['link'] = Link::getLinkProduct($product);
            //$product['link'] = 'product/' . $product['alias'];
            $product['link'] = URL::addGET($product['link'], 'color', $product['color_alias']);
            unset($product['color_alias']);
            if ($cardData['product_model'] == 1) {
                $product['link'] = URL::addGET($product['link'], 'sizes', $product['sizes_alias']);
                unset($product['sizes_alias']);
            }
            $product['linkedit'] = URL::addGET($product['link'], 'editcatritem', $product['id']);

            unset($product['modification_price']);
            unset($product['modification_old_price']);
            unset($product['img']);
            unset($product['product_model']);
            unset($product['modification_id']);
            unset($product['modificationregistr_id']);

            if ($cardData['product_model'] == '3' || $cardData['product_model'] == '4') {
                $product['color_title'] = '';
                $product['color_rustitle'] = '';
            }

            // $product['qty'] = 1;            
            //   $product['summ'] = $product['price'];
            $product['titlecard'] = $product['title'];

            $product['title'] = $product['color_title'] ? $product['title'] . ' ' . mb_strtoupper($product['color_title']) : $product['title'];
            $product['title'] = $product['sizes_title'] ? $product['title'] . ' разм: ' . $product['sizes_title'] : $product['title'];
            $product['nosizeselect'] = $cardData['nosizeselect'];
        }

        return $product;
    }

    public static function getFilterParams($id = 0, $str = true) {
        if (!$id)
            return false;
        $data = \R::getAssoc('SELECT attrgr_id, title, bitmask FROM attribute_product JOIN attribute_group ON attribute_product.attrgr_id = attribute_group.id WHERE product_id = ?', [$id]);

        if (!$data)
            return false;
        $rezult = [];
        foreach ($data as $k => $v) {
            $value = \R::getAssoc('SELECT id, value FROM attribute_value WHERE ((1 << sdvig) & ?) > 0 AND attr_group_id = ?', [$v['bitmask'], $k]);
            if ($value)
                $rezult[$v['title']] = $value;
        }

        if ($str && $rezult) {
            $newrezult = '';
            foreach ($rezult as $key => $val) {
                $newrezult .= ' ' . $key;
                foreach ($val as $r) {
                    $newrezult .= ' ' . $r;
                }
            }
        }
        $rezult = trim($newrezult, ' ');
        return $rezult;
    }

    public static function searchProduct($query = '', $limit = 0, $pagin = false, $start = 0, $perpage = 1) {

        $limit_sql = '';
        if ($limit > 0) {
            $limit_sql = 'LIMIT ' . $limit;
        } elseif ($pagin) {
            $limit_sql = "LIMIT $start, $perpage";
        }
        $query_arr = words($query);
        $rezult = [];
        if ($query_arr) {
            $where = "";
            $params = [];
            foreach ($query_arr as $value) {
                $params[] = "%$value%";
                $where .= ' AND index_search LIKE ?';
            }
            $where = trim($where, ' AND ');
            for ($i = 1; $i <= 3; $i++) {
                $sql_part = "SELECT ids, title, link FROM indexproduct WHERE $where AND sortvalue = $i $limit_sql";
                $rezult = \R::getAll($sql_part, $params);
                if ($rezult)
                    break;
            }
        }
        return $rezult;
    }

    public static function getColorsProduct(&$product = []) {
        if ($product) {
            $sql_part = 'SELECT color.title, color.rustitle';
            $sql_part .= ' FROM modification';
            $sql_part .= ' JOIN color ON modification.color_id = color.id';
            $sql_part .= " WHERE product_id = ? AND status = '1'";
            $sql_part .= ' AND modification.id IN (SELECT modification_id FROM `registr` WHERE product_id = ? AND amount > 0)';
            $sql_part .= ' ORDER BY color.sortvalue';
            $modifications = \R::getAll($sql_part, [$product['id'], $product['id']]);
            $product['colors'] = '';
            $product['colors_rus'] = '';
            foreach ($modifications as $v) {
                $product['colors'] .= $v['title'] . ', ';
                $product['colors_rus'] .= $v['rustitle'] . ', ';
            }
            $product['colors'] = rtrim($product['colors'], ', ');
            $product['colors_rus'] = rtrim($product['colors_rus'], ', ');
        }
    }

}

//       // $sql_part = 'SELECT product.id as product_id, modification.id as modification_id,modificationregistr.id as modificationregistr_id';
//        $sql_part = 'SELECT product.id as product_id, modification.id as modification_id';
//        $sql_part .= ' FROM product';
//        $sql_part .= ' JOIN modification ON product.id = modification.product_id';
//       // $sql_part .= ' JOIN modificationregistr ON modification.id = modificationregistr.modification_id';
//
//        $sql_part .= ' WHERE product.product_model = "2"';
//
//        $products = \R::getAll($sql_part);
//        foreach ($products as $v) {
//            $registr = \R::dispense('registr');
//            $registr['product_id'] = $v['product_id'];
//            $registr['modification_id'] = $v['modification_id'];
//            //$registr['modificationregistr_id'] = $v['modificationregistr_id'];
//            \R::store($registr);
//        }