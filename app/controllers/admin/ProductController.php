<?php

namespace app\controllers\admin;

use app\models\admin\Product;
use app\models\admin\Modification;
use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;
use app\widgets\jsinclud\Jsinclud;
use ishop\Cache;
use app\widgets\filter\Filter;

class ProductController extends AppController {

    public function indexAction() {
        $id = $_GET['id'] ?? 0;
        $product_id = $_GET['product_id'] ?? 0;
        $gender = $_GET['gender'] ?? 0;
        switch ($gender) {
            case 0:
                $genderwhere = 'Все';
                break;
            case 1:
                $genderwhere = 'Женские';
                break;
            case 2:
                $genderwhere = 'Мужские';
                break;
            case 3:
                $genderwhere = 'Детские';
                break;
            case 4:
                $genderwhere = 'Унисекс';
                break;
        }

        $sku = $_GET['sku'] ?? '';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 50;
        $msg = '';
        if ($id) {
            $count = \R::count('product', 'category_id = ?', [$id]);
            $msg = ' в категории';
        } elseif ($sku) {
            $count = \R::count('product', 'sku = ?', [$sku]);
            $msg = ' SKU: ' . $sku;
        } elseif ($product_id) {
            $count = 1;
            $msg = ' Product ID: ' . $product_id;
        } elseif ($genderwhere == 'Женские' || $genderwhere == 'Мужские' || $genderwhere == 'Детские' || $genderwhere == 'Унисекс') {
            $count = \R::count('product', 'gender = ?', [$genderwhere]);
            $msg = ' Гендер: ' . $genderwhere;
        } else {
            $count = \R::count('product');
        }        
        
        $pagination = new Pagination($page, $perpage, $count, true);
        $start = $pagination->getStart();
        if ($id) {
            $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id WHERE product.category_id = ? ORDER BY product.title LIMIT $start, $perpage", [$id]);
        } elseif ($sku) {
            $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id WHERE product.sku = ? ORDER BY product.title LIMIT $start, $perpage", [$sku]);
        } elseif ($product_id) {
            $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id WHERE product.id = ? ORDER BY product.title LIMIT $start, $perpage", [$product_id]);
        } elseif ($gender) {
            $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id WHERE product.gender = ? ORDER BY product.title LIMIT $start, $perpage", [$genderwhere]);
        } else {
            $products = \R::getAll("SELECT product.*, category.title AS cat FROM product JOIN category ON category.id = product.category_id ORDER BY product.title LIMIT $start, $perpage");
        }

        $this->setMeta('Список товаров' . $msg);
        $this->set(compact('products', 'pagination', 'count', 'msg', 'sku', 'gender', 'genderwhere'));
    }

    public function typeaheadAction() {
        if ($this->isAjax()) {
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
            if ($query) {
                $product = \R::getAll("SELECT sku, title FROM product WHERE sku LIKE ? LIMIT 11", ["%{$query}%"]);
                echo json_encode($product);
            }
        }
        die;
    }

    public function typeaheadproductAction() {
        if ($this->isAjax()) {
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
            if ($query) {
                $product = \R::getAll("SELECT id, title, gender FROM product WHERE title LIKE ? LIMIT 11", ["%{$query}%"]);
                foreach ($product as &$item) {
                    $item['title'] .= ' | ' . $item['gender'];
                }
                echo json_encode($product);
            }
        }
        die;
    }

    public function deleteAction() {
        $id = $_GET['id'] ?? 0;
        $id = (int) $id;
        if (!$id) {
            return;
        }
        $product = \R::load('product', $id);
        if (!$product) {
            return;
        }
        $alias = 'product/' . $product->alias;
        delSef($alias);
        $mod_id = \R::getCol('SELECT id FROM modification WHERE product_id = ?', [$id]);
        if ($mod_id) {
            $modification = new Modification();
            foreach ($mod_id as $item) {
                $modification->deleteModification($item);
            }
        }
        \R::trash($product);
        $res = ['succes' => true, 'title' => 'Продукт удален', 'message' => "Продукт удален успешно"];
        $_SESSION['success'] = 'Продукт удален';
        Product::cacheIdsClear();
        redirect();
    }

