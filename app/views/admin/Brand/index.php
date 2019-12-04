<section class="content-header">
    <h1>
        Бренды
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        
        <li class="active">Бренды</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN; ?>/brand/add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>                                    
                                    <th>Alias</th>                                    
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>                                 
                                <?php foreach ($brands as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= ADMIN; ?>/brand/edit?id=<?= $item['id']; ?>"><?= $item['id']; ?></a>
                                        </td>
                                        <td><?= $item['title']; ?></td>                                        
                                        <td><?= $item['alias']; ?></td>
                                        <td>
                                            <a href="<?= ADMIN; ?>/brand/edit?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-eye"></i></a>                                            
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