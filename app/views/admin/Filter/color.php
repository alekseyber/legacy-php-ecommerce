<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Цвета
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/filter/attribute-group"> Группы фильтров</a></li>
        <li class="active">Цвета</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN; ?>/filter/color-add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить цвет</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Наименование</th>
                                    <th>Руское имя</th>
                                    <th>Порядок сортировки</th>
                                    <th>Алиас</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($colors as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= ADMIN; ?>/filter/color-edit?id=<?= $item->id; ?>"><?= $item->title; ?></a>
                                        </td>
                                        <td>
                                            <a href="<?= ADMIN; ?>/filter/color-edit?id=<?= $item->id; ?>"><?= $item->rustitle; ?></a>
                                        </td>
                                        <td>
                                            <input type="text" name="sortvalue" class="cat-sortvalue js-input-form" pattern="^[0-9]{1,}$" value="<?= $item->sortvalue; ?>" data-id="<?= $item->id; ?>" data-oldval="<?= $item->sortvalue; ?>" data-pattern="true" data-table="color" data-action="<?= ADMIN; ?>/category/edit-value">
                                        </td>
                                        <td>
                                            <a href="<?= ADMIN; ?>/filter/color-edit?id=<?= $item->id; ?>"><?= $item->alias; ?></a>

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