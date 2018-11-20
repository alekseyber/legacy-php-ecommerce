<?php

namespace app\controllers\admin;

use app\models\admin\Csv;
use ishop\libs\CsvImporter;

class CsvController extends AppController {

    public function uploadAction() {
        $action = $_POST['action'] ?? '';
        if ($action) {
            if (property_exists('Csv', $action)) {
                $_SESSION['error'] = "$action таблицы не существует в модели Csv";
                redirect();
            }
        }
        $data = \R::getAll("SELECT * FROM $action");
        $fields = \R::inspect($action);
        foreach ($fields as $field => $type) {
            $head[] = $field;
        }

// output up to 5MB is kept in memory, if it becomes bigger it will automatically be written to a temporary file
        $csv = fopen('php://temp/maxmemory:' . (5 * 1024 * 1024), 'r+');

        fputcsv($csv, $head, ';');
        foreach ($data as $item) {
            fputcsv($csv, $item, ';');
        }

        rewind($csv);
        $output = stream_get_contents($csv);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $action . '.csv');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        //header('Content-Length: ' . $output);
        print $output;
        //print fread($output, (5 * 1024 * 1024));
        //exit;

        $_SESSION['success'] = 'Данные выгружены из таблицы' . $action;
        //redirect();
        exit;
    }

    public function loadAction() {
        $action = $_POST['action'] ?? '';
        $csv = $_FILES['csv'] ?? [];
        $key_name = $_POST['key_name'] ?? 'id';
        if ($action && $csv) {
            if (property_exists('Csv', $action)) {
                $_SESSION['error'] = "$action таблицы не существует в модели Csv";
                redirect();
            }
            $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $csv['name'])); // расширение картинки
            $type = 'application/vnd.ms-excel'; // допустимое расширение
            if ($csv['size'] > 2097152) {
                $res = ['succes' => false, 'title' => 'Ошибка!', 'message' => "Ошибка! Максимальный вес файла - 2 Мб!"];
                $_SESSION['error'] = "Ошибка! Максимальный вес файла - 2 Мб!";
                redirect();
            }
            if ($csv['error']) {
                $_SESSION['error'] = "Ошибка! Возможно файл очень большой или не загружен!";
                redirect();
            }
            if ($csv['type'] != $type) {
                $_SESSION['error'] = "Ошибка! Допустимые расширения - .csv";
                redirect();
            }

//Sample usage for small files:- 
            $importer = new CsvImporter($csv['tmp_name'], true);
            $data = $importer->get();
            $data = $importer->getArr($data);
            if (!array_key_exists($key_name, $data[0])) {
                $_SESSION['error'] = "Ошибка! Ключа $key_name нет в переданном массиве";
                redirect();
            }
            $obj = new Csv ();
            $obj->updateTable($data, $action, $key_name);
            //debug($data);
            //Sample usage for large files:- Sample usage for large files:-
//            $importer = new CsvImporter($csv['tmp_name'], true);
//            while ($data = $importer->get(2000)) {
//                debug($data);
//            }
        }

        $_SESSION['success'] = 'Данные обновлены';
        redirect();
        die;
    }

}
