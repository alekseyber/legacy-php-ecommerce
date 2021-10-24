<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Промокоды
    </h1>    
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Промокоды</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">                
                <div class="box-body">
                    <div class="mb-2"><a class="btn btn-success mb-2" href="<?= ADMIN; ?>/cupons/add">Добавить</a></div>
                    <div class="table-responsive">                        
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>                                    
                                    <th>Код</th>                                                                        
                                    <th>Включен</th>
                                    <th>Скидка, %</th>
                                    <th>Годен до</th>
                                    <th>Создан</th>                                    
                                    <th>Заказов</th>
                                    <th>Примечание</th>
                                    <th>Операции</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cupons as $item): ?>
                                    <tr>                                        
                                        <td>                                        
                                            <input type="text" name="code" class="form-control js-input-form" value="<?= $item['code']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['code']; ?>" data-pattern="false" data-table="cupons" data-action="<?= ADMIN; ?>/category/edit-value">                           
                                        </td>
                                        <?php $class = $item['status'] ? ' success' : ''; ?>
                                        <td class="order-status-bgr<?= $class; ?>">                                            
                                            <?php $pr_status = ['Off', 'On']; ?>
                                            <select class="form-control js-select-form" name="status" data-table="cupons" data-oldval="<?= $item['status']; ?>" data-action="<?= ADMIN; ?>/category/edit-value" data-id="<?= $item['id']; ?>">                                                    
                                                <?php
                                                foreach ($pr_status as $k => $v):
                                                    $selected = '';
                                                    if ($item['status'] == $k):
                                                        $selected = ' selected';
                                                        ?>
                                                    <?php endif; ?>
                                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                <?php endforeach; ?>                                    
                                            </select>                                                                                  
                                        </td>
                                        <td>
                                            <?php $discont = (100 - $item['value'] * 100); ?>
                                            <input 
                                                class="form-control js-input-form" 
                                                name="value"
                                                type="number"
                                                value="<?= $discont; ?>"
                                                data-table="cupons"
                                                data-action="<?= ADMIN; ?>/cupons/change-data"
                                                data-oldval="<?= $discont; ?>"
                                                data-id="<?= $item['id']; ?>"
                                                max="100"
                                                >   
                                        </td>
                                        <td>
                                            <?php $date_end = $item['date_end'] ? datreturnexpl($item['date_end']) : ''; ?>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input 
                                                    class="form-control pull-right datepicker datepickerinput js-select-form" 
                                                    name="date_end"
                                                    type="text" 
                                                    value="<?= $date_end; ?>"
                                                    data-table="cupons"
                                                    data-action="<?= ADMIN; ?>/cupons/change-data"
                                                    data-oldval="<?= $date_end; ?>"
                                                    data-id="<?= $item['id']; ?>"
                                                    >
                                            </div>
                                        </td>
                                        <td><?= dattimereturnexpl($item['date_start']); ?></td>
                                        <td> <?php $count_item_order = isset($countOrders[$item['id']]) ? $countOrders[$item['id']] : 0; ?>
                                            <?= $count_item_order ?>
                                            <?php if ($count_item_order): ?>
                                                <a class="ms-2"  target="_blank" href="<?= ADMIN; ?>/order?cupon_id=<?= $item['id']; ?>">Заказы</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>                                        
                                            <input type="text" name="description" class="form-control js-input-form" value="<?= $item['description']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['description']; ?>" data-pattern="false" data-table="cupons" data-action="<?= ADMIN; ?>/category/edit-value">                           
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a class="delete" href="<?= ADMIN; ?>/cupons/delete?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a>                                                    
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?= count($cupons); ?> страниц из <?= $count; ?>)</p>
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