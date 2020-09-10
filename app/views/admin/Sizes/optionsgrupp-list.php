<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Опции товаров
    </h1>    
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        

        <li class="active">Опции товаров</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <a class="btn btn-success" href="<?= ADMIN; ?>/sizes/optionsgrupp-add">Добавить</a>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">                        
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>                                                                        
                                    <th>Включен</th>
                                    <th>Sortvalue</th>
                                    <th>Значения</th>
                                    <th>Удалить</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($optionslist as $item): ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>
                                        <td>                                        
                                            <input type="text" name="title" class="form-control js-input-form" value="<?= $item['title']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['title']; ?>" data-pattern="false" data-table="optionsgrupp" data-action="<?= ADMIN; ?>/category/edit-value">                           
                                        </td>
                                        <?php $class = $item['status'] ? ' success' : ''; ?>
                                        <td class="order-status-bgr<?= $class; ?>">                                            
                                            <?php $pr_status = ['Off', 'On']; ?>
                                            <select class="form-control js-select-form" name="status" data-table="optionsgrupp" data-oldval="<?= $item['status']; ?>" data-action="<?= ADMIN; ?>/category/edit-value" data-id="<?= $item['id']; ?>">                                                    
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
                                            <input type="text" name="sortvalue" class="cat-sortvalue js-input-form" pattern="^[0-9]{1,}$" value="<?= $item['sortvalue']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['sortvalue']; ?>" data-pattern="true" data-table="optionsgrupp" data-action="<?= ADMIN; ?>/category/edit-value"> 
                                        </td>
                                        <td>
                                            <a class="" href="<?= ADMIN; ?>/sizes/options-list?id=<?= $item['id']; ?>">Значения</a>    
                                        </td> 
                                        <td>
                                            <a class="delete" href="<?= ADMIN; ?>/sizes/optionsgrupp-delete?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a>    
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->