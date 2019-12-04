<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>

        <table style="border: 1px solid #ddd; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background: #f9f9f9;">
                    <th colspan="2" style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;">Данные сообщения</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Имя:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $name ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Телефон:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><a href="tel:+<?= $phone ?>">+<?= $phone ?></a></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Сообщение:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $note ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">IP:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= long2ip($ip) ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Город по IP:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $geo_city ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Регион по IP:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $geo_region ?></td>
                </tr>
                <?php if ($product): ?>
                    <?php $product['img'] = ltrim($product['img'], '/') ?>
                    <tr>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;"><strong>Быстрый заказ:</strong></td>
                    </tr>
                    <tr>                        
                        <td colspan="2" style="padding: 8px; border: 1px solid #ddd; width: 70%;">
                            <div>Цена: <?= $product['price'] ?></div>
                            <div><a href="<?= PATH . '/' . $product['link'] ?>" target="_blank"><?= $product['title'] ?></a></div>                      

                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center"><img style="max-width: 100%; max-height: auto;" src="<?= PATH . '/' . $product['img'] ?>" alt="img"></td>
                    </tr>
                <?php endif ?>
                <?php if ($acquirers_status): ?>
                    <tr>
                        <td colspan='2' style="padding: 8px; border: 1px solid #ddd; background: #f7850b; width: 100%;">Внимание! Покупатель в черном списке</td>                        
                    </tr>
                <?php endif ?>
            </tbody>        
        </table>
    </body>
</html>