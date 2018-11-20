<?php $shopname = ishop\App::$app->getProperty('shop_name_rus'); ?>
<?php $shop_tel = ishop\App::$app->getProperty('shop_tel'); ?>
<style>
    .cont-wrap {        
        font-size: 8px;
        font-family: dejavusans;        
        border-bottom: 1px solid #000;
    } 
    td, th {
        padding: 4px;
    }
    .hedder-right {
        position: relative;
        top: -33px;
    }
    .itog-table tr{
        padding: 10px;
    }
</style>
<div class="cont-wrap">
    <div class="first-div-wrap">
        <table class="heder" width="100%" border="1" cellpadding="4" cellspacing="0">
            <tr>
                <td width="60%">    
                    <div class="hedder-left">
                        <table class="first" width="100%">
                            <tr>
                                <td align="left" bgcolor="#dddddd"><b>ПРОДАВЕЦ</b></td>           
                            </tr>
                            <tr>
                                <td align="left"><b>Компания:</b> <?= $shopname; ?>, тел: <?= $shop_tel; ?>, сайт: <?= PATH; ?></td>            
                            </tr> 
                        </table>
                        <table class="first2" width="100%">
                            <tr>
                                <td align="left" bgcolor="#dddddd"><b>ПОКУПАТЕЛЬ</b></td>           
                            </tr>
                            <tr>
                                <td align="left"><b>ФИО:</b> <?= $order['name']; ?><br><b>Телефон:</b> <?= $order['phone']; ?><br><b>Адрес:</b> <?= $order['address']; ?></td>            
                            </tr> 
                        </table>
                    </div>
                </td>
                <td width="40%">
                    <div class="hedder-right">
                        <table class="first" width="100%">
                            <tr>
                                <td align="left" bgcolor="#dddddd"><b>ИНФОРМАЦИЯ О ЗАКАЗЕ</b></td>           
                            </tr>
                            <tr>
                                <td align="left"><b>Заказ:</b> <?= $order['order_num']; ?> от <?= dattimereturnexpl($order['date']); ?></td>            
                            </tr>
                            <tr>
                                <td align="left"><b>Заметки:</b> <?= $order['note']; ?></td>            
                            </tr> 
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div class="too-div-wrap">
        <table class="products" width="100%" border="1" cellpadding="4" cellspacing="0">
            <tr bgcolor="#dddddd">
                <td width="5%"><b>п/п</b></td>
                <td width="40%"><b>Наименование товара</b></td>
                <td width="15%"><b>Цена</b></td>
                <td width="10%"><b>Кол-во</b></td>
                <td width="20%"><b>К оплате</b></td>
                <td width="10%"><b>Кол-во вручен</b></td>
            </tr>
            <?php
            $qty = 0;
            $i = 1;
            foreach ($order_products as $product):
                ?>
                <?php $qty += $product->qty; ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td><?= $product->title; ?></td>
                    <td><?= $product->price; ?> <?= $order['currency']; ?></td>
                    <td><?= $product->qty; ?></td>
                    <td><?= ($product->price * $product->qty); ?> <?= $order['currency']; ?></td>
                    <td></td>
                </tr>
                <?php $i++; ?>
            <?php endforeach; ?>
            <tr>
                <td align="right" colspan="3"><b>Итого:</b></td>
                <td><b><?= $qty; ?></b></td>
                <td><b><?= $order['sum']; ?> <?= $order['currency']; ?></b></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="free-div-wrap">
        <table class="itog-table" width="100%" cellpadding="4" cellspacing="0">
            <tr>
                <td width="50%">
                    <p>Проверяйте комплектацию и внешний вид товара во время его получения! Покупатель самостоятельно несет ответственность за внешний вид и комплектацию товара после приема его от продавца.</p>
                    <p>Обувь имеет гарантию сроком 30 дней в отношении дефектов материалов и изготовления, которые могут произойти за пределами нормального износа. Возврат/обмен новой (не ношенной) обуви - в течении 14-ти дней.</p>
                </td>
                <td width="50%">
                    <p><b>Итого получено:</b>_____________________________________________</p>
                    <p>Доставлено полностью, претензий не имею.</p>
                    <p><b>ПОДТВЕРЖДЕНИЕ ДОСТАВКИ</b><br><br> Дата "____" ____________._______ время ____:____</p>
                    <p><b>ПОЛУЧАТЕЛЬ</b> _______________________________ ________________</p>                    
                </td>                
            </tr>            
        </table>
    </div>
</div>