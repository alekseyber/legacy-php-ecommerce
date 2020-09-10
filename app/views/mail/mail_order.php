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
                    <th colspan="2" style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9;">Данные покупателя</th>
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
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Адрес:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $address ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Примечание:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $note ?></td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Валюта заказа:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $currency ?></td>
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
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Номер заказа:</td>
                    <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= $order_num ?></td>
                </tr>
                <?php if (isset($_SESSION['promo']) && $_SESSION['promo'] < 1): ?>
                    <tr>
                        <td style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 30%;">Промокод скидка:</td>
                        <td style="padding: 8px; border: 1px solid #ddd; width: 70%;"><?= (1 - $_SESSION['promo']) * 100 ?>%</td>
                    </tr>
                <?php endif ?>
                <?php $delivery = diveltostring(); ?>
                <?php $delivery_pvz = diveltostring(1); ?>
                <?php if ($delivery): ?>
                    <tr>
                        <td colspan='2' style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 100%;"><?= $delivery ?></td>                        
                    </tr>
                <?php endif ?>
                <?php if ($delivery_pvz): ?>
                    <tr>
                        <td colspan='2' style="padding: 8px; border: 1px solid #ddd; background: #f9f9f9; width: 100%;"><?= $delivery_pvz ?></td>                        
                    </tr>
                <?php endif ?>
                <?php if ($acquirers_status): ?>
                    <tr>
                        <td colspan='2' style="padding: 8px; border: 1px solid #ddd; background: #f7850b; width: 100%;">Внимание! Покупатель в черном списке</td>                        
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
        <p></p>
        <table style="border: 1px solid #ddd; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="background: #f9f9f9;">
                    <th colspan="5" style="padding: 8px; border: 1px solid #ddd;">Позиции заказа</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($_SESSION['cart'] as $item): ?>                
                    <tr>
                        <td colspan="5" style="padding: 8px; border: 1px solid #ddd;">
                            <span><strong><?= $i; ?>.</strong> </span>
                            <a href="<?= PATH . '/' . $item['link'] ?>" target="_blank"><?= $item['title'] ?></a>
                            <?php if (isset($item['nosizeselect']) && $item['nosizeselect'] > 0): ?>
                                <span> -не выбр.разм.</span>
                            <?php endif ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" style="padding: 8px; border: 1px solid #ddd; text-align: center"><img style="max-width: auto; max-height: 130px" src="<?= PATH . '/' . $item['img_thumb'] ?>" alt="img"></td>
                    </tr>
                    <tr>                       
                        <td style="padding: 8px; border: 1px solid #ddd;">Количество: <strong><?= $item['qty'] ?></strong></td>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Цена: <strong><?= $item['price'] ?></strong></td>
                        <td colspan="2" style="padding: 8px; border: 1px solid #ddd;">Сумма: <strong><?= $item['price'] * $item['qty'] ?></strong></td>
                    </tr>
                    <?php $i++; ?>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">Итого:</td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?= $_SESSION['cart.qty'] ?></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding: 8px; border: 1px solid #ddd;">На сумму:</td>
                    <td style="padding: 8px; border: 1px solid #ddd;"><?= $_SESSION['cart.currency']['symbol_left'] . $_SESSION['cart.sum'] . " {$_SESSION['cart.currency']['symbol_right']}" ?></td>
                </tr>
            </tbody>
        </table>

    </body>
</html>