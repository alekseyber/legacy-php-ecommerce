<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Группы фильтров
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Группы фильтров</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN; ?>/filter/group-add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить группу</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Наименование</th>
                                    <th>Порядок сортировки</th>
                                    <th>Атрибуты</th>
                                    <th>Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attrs_group as $item): ?>
                                    <?php
                                    if ($item->color) {
                                        $linkedit = ADMIN . 'filter/color';
                                    } else {
                                        $linkedit = ADMIN . '/filter/attribute?id=' . $item->id;
                                    }
                                    ?>

                                    <tr>
                                        <td>
                                            <a href="<?= ADMIN; ?>/filter/group-edit?id=<?= $item->id; ?>"><?= $item->title; ?></a>
                                        </td>
                                        <td>
                                            <input type="text" name="sortvalue" class="cat-sortvalue js-input-form" pattern="^[0-9]{1,}$" value="<?= $item->sortvalue; ?>" data-id="<?= $item->id; ?>" data-oldval="<?= $item->sortvalue; ?>" data-pattern="true" data-table="attribute_group" data-action="<?= ADMIN; ?>/category/edit-value">
                                        </td>
                                        <td>
                                            <a href="<?= $linkedit; ?>">Атрибуты</a>
                                        </td>
                                        <td>
                                            <a href="<?= ADMIN; ?>/filter/group-edit?id=<?= $item->id; ?>"><i class="fa fa-fw fa-pencil"></i></a>
                                            <?php if ($item->delnot == 0): ?>                                            	
                                                <a class="delete text-danger" href="<?= ADMIN; ?>/filter/group-delete?id=<?= $item->id; ?>"><i class="fa fa-fw fa-close text-danger"></i></a>
                                                <?php endif; ?> 
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