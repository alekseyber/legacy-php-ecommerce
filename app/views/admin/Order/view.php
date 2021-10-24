<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Заказ №<?= $order['order_num']; ?>
        <a href="<?= ADMIN; ?>/order/delete?id=<?= $order['id']; ?>" class="btn btn-danger btn-xs delete">Удалить</a>
        <a href="<?= ADMIN; ?>/order/print?id=<?= $order['id']; ?>" target="_blank" class="btn btn-info btn-xs">Печать</a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/order">Список заказов</a></li>
        <li class="active">Заказ №<?= $order['order_num']; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <form class="js-form-edit-order" action="<?= ADMIN; ?>/order/change" method="post" data-posthangler="true" data-act="editorder">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <div class="row">
                            <?php $acquirers_class = $order['blockl'] ? ' blockl' : ''; ?>
                            <?php $update_at = $order['update_at'] ? ' Изменен: <strong>' . dattimereturnexpl($order['update_at']) . '</strong>' : ''; ?>
                            <div class="col-md-8">
                                <h3 class="box-title <?= $acquirers_class; ?>">Заказ №<?= $order['order_num']; ?> Дата: <strong><?= dattimereturnexpl($order['date']); ?></strong><?= $update_at; ?></h3>
                            </div>
                            <div class="col-md-4">
                                <input type="hidden" name="id" value="<?= $order['id']; ?>">
                                <input type="hidden" name="delivery" value="<?= $order['delivery']; ?>">
                                <input type="hidden" name="delivery_pvz" value="<?= $order['delivery_pvz']; ?>">                                
                                <a class="btn btn-info" href="<?= ADMIN; ?>/order/view?id=<?= $order['id']; ?>">Обновить страницу</a>                               
                                <button type="submit" class="btn btn-success btn-save-product">Сохранить</button>
                            </div>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php $class = $order['action'] ? ' success' : ''; ?>
                        <p>Кол-во позиций в заказе: <?= count($order_products); ?> на сумму: <?= $order['sum']; ?> <?= $order['currency']; ?></p>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="thumbnail order-status-bgr<?= $class; ?>">
                                    <div class="form-group">
                                        <label>Статус</label>
                                        <div class="order-status-select-wrap">
                                            <input class="order-status-input" type="hidden" name="orderstatus_id" value="">                                                
                                            <select class="form-control order-status-select" data-oldval="<?= $order['orderstatus_id']; ?>" data-action="<?= ADMIN; ?>/order/change-status" data-id="<?= $order['id']; ?>">                                                    
                                                <?php
                                                foreach ($orderstatus as $k => $v):
                                                    $selected = '';
                                                    if ($order['orderstatus_id'] == $k):
                                                        $selected = ' selected';
                                                        ?>
                                                    <?php endif; ?>
                                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($order['blockl']): ?>
                                <div class="col-md-2">
                                    <?php $acquirers_class = $order['blockl'] ? ' blockl' : ''; ?>
                                    <div class="thumbnail <?= $acquirers_class; ?>">                                        
                                        Внимание, клиент в черном списке!                                           
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($order['cupon']) : ?>
                                <div class="col-md-2">
                                    <div class="thumbnail">
                                        <p>Купон</p>
                                        <p><?= $order['cupon']; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($order['utm_id']) : ?>
                                <div class="col-md-2">
                                    <div class="thumbnail">
                                        <p>utm_id</p>
                                        <p><?= $order['utm_id']; ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>    

                        <div class="row">
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Имя заказчика</label>
                                        <input type="text" name="name" class="form-control" value="<?= $order['name']; ?>">                                
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Телефон: </label>
                                        <span class="phone-user"><a href="tel:+<?= $order['phone']; ?>">+<?= $order['phone']; ?></a></span>
                                        <input type="tel" name="phone" class="form-control" value="<?= $order['phone']; ?>">                              
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Город</label>
                                        <input type="text" name="geo_city" class="form-control" value="<?= $order['geo_city']; ?>">                               
                                    </div>
                                </div>
                            </div>
                            <?php if ($order['geo_region']) : ?>
                                <div class="col-md-3">                                    
                                    <div class="thumbnail">
                                        <label>Регион по IP</label>
                                        <p><?= $order['geo_region']; ?></p>                            
                                    </div>                                    
                                </div>
                            <?php endif; ?>
                        </div>  
                        <div class="row"> 
                            <div class="col-md-4"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Адрес</label>
                                        <input type="text" name="address" class="form-control" value="<?= $order['address']; ?>">                             
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Метро</label>
                                        <input type="text" name="metro" class="form-control" value="<?= $order['metro']; ?>">                             
                                    </div>
                                </div>
                            </div>
                            <?php if ($order['delivery']): ?>
                                <div class="col-md-4">                                    
                                    <div class="thumbnail">
                                        <label>Покупатель считал доставку на сайте</label>
                                        <p><?= $order['delivery']; ?></p>                            
                                    </div>                                    
                                </div>
                            <?php endif; ?>
                            <?php if ($order['delivery']): ?>
                                <div class="col-md-4">                                    
                                    <div class="thumbnail">
                                        <label>Покупатель выбрал ПВЗ</label>
                                        <p><?= $order['delivery_pvz']; ?></p>                            
                                    </div>                                    
                                </div>
                            <?php endif; ?>
                            <div class="col-md-2"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Накладная СДЭК</label>
                                        <?php if ($order['cdek']): ?>
                                            <a class="btn btn-info" href="https://www.cdek.ru/track.html?order_id=<?= $order['cdek']; ?>" target="_blank">Отслеживание</a>
                                        <?php endif; ?>
                                        <input type="text" name="cdek" class="form-control" value="<?= $order['cdek']; ?>">                             
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row"> 
                            <?php if ($order['orders_count'] > 1): ?>
                                <div class="col-md-4">                                    
                                    <div class="thumbnail">
                                        <label>Другие заказы покупателя</label>
                                        <p>                                            
                                            <span class="orders-acquirers">
                                                <?php foreach ($orders_acquirers as $key => $val): ?>
                                                    <?php if ($order['id'] != $key): ?>
                                                        <a href="<?= ADMIN; ?>/order/view?id=<?= $key; ?>" target="_blank"> =<?= $val['order_num']; ?>-<?= $val['status']; ?>= </a>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>   
                                            </span>
                                        </p>                            
                                    </div>                                    
                                </div>
                                <div class="col-md-3"> 
                                    <div class="thumbnail">
                                        <div class="form-group">
                                            <label>Копировать контакты из другого заказа клиента</label>   
                                            <select class="form-control order-contact-copy" data-action="<?= ADMIN; ?>/order/copy-contact?id=<?= $order['id']; ?>&old_id=" data-id="<?= $order['id']; ?>">
                                                <option value="0" selected>Не выбран</option>
                                                <?php foreach ($orders_acquirers as $key => $val): ?>                                                       
                                                    <?php if ($order['id'] != $key): ?>
                                                        <option value="<?= $key; ?>"><?= $val['order_num']; ?>-<?= $val['status']; ?></option>                                                
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <div class="col-md-2"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Дата доставки</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control pull-right datepicker datepickerinput" name="dat_deliv" type="text" value="<?= $order['dat_deliv']; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"> 
                                <div class="thumbnail">
                                    <div class="form-group">
                                        <label>Диапазон времени доставки</label>   
                                        <select name="time_deliv" class="form-control">                                                    
                                            <?php
                                            $arrtime = \ishop\App::$app->getProperty('time_delive');
                                            foreach ($arrtime as $k => $v):
                                                $selected = '';
                                                if ($order['time_deliv'] == $k):
                                                    $selected = ' selected';
                                                    ?>
                                                <?php endif; ?>
                                                <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row"> 
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Комментарий клиента</label>
                                        <textarea name="note" class="form-control" rows="2" placeholder=""><?= $order['note']; ?></textarea>                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6"> 
                                <div class="form-group">
                                    <div class="thumbnail">
                                        <label>Комментарий администратора</label>
                                        <textarea name="note_admin" class="form-control" rows="2" placeholder=""><?= $order['note_admin']; ?></textarea>                      
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer"> 
                            <button type="submit" class="btn btn-success btn-save-product">Сохранить</button>
                        </div>
                    </div>
                </div>
                <div class="box box-widget collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Курьеру в Москве</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>                        
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group data-kurier">
                            <label>Данные заказа</label>
                            <textarea id="text-aria-kurier" class="form-control" rows="15"><?= $order['kurier']; ?></textarea>
                        </div>                        
                    </div><!-- /.box-body -->
                    <div class="box-footer">
                        <button id="copy-button" class="btn btn-transparent" aria-label="Выделить текст">Выделить текст</button>
                    </div>
                </div><!--box box-widget-->

                <h3>Корзина</h3>
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="10%">Фото</th>
                                        <th width="60%">Наименование</th>
                                        <th width="15%">Кол-во</th>
                                        <th width="15%">Цена</th>
                                    </tr>
                                </thead>
                                <tbody class="cart-order-body">
                                    <?php
                                    $qty = 0;
                                    foreach ($order_products as $product):
                                        ?>
                                        <tr>                                            
                                            <td>
                                                <?php if ($product->img_thumb) : ?>
                                                    <?php if ($product->link) : ?>
                                                        <a href="<?= $product->link; ?>" target="_blank"><img class="img-thumbnail" src="<?= $product->img_thumb; ?>" style="max-height: 60px;" alt=""></a>
                                                    <?php else: ?>
                                                        <img class="img-thumbnail" src="<?= $product->img_thumb; ?>" style="max-height: 60px;" alt="">
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $nosizeselect = '';
                                                if ($product->nosizeselect) {
                                                    $nosizeselect = ' checked';
                                                }
                                                ?>
                                                <input type="text" name="cart[<?= $product->id; ?>][title]" class="form-control" value="<?= $product->title; ?>">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="cart[<?= $product->id; ?>][nosizeselect]"<?= $nosizeselect; ?>> Не выбр.разм.
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <?php $qty += $product->qty; ?>
                                                <input type="number" name="cart[<?= $product->id; ?>][qty]" class="form-control" value="<?= $product->qty; ?>">
                                            </td>
                                            <td><input type="text" name="cart[<?= $product->id; ?>][price]" class="form-control" value="<?= $product->price; ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="4"> 
                                            <div class="form-group">                                        
                                                <label>Добавить товар</label>
                                                <select name="newproduct" class="form-control select2" id="newcartproduct"></select>
                                            </div> 
                                        </td>
                                    </tr>
                                    <tr class="active">
                                        <td colspan="2">
                                            <b>Итого:</b>
                                        </td>
                                        <td><b><?= $qty; ?></b></td>
                                        <td><b><?= $order['sum']; ?> <?= $order['currency']; ?></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer"> 
                        <button type="submit" class="btn btn-success btn-save-product">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<!-- /.content -->