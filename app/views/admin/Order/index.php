<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список заказов
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Список заказов</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Телефон</label>
                                <input type="tel" id="typeahead" name="phone" class="form-control typeahead" placeholder="Телефон" value="<?= $phone; ?>" aria-label="Телефон">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>№ заказа</label>
                                <input type="text" id="typeaheadordernum" class="form-control typeahead" placeholder="номер заказа" value="<?= $order_num; ?>" aria-label="Номер заказа">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?php $orderstatus[0] = 'Все'; ?>
                                <label>Статус</label>
                                <select class="form-control order-status-filter" data-oldval="<?= $orderstatus_id; ?>" data-action="<?= ADMIN; ?>/order?orderstatus_id=" data-id="">                                                    
                                    <?php
                                    foreach ($orderstatus as $k => $v):
                                        $selected = '';
                                        if ($orderstatus_id == $k):
                                            $selected = ' selected';
                                            $sel = true;
                                            ?>
                                        <?php endif; ?>
                                        <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                    <?php endforeach; ?>                                    
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Дата доставки</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control pull-right datepicker" id="datepicker" type="text" value="<?= $dat_deliv; ?>" data-action="<?= ADMIN; ?>/order?dat_deliv=">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-2">
                            <a class="btn btn-info clear-order-filter" href="<?= ADMIN; ?>/order">Очистить фильтр</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Номер</th>
                                    <th>Покупатель</th>
                                    <th>ИД пок</th>
                                    <th>Телефон</th>
                                    <th>Город(Geo)</th>
                                    <th>Адрес</th>
                                    <th>Статус</th>
                                    <th>Сумма</th>
                                    <th>Дата создания</th>                                    
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                unset ($orderstatus[0]);
                                foreach ($orders as $order):  ?>
                                    <?php $class = $order['action'] ? ' success' : ''; ?>
                                    <tr class="order-status-bgr<?= $class; ?>">
                                        <td><a href="<?= ADMIN; ?>/order/view?id=<?= $order['id']; ?>"><?= $order['order_num']; ?></a></td>
                                        <?php $acquirers_class = $order['acquirers_st'] ? ' blockl' : ''; ?>
                                        <td class="<?= $acquirers_class; ?>"><?= $order['name']; ?></td>
                                        <td><?= $order['acquirers_id'] . $acquirers_class; ?></td>
                                        <td><a href="tel:+<?= $order['phone']; ?>" title="Телефон">+<?= $order['phone']; ?></a></td>
                                        <td><?= $order['geo_city']; ?></td>
                                        <td><?= $order['address']; ?></td>
                                        <td>                                            
                                            <div class="order-status-select-wrap">
                                                <input class="order-status-input" type="hidden" name="orderstatus_id" value="">                                                
                                                <select class="form-control order-status-select" data-oldval="<?= $order['orderstatus_id']; ?>" data-action="<?= ADMIN; ?>/order/change-status" data-id="<?= $order['id']; ?>">                                                    
                                                    <?php  foreach ($orderstatus as $k => $v):
                                                        $selected = '';
                                                        if ($order['orderstatus_id'] == $k):
                                                            $selected = ' selected';
                                                            ?>
                                                        <?php endif; ?>
                                                        <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </td>
                                        <td><?= $order['sum']; ?> <?= $order['currency']; ?></td>
                                        <td><?= $order['date']; ?></td>

                                        <td><a class="delete" href="<?= ADMIN; ?>/order/delete?id=<?= $order['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?= count($orders); ?> заказа(ов) из <?= $count; ?>)</p>
                        <?php if ($pagination->countPages > 1): ?>
                            <?= $pagination; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->