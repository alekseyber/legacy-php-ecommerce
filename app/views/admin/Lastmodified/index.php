<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Lastmodified
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Lastmodified</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <form class="js-form-edit-lastmodified" action="<?= ADMIN; ?>/lastmodified/edit" method="post">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">                            
                            <a class="btn btn-default" href="<?= ADMIN; ?>/lastmodified/allsettime">Все даты обновить</a>
                            <a class="btn btn-info" href="<?= ADMIN; ?>/lastmodified">Обновить страницу</a>                            
                            <button type="submit" class="btn btn-success btn-save-product">Сохранить</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="table-responsive"> 
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><input name="id" type="radio" checked value="0"> Выбрать</th>
                                        <th>Controler</th>
                                        <th>Expires</th>
                                        <th>Expires_index</th>
                                        <th>Update_at</th>
                                        <th>Description</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($lastmodified as $k => $item): ?>
                                    <input name="data[<?= $k; ?>][update_at]" type="hidden" value="<?= $item['update_at']; ?>">
                                    <tr>
                                        <td><input name="id" type="radio" value="<?= $k; ?>"></td>
                                        <td><?= $item['controler']; ?></td>
                                        <td>
                                            <input type="text" name="data[<?= $k; ?>][expires]" class="form-control" pattern="^[0-9]{1,}$" value="<?= $item['expires']; ?>">
                                        </td>
                                        <td>
                                            <input type="text" name="data[<?= $k; ?>][expires_index]" class="form-control" pattern="^[0-9]{1,}$" value="<?= $item['expires_index']; ?>">
                                        </td>
                                        <td><input type="checkbox" class="checkbox-up_dat" name="data[<?= $k; ?>][up_dat]" value="1">  <span class="update_at-span" id="update_at<?= $k; ?>"><?= $item['update_at']; ?></span></td>
                                        <td>
                                            <input type="text" name="data[<?= $k; ?>][description]" class="form-control" value="<?= $item['description']; ?>">
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
    </form>
</section>
<!-- /.content -->