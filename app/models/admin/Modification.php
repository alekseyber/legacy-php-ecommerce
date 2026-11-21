<?php

namespace app\models\admin;

use app\models\AppModel;

class Modification extends AppModel {

    public $attributes = [
        'product_id' => '',
        'color_id' => '',
        'imgto' => 0,
        'price' => 0,
        'old_price' => 0,
        'status' => 0,
        'bagdeproduct_id' => 0,
        'title' => '',
    ];
    public $rules = [
        'required' => [
            ['product_id'],
            ['color_id'],
        ],
        'integer' => [
            ['product_id'],
            ['color_id'],
            ['status'],
            ['bagdeproduct_id'],
        ],
    ];

    public function setDefaultImg($id = 0, $name = 'no_image.jpg') {
        $id = (int) $id;
        if ($id) {
            $gal_id = \R::getCell('SELECT `id` FROM `gallery` WHERE `modification_id` = ? AND `img` = ?', [$id, $name]);
            if (!$gal_id) {
                $res = ['succes' => false, 'name' => $name, 'error' => 'Это имя в таблице gallery не найдено'];
                return $res;
            }
            \R::begin();
            try {
                $gallery = \R::load('gallery', $gal_id);
                $modif = \R::load('modification', $id);
                $modif->img = $name;
                $gallery->main = 1;
                $sql = 'UPDATE `gallery` SET `main`= 0 WHERE `modification_id` = ? AND `main` = 1';
                \R::exec($sql, [$id]);
                \R::store($gallery);
                \R::store($modif);
                \R::commit();
            } catch (\Throwable $e) {
                error_log('[' . date('Y-m-d H:i:s') . '] ' . get_class($e) . ': ' . $e->getMessage()
                    . ' | ' . $e->getFile() . ':' . $e->getLine() . "\n", 3, ROOT . '/tmp/errors.log');
                \R::rollback();
                $res = ['succes' => false, 'name' => $name, 'error' => 'Внутренняя ошибка'];
                return $res;
            }
            $res = ['succes' => true, 'name' => $name];
        } else {
            $res = ['succes' => false, 'name' => $name, 'error' => 'id не передано'];
        }
        return $res;
    }

    public function setSortValImg($id = 0, $name = 'no_image.jpg', $val = 0) {
        $id = (int) $id;
        $val = (int) $val;
        if ($id) {
            $gal_id = \R::getCell('SELECT `id` FROM `gallery` WHERE `modification_id` = ? AND `img` = ?', [$id, $name]);
            if (!$gal_id) {
                $res = ['succes' => false, 'name' => $name, 'error' => 'Это имя в таблице gallery не найдено'];
                return $res;
            }
            $gallery = \R::load('gallery', $gal_id);
            $gallery->sortvalue = $val;
            $res = \R::store($gallery);
            if ($res) {
                $res = ['succes' => true, 'name' => $name];
            } else {
                $res = ['succes' => false, 'name' => $name, 'error' => 'Ошибка БД'];
            }

            return $res;
        }
    }

    public function setAmount($amount = []) {
        foreach ($amount as $k => $v) {
            $registr = \R::load('registr', $k);
            $registr->amount = $v;
            \R::store($registr);
        }
    }

