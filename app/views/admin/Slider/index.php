<section class="content-header">
    <h1>
        Слайды
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        
        <li class="active">Слайды</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN; ?>/slider/add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить слайд</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Наименование</th>                                    
                                    <th>Порядок сортировки</th>
                                    <th>Статус</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>                                 
                                <?php foreach ($slidervalue as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= ADMIN; ?>/slider/edit?id=<?= $item['id']; ?>"><?= $item['id']; ?></a>
                                        </td>
                                        <td><?= $item['name']; ?></td>
                                        <td>
                                            <input type="text" name="sortvalue" class="cat-sortvalue js-input-form" pattern="^[0-9]{1,}$" value="<?= $item['sortvalue']; ?>" data-id="<?= $item['id']; ?>" data-oldval="<?= $item['sortvalue']; ?>" data-pattern="true" data-table="slidervalue" data-action="<?= ADMIN; ?>/category/edit-value">
                                        </td>
                                        <td><?= $item['status'] == 1 ? 'On' : 'Off'; ?></td>
                                        <td>
                                            <a href="<?= ADMIN; ?>/slider/edit?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-eye"></i></a>
                                            <a class="delete" href="<?= ADMIN; ?>/slider/delete?id=<?= $item['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a>
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