    public function addImageAction() {
        $id = $_GET['id'] ?? 0;
        $id = (int) $id;

        if ($id) {
            $data = $_FILES['files'] ?? [];
            $files = [];

            foreach ($data as $key => $val) {
                foreach ($val as $k => $v) {
                    $files[$k][$key] = $v;
                }
            }
            if (!$files) {
                $res[0] = ['succes' => false, 'name' => '', 'error' => 'Ошибка! Файлы не загружены'];
                exit(json_encode($res));
            }

            $name = 'multi';
            $modification = new Modification();

            foreach ($files as $i => $file) {
                $res[$i] = $modification->uploadImg($name, $file, $id);
            }
            $modification->saveGallery($id);
            exit(json_encode($res));
        }

        die;
    }

    public function addImageparseAction() {
        $id = $_REQUEST['id'] ?? 0;
        $id = (int) $id;
        $redirect = $_REQUEST['redirect'] ?? false;
        $add = $_REQUEST['add'] ?? false;
        if ($id) {
            $sql = 'SELECT `modification`.`id`, `color`.`alias`, `product`.`sku`';
            $sql .= '  FROM `product` JOIN `modification` ON `product`.`id` = `modification`.`product_id`';
            $sql .= '  JOIN `color` ON `modification`.`color_id` = `color`.`id`';
            $sql .= '  WHERE `product`.`id` = ?';
            $data = \R::getAssoc($sql, [$id]);
            $uploaddir = WWW . App::$app->getProperty('img_parsimg_path');
            $arr_rezult = [];
            $start_count = 0;
            $count = 0;
            if ($data) {
                foreach ($data as $id => $item) {
                    $color = mb_strtoupper($item['alias']);
                    for ($i = 1; $i <= 6; $i++) {
                        $fileurl = App::$app->getProperty('url_parsimg_prefix') . $item['sku'] . '-' . $color . "_$i.jpg" . App::$app->getProperty('url_parsimg_postfix');
                        $name = $item['sku'] . '-' . $color . "_$i.jpg";
                        $file = $uploaddir . $name;
                        $rezult = file_get_img_file_curl($fileurl, $file);
                        $filesize = 0;
                        if ($rezult) {
                            @$filesize = filesize($file);
                            $start_count++;
                        }
                        if (!$filesize) {
                            $rezult = false;
                        }
                        $arr_rezult[$id][$i] = [
                            'rezult' => $rezult,
                            'file' => [
                                'name' => $name,
                                'size' => $filesize,
                                'error' => false,
                                'type' => 'image/jpeg',
                                'tmp_name' => $file
                            ],
                            'color' => $color
                        ];
                        //sleep(1);
                    }
                }
            }

            if ($arr_rezult && $add) {
                $name = 'multi';
                $s = 0;
                foreach ($arr_rezult as $id => $item) {
                    $modification = new Modification();
                    foreach ($item as $img) {
                        if ($img['rezult']) {
                            $res[$s] = $modification->uploadImg($name, $img['file'], $id, [], false, true);
                            if ($res[$s]['succes']) {
                                $count++;
                            }
                            $s++;
                        }
                    }
                    $modification->saveGallery($id);
                }
            }
        }
        if ($redirect) {
            $_SESSION['success'] = "Успешно скачано $start_count файлов, добавлено к товару $count файлов.";
            redirect();
        }
        die;
    }

