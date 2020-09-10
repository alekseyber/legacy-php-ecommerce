<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\admin\Category;
use ishop\App;
use ishop\libs\Pagination;
use app\models\admin\Modification;

class CategoryController extends AppController {

    public function indexAction() {
        $this->setMeta('Список категорий');
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $children = \R::count('category', 'parent_id = ?', [$id]);
        $errors = '';
        if ($children) {
            $errors .= '<li>Удаление невозможно, в категории есть вложенные категории</li>';
        }
        $products = \R::count('product', 'category_id = ?', [$id]);
        if ($products) {
            $errors .= '<li>Удаление невозможно, в категории есть товары</li>';
        }
        if ($errors) {
            $_SESSION['error'] = "<ul>$errors</ul>";
            redirect();
        }
        $category = \R::load('category', $id);
        $alias = 'category/' . $category->alias;
        delSef($alias);
        \R::trash($category);
        $_SESSION['success'] = 'Категория удалена';
        redirect();
    }

    public function addAction() {

        if (!empty($_POST)) {
            $category = new Category();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            $category->load($data);
            if (!$category->validate($data)) {
                $category->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $category->save('category')) {
                $alias = AppModel::createAlias('category', 'alias', $data['title'], $id);
                $cat = \R::load('category', $id);
                $cat->alias = $alias;
                \R::store($cat);
                $category_seolink = \R::getCell('SELECT seolink FROM category WHERE id = ?', [$data['parent_id']]);
                if ($category_seolink) {
                    $seolink_temp = $category_seolink . '/' . $alias;
                } else {
                    $seolink_temp = $alias;
                }
                $seolink = AppModel::createSeolink($seolink_temp, $id);
                $sef = \R::dispense('sef');
                $sef->alias = 'category/' . $alias;
                $sef->link = $seolink;
                $res = \R::store($sef);
                if ($res) {
                    $c = \R::load('category', $id);
                    $c->seolink = $seolink;
                    \R::store($c);
                }
                $_SESSION['success'] = 'Категория добавлена';
                redirect(ADMIN . '/category/edit?id=' . $id);
            }
            redirect();
        }
        $this->setMeta('Новая категория');
    }

    public function editAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $category = new Category();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            $category->load($data);
            if ($data['cat_default']) {
                unset($category->rules['min']);
            }
            if (!$category->validate($data)) {
                $category->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }
            if ($category->update('category', $id)) {
                //$alias = AppModel::createAlias('category', 'alias', $data['title'], $id);
                //$category = \R::load('category', $id);
                //$category->alias = $alias;
                //\R::store($category);
                if ($this->isAjax()) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Категория сохранена'];
                    exit(json_encode($res));
                } else {
                    $_SESSION['success'] = 'Изменения сохранены';
                    redirect();
                }
            }
            if ($this->isAjax()) {
                $res = ['succes' => false, 'title' => 'Изменения не сохранены', 'message' => 'Ошибка БД'];
                exit(json_encode($res));
            } else {
                redirect();
            }
        }
        $id = $this->getRequestID();
        $category = \R::load('category', $id);
        App::$app->setProperty('parent_id', $category->parent_id);
        $this->setMeta("Редактирование категории {$category->title}");
        $this->set(compact('category'));
    }

    public function addImageAction() {
        $id = $_GET['id'] ?? 0;
        $id = (int) $id;
        $thumb = $_GET['thumb'] ?? FALSE;
        
        
        $file = $_FILES['file'] ?? [];
        if (!$file) {
            $res[0] = ['succes' => false, 'name' => '', 'error' => 'Ошибка! Файлы не загружены'];
            exit(json_encode($res));
        }
        
        $uploaddir = WWW . '/' . \ishop\App::$app->getProperty('img_category_original_path');
        if ($thumb) {
            $namefield = 'img_thumb';
            $params = \ishop\App::$app->getProperty('img_thumb_category_property');
            
        } else {
          $params = \ishop\App::$app->getProperty('img_category_property');
          $namefield = 'img';
        }
        
        $name = 'single';

        $modification = new Modification();
        $res[0] = $modification->uploadImg($name, $file, $id, $params, $uploaddir);
        $res[0]['path'] = $params['150']['path'];
        if ($thumb) {
            $res[0]['divIdPr'] = '#multitr';
        }
        $name = $_SESSION['single'][$id] ?? '';
        if ($id && $name) {
            unset($_SESSION['single'][$id]);
            $cat = \R::load('category', $id);
            if ($cat) {
                $cat->$namefield = $name;
                \R::store($cat);
            }
        }

        exit(json_encode($res));
    }

}