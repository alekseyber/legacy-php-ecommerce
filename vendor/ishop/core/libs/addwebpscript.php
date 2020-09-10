<?php

//define("WWW", dirname(__DIR__) . '/public_html/public');
define("WWW",  dirname(__DIR__). "/demo-shop.example/public_html/public");
define("QUALITY", 60);


function greatWebp($dest) {
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

    $rezult = imagewebp($img, $webPDest, QUALITY);
    imagedestroy($img);
    return $rezult;
}

function updatewebpAction() {
    // set_time_limit(0);
    $timestartsec = time();
    $timestart = date('l jS \of F Y h:i:s A');
    echo "Процесс создания Webp запущен $timestart" . PHP_EOL;
    
    echo dirname(__DIR__). PHP_EOL;
    
    echo WWW . PHP_EOL;

    $params = [
        WWW . '/images/product/1500/',
        WWW . '/images/product/1000/',
        WWW . '/images/product/300/',
        WWW . '/images/product/160/',
        WWW . '/images/category/',
        WWW . '/images/news/'
    ];
    
    $countDel = 0;
    $countForDel = 0;
    $countAdd = 0;

    $pattern = '/\.webp$/';

    print_r($params);

    foreach ($params as $dir) {

        $files = scandir($dir);
        echo $dir . PHP_EOL;
        $countFiles = count($files);
        echo "Количество файлов: $countFiles" . PHP_EOL;
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && $file !== 'index.html') {

                if (preg_match($pattern, $file)) {
                    $countForDel++;
                    $fullPath = $dir . $file;
                    $rezult = unlink($fullPath);
                    if ($rezult) {
                        $countDel++;
                    }
                }
            }
        }
    }

    $patternimg = "/\.(png|jpg|gif)$/i";

    foreach ($params as $dir) {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && $file !== 'index.html') {

                if (preg_match($patternimg, $file)) {

                    $fullPath = $dir . $file;
                    $rezult = greatWebp($fullPath);

                    if ($rezult) {
                        $countAdd++;
                    }
                }
            }
        }
    }

    $timemin = (time() - $timestartsec) / 60;
    $timeend = date('l jS \of F Y h:i:s A');
    echo "Процесс создания Webp завершен $timeend" . PHP_EOL;
    echo "Найдено webp файлов: $countForDel, удалено webp файлов: $countDel, создано webp файлов: $countAdd, время выполнения: $timemin минут.";
}

updatewebpAction();