    public function newAmount($data = [], $id) {
        $product_model = $data['product_model'];
        if ($product_model == '1' || $product_model == '4') {
            \R::begin();
            try {
                foreach ($data['amount'] as $sizes_id => $amount) {
                    $modificationregistr_data = [
                        'modification_id' => $id,
                        'sizes_id' => $sizes_id
                    ];
                    $modificationregistr_id = $this->saveData('modificationregistr', $modificationregistr_data);
                    $registr_data = [
                        'product_id' => $data['product_id'],
                        'modification_id' => $id,
                        'modificationregistr_id' => $modificationregistr_id,
                        'amount' => $amount
                    ];
                    $this->saveData('registr', $registr_data);
                }
                \R::commit();
            } catch (\Throwable $e) {
                error_log('[' . date('Y-m-d H:i:s') . '] ' . get_class($e) . ': ' . $e->getMessage()
                    . ' | ' . $e->getFile() . ':' . $e->getLine() . "\n", 3, ROOT . '/tmp/errors.log');
                \R::rollback();
                $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'Внутренняя ошибка'];
                return $res;
            }
            $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Модификация сохранена успешно'];
            return $res;
        }
        if ($product_model == '2' || $product_model == '3') {
            $registr_data = [
                'product_id' => $data['product_id'],
                'modification_id' => $id,
                'modificationregistr_id' => 0,
                'amount' => $data['amount']
            ];
            $rezult = $this->saveData('registr', $registr_data);
            if ($rezult) {
                $res = ['succes' => true, 'title' => 'Изменения сохранены', 'message' => 'Модификация сохранена успешно'];
            } else {
                $res = ['succes' => false, 'title' => 'Ошибка', 'message' => 'Регистр не сохранен'];
            }
            return $res;
        }
    }

    public function getModification($data) {
        $data['color_all'] = \R::getAssoc('SELECT id, title FROM color');
        $data['modification'] = \R::getRow('SELECT * FROM modification WHERE id = ? LIMIT 1', [$data['id']]);
        $data['name'] = $data['color_all'][$data['modification']['color_id']];
        $data['bagdeproduct'] = \R::getAssoc('SELECT id, title FROM bagdeproduct');
        if ($data['product_model'] == '1' || $data['product_model'] == '4') {
            $data['modificationregistr'] = \R::getAssoc('SELECT modificationregistr.id, sizes.title FROM modificationregistr JOIN sizes ON modificationregistr.sizes_id = sizes.id WHERE modification_id = ?', [$data['id']]);
            $data['registr'] = \R::getAssoc('SELECT modificationregistr_id, amount, id FROM registr WHERE modification_id = ?', [$data['id']]);
        } else {
            $data['modificationregistr'] = [];
            $data['registr'] = \R::getRow('SELECT id, amount FROM registr WHERE modification_id = ? LIMIT 1', [$data['id']]);
        }
        //$data['gallery'] = \R::getAssoc('SELECT * FROM gallery WHERE modification_id = ? ORDER BY sortvalue', [$data['id']]);
        return $data;
    }

    public function saveGallery($id) {
        if (!empty($_SESSION['multi'][$id])) {
            // Аудит 2026-11: имя файла подставлялось в SQL строкой; теперь — плейсхолдеры.
            $id = (int) $id;
            foreach ($_SESSION['multi'][$id] as $v) {
                \R::exec('INSERT INTO gallery (img, modification_id) VALUES (?, ?)', [$v, $id]);
            }
            unset($_SESSION['multi'][$id]);
        }
    }

    public function deleteGallery($id, $src) {
        if (\R::exec("DELETE FROM gallery WHERE modification_id = ? AND img = ?", [$id, $src])) {
            $params = \ishop\App::$app->getProperty('img_property');
            $original = WWW . '/' . \ishop\App::$app->getProperty('img_original_path') . $src;
            @unlink($original);
            foreach ($params as $item) {
                $img = WWW . '/' . $item['path'] . $src;
                @unlink($img);
            }
            $res = ['succes' => true, 'title' => 'Изображение удалено', 'message' => "Изображение $src удалено"];
        } else {
            $res = ['succes' => false, 'title' => 'Изображение не найдено', 'message' => "Изображение $src не найдено"];
        }
        return $res;
    }

    public function deleteModification($id) {

        $modification = \R::load('modification', $id);
        if (!$modification) {
            $res = ['succes' => false, 'title' => 'Модификация не найдена', 'message' => "Модификация не найдена"];
            return $res;
        }
        $gal = \R::getCol('SELECT img FROM gallery WHERE modification_id = ?', [$modification->id]);
        if ($gal) {
            foreach ($gal as $src) {
                $this->deleteGallery($id, $src);
            }
        }
        \R::exec("DELETE FROM modificationregistr WHERE modification_id = ?", [$id]);
        \R::exec("DELETE FROM registr WHERE modification_id = ?", [$id]);
        \R::trash($modification);
        $res = ['succes' => true, 'title' => 'Модификация удалена', 'message' => "Модификация удалена успешно"];

        return $res;
    }

    public function uploadImg($name, $file, $id = 0, $params = [], $uploaddir = false, $parse = false) {
        if (!$uploaddir) {
            $uploaddir = WWW . '/' . \ishop\App::$app->getProperty('img_original_path');
        }
        if (!$params) {
            $params = \ishop\App::$app->getProperty('img_property');
        }

        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $file['name'])); // расширение картинки
        $types = array("image/gif", "image/png", "image/jpeg", "image/pjpeg", "image/x-png"); // массив допустимых расширений
        if ($file['size'] > 2097152) {
            $res = ['succes' => false, 'title' => 'Ошибка!', 'message' => "Ошибка! Максимальный вес файла - 2 Мб!"];
            return $res;
            //$res = array("error" => "Ошибка! Максимальный вес файла - 2 Мб!");
            //exit(json_encode($res));
        }
        if ($file['error']) {
            $res = ['succes' => false, 'title' => 'Ошибка!', 'message' => "Ошибка! Возможно, файл слишком большой."];
            return $res;
            //$res = array("error" => "Ошибка! Возможно, файл слишком большой.");
            //exit(json_encode($res));
        }
        if (!in_array($file['type'], $types)) {
            $res = ['succes' => false, 'title' => 'Ошибка!', 'message' => "Допустимые расширения - .gif, .jpg, .png"];
            return $res;
            // $res = array("error" => "Допустимые расширения - .gif, .jpg, .png");
            // exit(json_encode($res));
        }
        //$new_name = md5(time()) . ".$ext";
        $new_name = md5(uniqid(rand(), true) . time()) . ".$ext";
        $uploadfile = $uploaddir . $new_name;
        $run = false;
        if ($parse) {
            $run = copy($file['tmp_name'], $uploadfile);
        } else {
            if (@move_uploaded_file($file['tmp_name'], $uploadfile)) {
                $run = true;
            }
        }

        if ($run) {
            if ($name == 'single') {
                $_SESSION['single'][$id] = $new_name;
                $name = false;
            } else {
                $_SESSION['multi'][$id][] = $new_name;
                $name = true;
            }

            foreach ($params as $item) {
                $dest = WWW . '/' . $item['path'] . $new_name;
                $wmax = $item['img_width'];
                $hmax = $item['img_height'];
                self::resize($uploadfile, $dest, $wmax, $hmax, $ext);
            }
            $res = ['succes' => true, 'name' => $new_name, 'id' => $id, 'title' => 'Успешно', 'message' => "Картинки успешно добавлены", 'multi' => $name];
            // $res = array("file" => ['name' => $new_name]);           
        } else {
            $res = ['succes' => false, 'title' => 'Ошибка!', 'message' => "Ошибка перемещения файла"];
        }
        return $res;
    }

    /**
     * @param string $target путь к оригинальному файлу
     * @param string $dest путь сохранения обработанного файла
     * @param string $wmax максимальная ширина
     * @param string $hmax максимальная высота
     * @param string $ext расширение файла
     */
    public static function resize($target, $dest, $wmax, $hmax, $ext) {
        list($w_orig, $h_orig) = getimagesize($target);
        $ratio = $w_orig / $h_orig; // =1 - квадрат, <1 - альбомная, >1 - книжная

        if (($wmax / $hmax) > $ratio) {
            $wmax = $hmax * $ratio;
        } else {
            $hmax = $wmax / $ratio;
        }

        $img = "";
        // imagecreatefromjpeg | imagecreatefromgif | imagecreatefrompng
        switch ($ext) {
            case("gif"):
                $img = imagecreatefromgif($target);
                break;
            case("png"):
                $img = imagecreatefrompng($target);
                break;
            default:
                $img = imagecreatefromjpeg($target);
        }
        $newImg = imagecreatetruecolor($wmax, $hmax); // создаем оболочку для новой картинки

        if ($ext == "png") {
            imagesavealpha($newImg, true); // сохранение альфа канала
            $transPng = imagecolorallocatealpha($newImg, 0, 0, 0, 127); // добавляем прозрачность
            imagefill($newImg, 0, 0, $transPng); // заливка
        }

        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig); // копируем и ресайзим изображение
        switch ($ext) {
            case("gif"):
                imagegif($newImg, $dest);
                break;
            case("png"):
                imagepng($newImg, $dest);
                break;
            default:
                imagejpeg($newImg, $dest);
        }
        $webp_on = \ishop\App::$app->getProperty('webp_on');
        if ($webp_on) {
            $quality = \ishop\App::$app->getProperty('webp_quality');
            $pattern = "/\.(png|jpg|gif)$/i";
            $webPDest = preg_replace($pattern, ".webp", $dest);
            imagewebp($newImg, $webPDest, $quality);
        }

        imagedestroy($newImg);
    }

    public static function greatWebp($dest) {
        $ext = FALSE;
        $extSuccess = ['jpg', 'png', 'gif'];
        $webPDest = '';

        foreach ($extSuccess as $value) {
            $pattern = "/\.$value$/i";
            if (preg_match($pattern, $dest)) {
                $ext = $value;
                $webPDest = preg_replace($pattern, ".webp", $dest);
            }
        }

        if ($ext === FALSE) {
            return false;
        }
        $img = "";

        switch ($ext) {
            case("gif"):
                $img = imagecreatefromgif($dest);
                break;
            case("png"):
                $img = imagecreatefrompng($dest);
                imagepalettetotruecolor($img);
                imagealphablending($img, true);
                imagesavealpha($img, true);
                break;
            default:
                $img = imagecreatefromjpeg($dest);
        }
        $quality = \ishop\App::$app->getProperty('webp_quality');
        $rezult = imagewebp($img, $webPDest, $quality);
        imagedestroy($img);
        return $rezult;
    }

}
