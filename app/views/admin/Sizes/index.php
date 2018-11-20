<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Группы размеров
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        
        <li class="active">Группы размеров</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
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
                                    <th>Размеры</th>
                                    <th>Редактировать</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sizesgroup as $item): ?>
                                    <tr>
                                        <td><?= $item->id; ?></td>
                                        <td><?= $item->title; ?></td>                                        
                                        <td>
                                            <a href="<?= ADMIN; ?>/sizes/size-list?id=<?= $item->id; ?>">Размеры</a>
                                        </td>
                                        <td>
                                            <a href="<?= ADMIN; ?>/sizes/grupp-edit?id=<?= $item->id; ?>">Редактировать</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a class="btn btn-info" href="<?= ADMIN; ?>/sizes/size-list">Все размеры</a>  
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->