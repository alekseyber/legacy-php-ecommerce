<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список страниц городов
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Список страниц</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN; ?>/citypage/add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить страницу</a>
                        <a href="<?= ADMIN; ?>/citypage/edit-settings" class="btn btn-info">Общие настройки</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>                                                                   
                                    <th>Город</th>                                    
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($citypages as $citypage): ?>
                                    <tr>
                                        <td><?= $citypage->id; ?></td>                                        
                                        <td><a href="<?= ADMIN; ?>/citypage/edit?id=<?= $citypage->id; ?>"><?= $citypage->city_name; ?></a></td> 
                                        <td><?= $citypage->status ? 'On' : 'Off'; ?></td>
                                        <td><a href="<?= ADMIN; ?>/citypage/edit?id=<?= $citypage->id; ?>"><i class="fa fa-fw fa-eye"></i></a> <a class="delete" href="<?= ADMIN; ?>/citypage/delete?id=<?= $citypage->id; ?>"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?= count($citypages); ?> страниц из <?= $count; ?>)</p>
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