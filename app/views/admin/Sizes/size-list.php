<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Размеры <?= $grname; ?>
    </h1>    
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        
        <li><a href="<?= ADMIN; ?>/sizes">Группы размеров</a></li>
        <li class="active">Размеры</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <a class="btn btn-info" href="<?= ADMIN; ?>/sizes/size-edit">Все размеры</a>
    <a class="btn btn-success" href="<?= ADMIN; ?>/sizes/size-add">Добавить</a>
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
                                    <th>Алиас</th>
                                    <th>Tag</th>
                                    <th>Sortvalue</th>
<!--                                    <th>Удалить</th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sizelist as $item): ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>
                                        <td>                                        
                                            <input type="text" name="title" class="form-control js-input-form" value="<?= $item['title']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['title']; ?>" data-pattern="false" data-table="sizes" data-action="<?= ADMIN; ?>/category/edit-value">                           
                                        </td>
                                        <td><?= $item['alias']; ?></td>
                                        <td>                                        
                                            <input type="text" name="tags" class="form-control js-input-form" value="<?= $item['tags']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['tags']; ?>" data-pattern="false" data-table="sizes" data-action="<?= ADMIN; ?>/category/edit-value">                           
                                        </td>
                                        <td>
                                            <input type="text" name="sortvalue" class="cat-sortvalue js-input-form" pattern="^[0-9]{1,}$" value="<?= $item['sortvalue']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['sortvalue']; ?>" data-pattern="true" data-table="sizes" data-action="<?= ADMIN; ?>/category/edit-value"> 
                                        </td>
    <!--                                        <td>
                                            <a class="delete" href="<?= ADMIN; ?>/sizes/size-delete?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a>    
                                        </td>-->
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