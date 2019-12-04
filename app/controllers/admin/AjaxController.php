<?php

namespace app\controllers\admin;

use app\models\AppModel;
use ishop\App;
use ishop\Cache;

class AjaxController extends AppController {

    public function typeaheadAction() {
        if ($this->isAjax()) {
            $data = $_GET['data'] ?? null;
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
//$data = 'category,title,id';            
            $data = explode(',', $data);
            $key = $data[2] ?? 'id';
            if ((is_array($data) && count($data) > 1) && $query) {
                $rezult = \R::getAll("SELECT $key AS id, {$data[1]} as title FROM {$data[0]} WHERE {$data[1]} LIKE ? LIMIT 11", ["%{$query}%"]);
                echo json_encode($rezult);
            }
        }
        die;
    }

    public function formAction() {
        // if ($this->isAjax()) {
        //  $request = $_REQUEST;
        //  debug($request,1);
        //   echo json_encode($reqvest);
        //    }
        die;
    }

    public function editseolinkAction() {
        if ($this->isAjax()) {
            $request = $_REQUEST;
            $succes = false;
            $title = 'Ошибка валидации';
            $message = 'Ошибка БД';
            $table = $request['table'] ?? '';
            $seolink = $request['seolink'] ?? '';
            $newseolink = $request['newseolink'] ?? '';
            $table = trim($table);
            $seolink = trim($seolink);
            $newseolink = trim($newseolink);
            $newseolink = trim($newseolink, '/');
            $newseolink = strtolower($newseolink);
            $newseolink = preg_replace("/[^a-z0-9\/\-]/", '', $newseolink); // только цифры прописные латинские / и -          
            $newseolink = preg_replace("/-+/", "-", $newseolink); // множественные - заменяем на одинарные
            if ($newseolink) {
                $count = \R::count('sef', ' link = ? ', [$newseolink]);
                if ($count > 0) {
                    $message = 'Такой адрес уже занят';
                } else {
                    $sefbean = \R::findOne('sef', ' link = ? ', [$seolink]);
                    if ($sefbean) {
                        $table = \R::findOne($table, ' seolink = ? ', [$seolink]);
                        if ($table) {

                            \R::begin();
                            try {
                                $sefbean->link = $newseolink;
                                $table->seolink = $newseolink;
                                \R::store($sefbean);
                                \R::store($table);
                                \R::commit();
                            } catch (Exception $e) {
                                \R::rollback();
                            }

                            $succes = true;
                            $title = 'Успех';
                            $message = 'Изменения охранены';
                        } else {
                            $message = 'Старый адрес не найден';
                        }
                    }
                }
            } else {
                $message = 'После удаления запрещенных символов - значение пустое';
            }
            $res = ['succes' => $succes, 'title' => $title, 'message' => $message, 'newseolink' => $newseolink];
            exit(json_encode($res));
        }
        die;
    }

}
