<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Значения опции:  <?= $titleGr; ?>
    </h1>    
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        
        <li><a href="<?= ADMIN; ?>/sizes/optionsgrupp-list">Опции</a></li>
        <li class="active"><?= $titleGr; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">

    <a class="btn btn-success" href="<?= ADMIN; ?>/sizes/options-add?id=<?= $id; ?>">Добавить</a>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">                        
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Значения</th>                                                                        
                                    <th>По умолчанию</th>
                                    <th>Добавить к цене</th>
                                    <th>Sortvalue</th>
                                    <th>Удалить</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attrs as $item): ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>
                                        <td>                                        
                                            <input type="text" name="value" class="form-control js-input-form" value="<?= $item['value']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['value']; ?>" data-pattern="false" data-table="options" data-action="<?= ADMIN; ?>/category/edit-value">                           
                                        </td>
                                        <td>                                        
                                            <?php if ($item['default_v']): ?>
                                                <strong>Задано</strong>
                                            <?php else: ?>
                                                <a class="btn btn-info" href="<?= ADMIN; ?>/sizes/options-setdef?group=<?= $id; ?>&id=<?= $item['id']; ?>">Задать</a>
                                            <?php endif; ?></td>
                                        <td>
                                            <input type="text" style="text-align:left;padding:.2rem .5rem" name="price_add" class="js-input-form" pattern="^[0-9]{1,}$" value="<?= $item['price_add']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['price_add']; ?>" data-pattern="true" data-table="options" data-action="<?= ADMIN; ?>/category/edit-value"> 
                                        </td>
                                        <td>
                                            <input type="text" name="sortvalue" class="cat-sortvalue js-input-form" pattern="^[0-9]{1,}$" value="<?= $item['sortvalue']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['sortvalue']; ?>" data-pattern="true" data-table="options" data-action="<?= ADMIN; ?>/category/edit-value"> 
                                        </td>
                                        <td>
                                            <a class="delete" href="<?= ADMIN; ?>/sizes/options-delete?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a>    
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