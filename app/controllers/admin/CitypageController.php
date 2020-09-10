<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;
use app\models\admin\Citypage;
use app\models\admin\Citysettings;

class CitypageController extends AppController {

//citypage
    public function indexAction() {
        $this->setMeta('Список страниц городов');
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 50;
        $count = \R::count('citypage');
        $pagination = new Pagination($page, $perpage, $count, true);
        $start = $pagination->getStart();
        $citypages = \R::find('citypage');
        $this->set(compact('citypages', 'pagination', 'count'));
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $citypage = \R::load('citypage', $id);

        $alias = 'citypage/' . $citypage->alias;
        delSef($alias);
        \R::trash($citypage);
        $_SESSION['success'] = 'Страница города удалена';
        redirect();
    }

    public function addAction() {



        if (!empty($_POST)) {

            $citypage = new Citypage();
            $data = $_POST;

            $data['update_at'] = date('Y-m-d H:i:s');
            $citypage->load($data);
            if (!$citypage->validate($data)) {
                $citypage->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $citypage->save('citypage')) {
                $alias = AppModel::createAlias('citypage', 'alias', $data['title'], $id);
                $pp = \R::load('citypage', $id);
                $pp->alias = $alias;
                \R::store($pp);
                $seolink_temp = $alias;
                $seolink = AppModel::createSeolink($seolink_temp, $id);
                $sef = \R::dispense('sef');
                $sef->alias = 'citypage/' . $alias;
                $sef->link = $seolink;
                $res = \R::store($sef);
                if ($res) {
                    $p = \R::load('citypage', $id);
                    $p->seolink = $seolink;
                    \R::store($p);
                }
                $_SESSION['success'] = 'Страница города добавлена';
                redirect(ADMIN . '/citypage/edit?id=' . $id);
            }
            redirect();
        }
        $this->setMeta('Новая страница города');
    }

    public function editAction() {

        //  debug($_POST,1);
        //  $id = $this->getRequestID();

        $id = $_REQUEST['id'] ?? 0;
        $id = (int) $id;



        if (!empty($_POST)) {
            $citypage = new Citypage();
            $data = $_POST;
            $data['update_at'] = date('Y-m-d H:i:s');
            $citypage->load($data);




            if (!$citypage->validate($data)) {
                $citypage->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }

            if ($citypage->update('citypage', $id)) {

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
        $citypage = \R::load('citypage', $id);

        if (!$citypage->id) {
            $_SESSION['error'] = 'Сттраница не найдена';
            redirect(ADMIN . '/citypage');
        }

        $this->setMeta("Редактирование страницы г. {$citypage->city_name}");
        $this->set(compact('citypage'));
    }

    public function editSettingsAction() {
        
        $id = \R::getCell('SELECT `id` FROM `citysettings` WHERE `selected` = 1 LIMIT 1');

        if (!empty($_POST)) {
            $citysettings = new Citysettings();
            $data = $_POST;

            $citysettings->load($data);

            if (!$citysettings->validate($data)) {
                $citysettings->getErrors();
                if ($this->isAjax()) {
                    $err = $_SESSION['error'];
                    unset($_SESSION['error']);
                    $res = ['succes' => false, 'title' => 'Ошибка валидации', 'message' => $err];
                    exit(json_encode($res));
                } else {
                    redirect();
                }
            }

            if ($citysettings->update('citysettings', $id)) {

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
        $citysettings = \R::load('citysettings', $id);

        $this->setMeta("Редактирование общих настроек городов");
        $this->set(compact('citysettings'));
    }

}
