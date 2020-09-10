<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;
use app\models\admin\News;
use app\models\admin\Modification;

class NewsController extends AppController {

    public function indexAction() {
        $this->setMeta('Список Новостей');
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 50;
        $count = \R::count('news');
        $pagination = new Pagination($page, $perpage, $count, true);
        $start = $pagination->getStart();
        $news = \R::getAll('SELECT `id`, `title`, `update_at`, `status` FROM `news` ORDER BY `update_at` DESC');
        $this->set(compact('news', 'pagination', 'count'));
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $news = \R::load('news', $id);
        $alias = 'news/' . $news->alias;
        delSef($alias);
        \R::trash($news);
        $_SESSION['success'] = 'Новость удалена';
        redirect();
    }

    public function addAction() {
        if (!empty($_POST)) {
            $news = new News();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            $news->load($data);
            if (!$news->validate($data)) {
                $news->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $news->save('news')) {
                $alias = AppModel::createAlias('news', 'alias', $data['title'], $id);
                $pp = \R::load('news', $id);
                $pp->alias = $alias;
                \R::store($pp);
                $seolink_temp = 'news/' . $alias;
                $seolink = AppModel::createSeolink($seolink_temp, $id);
                $sef = \R::dispense('sef');
                $sef->alias = 'news/' . $alias;
                $sef->link = $seolink;
                $res = \R::store($sef);
                if ($res) {
                    $p = \R::load('news', $id);
                    $p->seolink = $seolink;
                    \R::store($p);
                }
                $lastmodified_id = \R::getCell("SELECT `id` FROM `lastmodified` WHERE `controler` = 'news'");
                $lastmodified = \R::load('lastmodified', $lastmodified_id);
                $lastmodified->update_at = date('Y-m-d H:i:s');
                \R::store($lastmodified);
                $_SESSION['success'] = 'Новость добавлена';
                redirect(ADMIN . '/news/edit?id=' . $id);
            }
            redirect();
        }
        $this->setMeta('Новая Новость');
    }

    public function editAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestID(false);
            $news = new News();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            $news->load($data);
            if (!$news->validate($data)) {
                $news->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }
            if ($news->update('news', $id)) {
                if ($this->isAjax()) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Новость сохранена'];
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
        $news = \R::load('news', $id);

        $this->setMeta("Редактирование Новости {$news->title}");
        $this->set(compact('news'));
    }

    public function addImageAction() {
        $id = $_GET['id'] ?? 0;
        $id = (int) $id;

        $file = $_FILES['file'] ?? [];
        if (!$file) {
            $res[0] = ['succes' => false, 'name' => '', 'error' => 'Ошибка! Файлы не загружены'];
            exit(json_encode($res));
        }

        $uploaddir = WWW . '/' . \ishop\App::$app->getProperty('img_news_original_path');
        $params = \ishop\App::$app->getProperty('img_news_property');
        $name = 'single';

        $modification = new Modification();
        $res[0] = $modification->uploadImg($name, $file, $id, $params, $uploaddir);
        $res[0]['path'] = $params['410']['path'];
        $name = $_SESSION['single'][$id] ?? '';
        if ($id && $name) {
            unset($_SESSION['single'][$id]);
            $n = \R::load('news', $id);
            if ($n) {
                $n->img = $name;
                \R::store($n);
            }
        }

        exit(json_encode($res));
    }

}
