<?php

namespace app\controllers;

use ishop\App;
//use app\widgets\menu\Menu;
use app\widgets\filter\Filter;
use ishop\libs\Pagination;
//use ishop\Link;
use app\widgets\jsinclud\Jsinclud;
use ishop\Recaptcha;
use app\models\Comment;

class CommentController extends AppController {

    public function indexAction() {
        if (App::$app->getProperty('last-modified_header_on') && !$this->json) {
            $lastmodified = self::getLastmodified('comment');
            self::setLastmodified($lastmodified['update_at']);
        }
        self::uriChek();
        $linkCanonical = PATH . '/comment';
        $getArr = App::$app->getProperty('chek_get');
        $page = isset($getArr['page']) ? $getArr['page'] : 1;
        $perpage = App::$app->getProperty('pagination_comment');
        $postfix_meta = "";
        $paginContent = '<p class="text-center h4">Отзывы пока отсутствуют</p>';
        
        if ($page > 1) {
            $linkCanonical .= '?page=' . $page;
            $postfix_meta = " - Страница " . $page;
        }
        $total = \R::count('comment', 'status = 1');
        $pagination = new Pagination($page, $perpage, $total, false, true);
        $start = $pagination->getStart();
        $sql_part = "SELECT * FROM `comment` WHERE `status` = 1 ORDER BY `date` DESC LIMIT $start, $perpage";
        if ($perpage >= $total)
            $paginationstr = '';
        else
            $paginationstr = $pagination;

        $comment = \R::getAssoc($sql_part);

        $paginInContent = '';

        if (count($comment)) {
            $paginContent = $this->loadViewContent('view_pagin', compact('comment', 'paginationstr'));
            $paginInContent = $paginContent;
        }

        if ($this->json && isset($_POST['pagin'])) {
            //  $this->loadView('view_pagin', compact('comment', 'paginationstr'));
            exit(json_encode(['content' => $paginInContent]));
        }

        $shop_name = App::$app->getProperty('shop_name');
        $shop_tel = App::$app->getProperty('shop_tel');
        $shop_tel_link = App::$app->getProperty('shop_tel_link');
        $contData['meta_title'] = "Интернет-магазин $shop_name - Отзывы" . $postfix_meta;
        $contData['meta_description'] = "Интернет-магазин $shop_name отзывы клиентов." . $postfix_meta;
        $contData['meta_keywords'] = "отзывы";
        $contData['htitle'] = "Отзывы $shop_name" . $postfix_meta;
        $contData['promo'] = '';
        $contData['data_link_add_comment'] = h(json_encode(['id' => 0, 'name' => 'collapseAddComment', 'modal' => false, 'block' => true, 'rezult_id' => 'comment-edit']));

        // $menuCatalog = new Menu([
        //     'tpl' => WWW . '/menu/menu.php',
        //     'class' => 'nav-left nav-pills refinements-item-catproduct nav-stacked',
        // ]);
        // $product_ids = App::$app->getProperty('product_ids');
        // $uri = App::$app->getProperty('filter_default_uri');
        // //$filter = new Filter($product_ids, $uri, WWW . '/filter/filter_main_tpl.php');
        // $filter = new Filter([
        //     'ids' => $product_ids,
        //     'uri' => $uri,
        //     'tpl' => WWW . '/filter/filter_main_tpl.php'            
        // ]);
        $product_ids = App::$app->getProperty('product_ids');
        $uri = App::$app->getProperty('filter_default_uri');
        $leftFilter = new Filter([
            'ids' => $product_ids,
            'uri' => $uri,
        ]);
        // $this->setJs($filter->js, false, 'Filter Price');
        //  $js_w = new Jsinclud(3);
        // if ($this->isAjax()) {
        //     $js = $filter->js;
        //     $this->loadView('index', compact('comment', 'filter', 'menuCatalog', 'contData', 'paginationstr'), $js);
        // }

        if ($this->json) {
            //         'content' => $this->loadViewContent('index', compact('comment', 'contData', 'paginationstr')),

            $res = [
                'content' => $this->loadViewContent('index', compact('paginContent', 'contData')),
                'historyset' => 1,
                'scrolltop' => 1,
                'filter' => $leftFilter->toString(),                 
            ];

            exit(json_encode($res));
        }
        App::$app->setProperty('filter_content', $leftFilter);

        //   $this->setJs($js_w, false, 'Auth block');

        $this->setMeta($contData['meta_title'], $contData['meta_description'], $contData['meta_keywords'], $linkCanonical);
        $this->set(compact('paginContent', 'contData'));
    }

    public function addAction() {
        if ($this->json) {            
            if (isset($_POST['g-recaptcha-response'])) {
                //  $rekey = \ishop\App::$app->getProperty('reCAPTCHA');
                $response = $_POST['g-recaptcha-response'];
                $re = Recaptcha::instance();
                $reRezult = $re->verifyResponse($response);
                if ($reRezult === true) {
                    $comment = new Comment();
                    $data = $_POST;

                    $new_data['author_name'] = $data['author']['name'] ?? '';
                    $new_data['author_avatar'] = $data['author']['avatar'] ?? '';
                    $new_data['author_provider'] = $data['author']['provider'] ?? '';
                    $new_data['author_uid'] = $data['author']['uid'] ?? '';
                    $new_data['commen_text'] = $data['commenttext'] ?? '';

                    if (!$new_data['author_provider'] == 'vk' || !$new_data['author_provider'] == 'fb') {
                        $new_data['author_provider'] = '';
                    }
                    if (!$new_data['author_name']) {
                        $new_data['author_name'] = $data['name'] ?? '';
                    }
                    foreach ($new_data as &$val) {
                        $val = trim($val);
                    }
                    $new_data['social'] = 1;
                    if (!$new_data['author_avatar'] || !$new_data['author_provider'] || !$new_data['author_uid']) {
                        $new_data['social'] = 0;
                        $new_data['author_avatar'] = '';
                        $new_data['author_provider'] = '';
                        $new_data['author_uid'] = '';
                    }
                    $comment->load($new_data);
                    if (!$comment->validate($new_data)) {
                        $comment->getErrors();
                        $data = ['succes' => false, 'message' => $_SESSION['error']];
                        //unset($_SESSION['error']);
                    } else {
                        $comment->hChek();
                        $comment->saveComment();
                        $comment->mailComment();
                        $data = ['succes' => true, 'message' => 'Благодарим Вас за отзыв! В ближайшее время он появится на нашем сайте.'];
                    }
                } else {
                    $data = ['succes' => false, 'message' => 'Ошибка связи, повторите отправку формы.'];
                }
                // echo json_encode($data);
                // die;

                exit(json_encode($data));
            } else {
                exit(json_encode([
                    'content' => $this->loadViewContent('collapseaddcomment'),
                    'message' => '',
                    'succes' => true,
                ]));

                //  $this->loadView('collapseaddcomment');
            }
        } else {
            throw new \Exception('Страница не найдена', 404);
        }
      
    }

}
