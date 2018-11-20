<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список новостей
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Список новостей</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN; ?>/news/add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить новость</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>                                                                   
                                    <th>Заголовок</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($news as $item): ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>                                        
                                        <td><a href="<?= ADMIN; ?>/news/edit?id=<?= $item['id']; ?>"><?= $item['title']; ?></a></td>
                                        <td><?= $item['update_at']; ?></td>
                                        <td><?= $item['status'] ? 'On' : 'Off'; ?></td>                                        
                                        <td><a href="<?= ADMIN; ?>/news/edit?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-eye"></i></a> <a class="delete" href="<?= ADMIN; ?>/news/delete?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?= count($news); ?> страниц из <?= $count; ?>)</p>
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