<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use ishop\libs\Pagination;
use app\models\admin\Comment;

class CommentController extends AppController {

    public function indexAction() {
        $this->setMeta('Список Комментариев');
        $id = $_GET['id'] ?? 0;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perpage = 10;
        $pagination = '';
        if ($id) {
            $count = 1;
            $comments = \R::find('comment', 'id = ?', [$id]);
        } else {
            $count = \R::count('comment');
            $pagination = new Pagination($page, $perpage, $count, true, false, '', true);
            $start = $pagination->getStart();
            $comments = \R::find('comment', " ORDER BY status, date DESC LIMIT $start, $perpage");
        }

        $this->set(compact('comments', 'pagination', 'count'));
    }

    public function deleteAction() {
        $id = $this->getRequestID();
        $comment = \R::load('comment', $id);
        $name = $comment->author_name;
        \R::trash($comment);
        $_SESSION['success'] = "Комментарий ID: $id от $name удален";
        redirect();
    }

    public function changeStatusAction() {
        if (!empty($_POST)) {
            $id = $_POST['id'] ?? 0;
            $id = (int) $id;
            $value = $_POST['value'] ?? 0;
            $value = (int) $value;
            $name = $_GET['name'] ?? '';

            if ($id && ($name == 'status' || $name == 'social')) {
                $res = Comment::changeStatus($value, $id, $name);
                if ($name == 'status') {
                    $lastmodified_id = \R::getCell("SELECT `id` FROM `lastmodified` WHERE `controler` = 'comment'");
                    $lastmodified = \R::load('lastmodified', $lastmodified_id);
                    $lastmodified->update_at = date('Y-m-d H:i:s');
                    \R::store($lastmodified);
                }
            } else {
                $res = ['succes' => false, 'title' => 'Данные не переданы', 'message' => 'Данные не переданы'];
            }
            exit(json_encode($res));
        }
        die;
    }

}
