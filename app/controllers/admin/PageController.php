<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;
use app\models\admin\Page;

class PageController extends AppController {

    public function indexAction() {
        $this->setMeta('Список страниц');
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 50;
        $count = \R::count('page', 'main = 0');
        $pagination = new Pagination($page, $perpage, $count, true);
        $start = $pagination->getStart();
        $pages = \R::find('page', 'main = 0');
        $this->set(compact('pages', 'pagination', 'count'));
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $page = \R::load('page', $id);
        if ($page->main) {
            $_SESSION['error'] = "Удаление главной страницы не возможно";
            redirect();
        }
        $alias = 'page/' . $page->alias;
        delSef($alias);
        \R::trash($page);
        $_SESSION['success'] = 'Страница удалена';
        redirect();
    }

    public function addAction() {
        if (!empty($_POST)) {
            $page = new Page();
            $data = $_POST;

            $data['update_at'] = date('Y-m-d H:i:s');
            $page->load($data);
            if (!$page->validate($data)) {
                $page->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $page->save('page')) {
                $alias = AppModel::createAlias('page', 'alias', $data['title'], $id);
                $pp = \R::load('page', $id);
                $pp->alias = $alias;
                \R::store($pp);
                $seolink_temp = $alias;
                $seolink = AppModel::createSeolink($seolink_temp, $id);
                $sef = \R::dispense('sef');
                $sef->alias = 'page/' . $alias;
                $sef->link = $seolink;
                $res = \R::store($sef);
                if ($res) {
                    $p = \R::load('page', $id);
                    $p->seolink = $seolink;
                    \R::store($p);
                }
                $_SESSION['success'] = 'Страница добавлена';
                redirect(ADMIN . '/page/edit?id=' . $id);
            }
            redirect();
        }
        $this->setMeta('Новая страница');
    }

    public function editAction() {

        if (isset($_GET['main'])) {
            $id = \R::getCell('SELECT `id` FROM `page` WHERE `main` = 1');
            $main = true;
            $ddd = 10;
        } else {
            $id = $_REQUEST['id'] ?? 0;
            $id = (int) $id;
            $main = false;
            $ddd = 20;
        }
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }

        if (!empty($_POST)) {
            $page = new Page();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            $page->load($data);
            $main = $data['main'];
            if (!$page->validate($data)) {
                $page->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }

            if ($page->update('page', $id)) {
                if ($main) {
                    $lastmodified_id = \R::getCell("SELECT `id` FROM `lastmodified` WHERE `controler` = 'main'");
                    $lastmodified = \R::load('lastmodified', $lastmodified_id);
                    $lastmodified->update_at = date('Y-m-d H:i:s');
                    \R::store($lastmodified);
                }
                if ($this->isAjax()) {
                    $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Страница сохранена'];
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
        $page = \R::load('page', $id);
        $str = $main ? 'Главной ' : '';
        $this->setMeta("Редактирование {$str}страницы {$page->title}");
        $this->set(compact('page', 'main', 'str'));
    }

}