    public function editModificationAction() {
        $data = $_POST;
        $id = $data['id'] ?? 0;
        $id = (int) $id;
        if ($data && $id) {
            $modification = new Modification();
            $modification->load($data);
            if (!$modification->validate($data)) {
                $modification->getErrors();
                $err = $_SESSION['error'];
                unset($_SESSION['error']);
                $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                exit(json_encode($res));
            }
            if ($modification->update('modification', $id)) {
                $amount = $data['amount'];
                if (is_array($amount)) {
                    $modification->setAmount($amount);
                }
                Product::cacheIdsClear();
                $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Модификация сохранена успешно'];
                $modification->setTimestamp('product', $data['product_id']);
                exit(json_encode($res));
            } else {
                $res = ['succes' => false, 'error' => 'Ошибка БД'];
                exit(json_encode($res));
            }
        }
        die;
    }

    public function addModificationAction() {
        $data = $_POST;
        if ($data) {
            $modification = new Modification();
            $modification->load($data);
            if (!$modification->validate($data)) {
                $modification->getErrors();
                $err = $_SESSION['error'];
                unset($_SESSION['error']);
                $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                exit(json_encode($res));
            }
            if ($id = $modification->save('modification')) {
                $res = $modification->newAmount($data, $id);
                if ($res['succes']) {
                    $modification->setTimestamp('product', $data['product_id']);
                    $new_mod = [
                        'id' => $id,
                        'product_id' => $data['product_id'],
                        'product_model' => $data['product_model']
                    ];
                    $new_mod = $modification->getModification($new_mod);
                    $res['content'] = $this->loadViewContent('modification_tab', compact('new_mod'));
                    $name = $new_mod['name'];
                    $res['li'] = $this->loadViewContent('modli', compact('id', 'name'));
                    $res['id'] = $id;
                    AppModel::setAllLastmodified();
                }
                exit(json_encode($res));
            } else {
                $res = ['succes' => false, 'error' => 'Ошибка БД'];
                exit(json_encode($res));
            }
        }
        die;
    }

    public function setDefimgAction() {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        if ($id && $name) {
            $modification = new Modification();
            $res = $modification->setDefaultImg($id, $name);
            exit(json_encode($res));
        } else {
            $res = ['succes' => false, 'name' => $name, 'error' => 'Данные POST не получены'];
            exit(json_encode($res));
        }
        die;
    }

    public function delModificationAction() {
        $id = $_POST['id'] ?? 0;
        if ($id) {
            $modification = new Modification();
            $res = $modification->deleteModification($id);
            exit(json_encode($res));
        }
        die;
    }

    public function setSortimgAction() {
        $id = $_POST['id'] ?? 0;
        $name = $_POST['name'] ?? '';
        $val = $_POST['val'] ?? 0;
        if ($id && $name && $val) {
            $modification = new Modification();
            $res = $modification->setSortValImg($id, $name, $val);
            exit(json_encode($res));
        } else {
            $res = ['succes' => false, 'name' => $name, 'error' => 'Данные POST не получены'];
            exit(json_encode($res));
        }
        die;
    }

    public function editAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $product = new Product();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            if (!isset($data['dopcategory'])) {
                $data['dopcategory'][0] = $data['category_id'];
            }
            $product->load($data);
            if (!$product->validate($data)) {
                $product->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }

            if ($product->update('product', $id)) {
                $product->editDopCategoryProduct($id, $data);
                $product->editFilter($id, $data);
                Product::cacheIdsClear();
                //$alias = AppModel::createAlias('product', 'alias', $data['title'], $id);
                //$product = \R::load('product', $id);
                //$product->alias = $alias;
                //$product->alias;
                //\R::store($product);
                if ($this->isAjax()) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Продукт сохранен'];
                    exit(json_encode($res));
                } else {
                    $_SESSION['success'] = 'Изменения сохранены';
                    redirect();
                }
            }
        }

        $id = $this->getRequestID();


//        $product = \R::load('product', $id);
//
//        App::$app->setProperty('parent_id', $product->category_id);
//        $dop_category_id = \R::getAssoc('SELECT category_id FROM categoryproduct  WHERE product_id = ?', [$id]);
//        if (!isset($dop_category_id[$product->category_id])) {
//            $dop_category_id[$product->category_id] = $product->category_id;
//        }
//
//        $brand = \R::getAssoc('SELECT id, title FROM brand');
//
//        $sizesgroup = \R::getAssoc('SELECT id, title FROM sizesgroup');
//        $promo = \R::getAssoc('SELECT id, title FROM promo');
//        $related_product = '';
//        if ($product->related) {
//            $related_product = \R::getCell('SELECT title FROM product WHERE id = ?', [$product->related]);
//        }
//
//       // $filter = new \app\widgets\filter\Filter($product->id, '', WWW . '/filter/admin_filter_tpl.php', false, true, false, false);
//        $filter = new \app\widgets\filter\Filter([
//            'ids' => $product->id,
//            'check' => false,
//            'tpl' => WWW . '/filter/admin_filter_tpl.php',
//            'oneproduct' => true,
//            'status' => false
//        ]);
        $form = new \app\widgets\formprocessor\Formprocessor([], [], 'Product', $id, 'product');
        $dop_category_id = \R::getAssoc('SELECT category_id FROM categoryproduct  WHERE product_id = ?', [$id]);
        \ishop\App::$app->setProperty('parent_id', $dop_category_id);
        $dopcategory = new \app\widgets\menu\Menu([
            'tpl' => WWW . '/menu/select2ToTree.php',
            'container' => 'select',
            'cache' => 0,
            'cacheKey' => 'admin_select_multiple',
            'class' => 'form-control select2ToTree',
            'admin' => true,
            'attrs' => [
                'name' => 'dopcategory[]',
                'id' => 'dopcategory',
                'multiple' => '',
            ],
        ]);
        $inputs['dop_category_id'] = ['type' => 'html', 'default_v' => $dopcategory, 'label' => 'Дополнительные каталоги', 'parentclass' => 'form-group'];
        $form->addInput($inputs, 'tagstart15');
        $seolink = \R::getCell('SELECT `seolink` FROM `product` WHERE `id` = ?', [$id]);
        $addinputs['seolink'] = [
            'type' => 'text',
            'name' => 'newseolink',
            'default_v' => '',
            'parentclass' => 'form-group',
            'value' => $seolink,
            'label' => 'Seolink',
            'id' => 'input-seolink',
            'data' => ['table' => 'product', 'action' => ADMIN . '/ajax/editseolink', 'seolink' => $seolink]];
        $form->addInput($addinputs, 'tagstart32');
        $filter = new Filter([
            'ids' => $id,
            'check' => false,
            'oneproduct' => true,
            'status' => false
        ]);
        $form->addInput($filter->inputs, 'tagstart49');

        $color = \R::getAssoc('SELECT alias FROM modification JOIN color ON modification.color_id = color.id WHERE product_id = ?', [$form->id]);
        $form->inputs['color_default']['select'] = $color;
        $color_all = \R::getAssoc('SELECT id, title FROM color');
        $modifications = \R::getAssoc('SELECT * FROM modification WHERE product_id = ?', [$form->id]);
        $bagdeproduct = \R::getAssoc('SELECT id, title FROM bagdeproduct');
        $sizes_all = [];
        if ($form->product_model != '3') {
            $sql = 'SELECT sizes.id, sizes.title FROM sizesgroupselect JOIN sizes ON sizesgroupselect.sizes_id = sizes.id WHERE sizesgroup_id = ? ORDER BY sizes.sortvalue';
            $sizes_all = \R::getAssoc($sql, [$form->sizesgroup_id]);
        }

        foreach ($modifications as $k => $v) {
            if ($form->product_model == '1' || $form->product_model == '4') {
                $modificationregistr[$k] = \R::getAssoc('SELECT modificationregistr.id, sizes.title FROM modificationregistr JOIN sizes ON modificationregistr.sizes_id = sizes.id WHERE modification_id = ?', [$k]);
                $registr[$k] = \R::getAssoc('SELECT modificationregistr_id, amount, id FROM registr WHERE modification_id = ?', [$k]);
            } else {
                $modificationregistr[$k] = [];
                $registr[$k] = \R::getRow('SELECT id, amount FROM registr WHERE modification_id = ? LIMIT 1', [$k]);
            }
            $gallery[$k] = \R::getAssoc('SELECT * FROM gallery WHERE modification_id = ? ORDER BY sortvalue', [$k]);
            $dat[] = $k;
        }

        $this->setMeta("Редактирование товара {$form->title}");
        $this->set(compact('form', 'gallery', 'registr', 'color_all', 'modifications', 'modificationregistr', 'bagdeproduct', 'sizes_all'));
    }

    public function copyproductAction() {
        if (!empty($_POST)) {
            $product = new Product();
            $data = $_POST;
            $modifsdata = $_POST['modif'] ?? [];
            $data['update_at'] = date('Y-m-d H:i:s');
            if (!isset($data['dopcategory'])) {
                $data['dopcategory'][0] = $data['category_id'];
            }
            $product->load($data);
            $product->attributes['status'] = $product->attributes['status'] ? '1' : '2';
            $product->attributes['hit'] = $product->attributes['hit'] ? '1' : '2';

            if (!$product->validate($data)) {
                $product->getErrors();
                redirect();
            }

            if ($id = $product->save('product')) {
                $alias = AppModel::createAlias('product', 'alias', $data['title'], $id);
                $p = \R::load('product', $id);
                $p->alias = $alias;
                \R::store($p);
                $category_seolink = \R::getCell('SELECT seolink FROM category WHERE id = ?', [$data['category_id']]);
                if ($category_seolink) {
                    $seolink_temp = $category_seolink . '/' . $alias;
                } else {
                    $seolink_temp = $alias;
                }
                $seolink = AppModel::createSeolink($seolink_temp, $id);
                $sef = \R::dispense('sef');
                $sef->alias = 'product/' . $alias;
                $sef->link = $seolink;
                $res = \R::store($sef);
                if ($res) {
                    $p = \R::load('product', $id);
                    $p->seolink = $seolink;
                    \R::store($p);
                }
                $product->editDopCategoryProduct($id, $data);
                $product->editFilter($id, $data);
                Product::cacheIdsClear();
                $success = 'Товар добавлен';
                AppModel::setAllLastmodified($data['category_id']);
                $err = '';
                if ($modifsdata) {

                    foreach ($modifsdata as $dataitem) {
                        $erritem = '';
                        $dataitem['product_id'] = $id;
                        $dataitem['product_model'] = $data['product_model'];
                        $modification = new Modification();
                        $modification->load($dataitem);
                        if (!$modification->validate($dataitem)) {
                            $modification->getErrors();
                            $erritem = $_SESSION['error'];
                            unset($_SESSION['error']);

                            $err .= "==color_id: {$dataitem['color_id']} не добавлена. $erritem ==";
                            continue;
                        }
                        if ($idmod = $modification->save('modification')) {
                            $res = $modification->newAmount($dataitem, $idmod);
                            if ($res['succes']) {
                                $success .= "==color_id: {$dataitem['color_id']} добавлена==";
                            }
                        } else {
                            $err .= "==color_id: {$dataitem['color_id']} не добавлена. Ошибка БД==";
                        }
                    }
                }
                if ($success) {
                    $_SESSION['success'] = $success;
                }
                if ($err) {
                    $_SESSION['error'] = $err;
                }
                redirect(ADMIN . '/product/edit?id=' . $id);
            }
            redirect();
        }

        $id = $this->getRequestID();

        $form = new \app\widgets\formprocessor\Formprocessor([], [], 'Product', $id, 'product');
        $dop_category_id = \R::getAssoc('SELECT category_id FROM categoryproduct  WHERE product_id = ?', [$id]);
        \ishop\App::$app->setProperty('parent_id', $dop_category_id);
        $dopcategory = new \app\widgets\menu\Menu([
            'tpl' => WWW . '/menu/select2ToTree.php',
            'container' => 'select',
            'cache' => 0,
            'cacheKey' => 'admin_select_multiple',
            'class' => 'form-control select2ToTree',
            'attrs' => [
                'name' => 'dopcategory[]',
                'id' => 'dopcategory',
                'multiple' => '',
            ],
        ]);
        $inputs['dop_category_id'] = ['type' => 'html', 'default_v' => $dopcategory, 'label' => 'Дополнительные каталоги', 'parentclass' => 'form-group'];
        $form->addInput($inputs, 'tagstart15');
        $filter = new Filter([
            'ids' => $id,
            'check' => false,
            'oneproduct' => true,
            'status' => false
        ]);
        $form->addInput($filter->inputs, 'tagstart49');
        $title = $form->title;
        $form->delInput('linkhref');
        $form->delInput('linkhref2');
        $form->delInput('submit');
        $form->form = false;
        $color = \R::getAssoc('SELECT alias FROM modification JOIN color ON modification.color_id = color.id WHERE product_id = ?', [$form->id]);
        $form->inputs['color_default']['select'] = $color;

//        $product = \R::load('product', $id);
//        App::$app->setProperty('parent_id', $product->category_id);
//        $dop_category_id = \R::getAssoc('SELECT category_id FROM categoryproduct  WHERE product_id = ?', [$id]);
//        if (!isset($dop_category_id[$product->category_id])) {
//            $dop_category_id[$product->category_id] = $product->category_id;
//        }
//        $brand = \R::getAssoc('SELECT id, title FROM brand');
//        $sizesgroup = \R::getAssoc('SELECT id, title FROM sizesgroup');
//        $color = \R::getAssoc('SELECT color.id, alias FROM modification JOIN color ON modification.color_id = color.id WHERE product_id = ?', [$product->id]);
//        $promo = \R::getAssoc('SELECT id, title FROM promo');
//        $related_product = '';
//        if ($product->related) {
//            $related_product = \R::getCell('SELECT title FROM product WHERE id = ?', [$product->related]);
//        }
//
//        $filter = new \app\widgets\filter\Filter($product->id, '', WWW . '/filter/admin_filter_tpl.php', false, true, false, false);

        $color_all = \R::getAssoc('SELECT id, title FROM color');
        $modifications = \R::getAssoc('SELECT * FROM modification WHERE product_id = ?', [$form->id]);
        $bagdeproduct = \R::getAssoc('SELECT id, title FROM bagdeproduct');
        if ($form->product_model != '3') {
            $sql = 'SELECT sizes.id, sizes.title FROM sizesgroupselect JOIN sizes ON sizesgroupselect.sizes_id = sizes.id WHERE sizesgroup_id = ? ORDER BY sizes.sortvalue';
            $sizes_all = \R::getAssoc($sql, [$form->sizesgroup_id]);
        }

        foreach ($modifications as $k => $v) {
            if ($form->product_model == '1' || $form->product_model == '4') {
                $modificationregistr[$k] = \R::getAssoc('SELECT modificationregistr.id, sizes.title, modificationregistr.sizes_id FROM modificationregistr JOIN sizes ON modificationregistr.sizes_id = sizes.id WHERE modification_id = ?', [$k]);
                $registr[$k] = \R::getAssoc('SELECT modificationregistr_id, amount, id FROM registr WHERE modification_id = ?', [$k]);
            } else {
                $modificationregistr[$k] = [];
                $registr[$k] = \R::getRow('SELECT id, amount FROM registr WHERE modification_id = ? LIMIT 1', [$k]);
            }
            $gallery[$k] = \R::getAssoc('SELECT * FROM gallery WHERE modification_id = ? ORDER BY sortvalue', [$k]);
            $dat[] = $k;
        }

        $this->setMeta("Редактирование товара {$form->title}");
        $this->set(compact('form', 'gallery', 'registr', 'color_all', 'modifications', 'modificationregistr', 'bagdeproduct', 'sizes_all'));
    }

    public function addAction() {
        if (!empty($_POST)) {
            $product = new Product();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            if (!isset($data['dopcategory'])) {
                $data['dopcategory'][0] = $data['category_id'];
            }
            $product->load($data);
            $product->attributes['status'] = $product->attributes['status'] ? '1' : '2';
            $product->attributes['hit'] = $product->attributes['hit'] ? '1' : '2';
            //$product->getImg();

            if (!$product->validate($data)) {
                $product->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }

            if ($id = $product->save('product')) {
                $alias = AppModel::createAlias('product', 'alias', $data['title'], $id);
                $p = \R::load('product', $id);
                $p->alias = $alias;
                \R::store($p);
                $category_seolink = \R::getCell('SELECT seolink FROM category WHERE id = ?', [$data['category_id']]);
                if ($category_seolink) {
                    $seolink_temp = $category_seolink . '/' . $alias;
                } else {
                    $seolink_temp = $alias;
                }
                $seolink = AppModel::createSeolink($seolink_temp, $id);
                $sef = \R::dispense('sef');
                $sef->alias = 'product/' . $alias;
                $sef->link = $seolink;
                $res = \R::store($sef);
                if ($res) {
                    $p = \R::load('product', $id);
                    $p->seolink = $seolink;
                    \R::store($p);
                }
                $product->editDopCategoryProduct($id, $data);
                AppModel::setAllLastmodified($data['category_id']);
                Product::cacheIdsClear();
                //$product->editFilter($id, $data);
                //$product->editRelatedProduct($id, $data);
                $_SESSION['success'] = 'Товар добавлен';
                redirect(ADMIN . '/product/edit?id=' . $id);
            }
            redirect();
        }
        $brand = \R::getAssoc('SELECT id, title FROM brand');
        $sizesgroup = \R::getAssoc('SELECT id, title FROM sizesgroup');
        $promo = \R::getAssoc('SELECT id, title FROM promo');
        $this->setMeta('Новый товар');
        $this->set(compact('brand', 'sizesgroup', 'promo'));
    }

    public function relatedProductAction() {
        /* $data = [
          'items' => [
          [
          'id' => 1,
          'text' => 'Товар 1',
          ],
          [
          'id' => 2,
          'text' => 'Товар 2',
          ],
          ]
          ]; */
        $tabl = 'product';
        $q = isset($_GET['q']) ? $_GET['q'] : '';
        $category = isset($_GET['category']) ? $_GET['category'] : '';
        if ($category) {
            $tabl = 'category';
        }
        $data['items'] = [];

        $result = \R::getAssoc('SELECT id, title, gender FROM ' . $tabl . ' WHERE title LIKE ? LIMIT 10', ["%{$q}%"]);
        if ($result) {
            $i = 0;
            foreach ($result as $id => $item) {
                $data['items'][$i]['id'] = $id;
                $data['items'][$i]['text'] = $item['title'] . ' | ' . $item['gender'];
                $i++;
            }
        }
        echo json_encode($data);
        die;
    }

    public function deleteGalleryAction() {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $src = isset($_POST['src']) ? $_POST['src'] : null;
        if (!$id || !$src) {
            return;
        }
        $modification = new Modification();
        $res = $modification->deleteGallery($id, $src);
        exit(json_encode($res));
    }

    public function deleteModificationAction() {
        $id = isset($_POST['id']) ? $_POST['id'] : null;

        if (!$id) {
            return;
        }
        $modification = new Modification();
        $res = $modification->deleteModification($id);
        exit(json_encode($res));
    }

    public function changeStatusAction() {
        if (!empty($_POST)) {
            $id = $_POST['id'] ?? 0;
            $id = (int) $id;
            $value = $_POST['value'] ?? 0;
            $value = (int) $value;
            if ($id && $value) {
                $res = Product::changeStatus($value, $id);
            } else {
                $res = ['succes' => false, 'title' => 'Данные не переданы', 'message' => 'Данные не переданы'];
            }
            exit(json_encode($res));
        }
        die;
    }

}
