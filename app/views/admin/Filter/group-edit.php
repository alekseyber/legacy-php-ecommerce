<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование группы фильтров <?= h($group->title); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/filter/attribute-group">Группы фильтров</a></li>
        <li class="active">Редактирование</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN; ?>/filter/group-edit" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Наименование группы</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Наименование группы" value="<?= h($group->title); ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="alias">Алиас</label>
                            <input type="text" name="alias" class="form-control" id="alias" placeholder="Алиас" required value="<?= h($group->alias); ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label>Включить группу</label>
                            <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>  
                            <select class="form-control" name="status" id="status">
                                <?php
                                foreach ($arr as $k => $v):
                                    $selected = '';
                                    if ($group->status == $k):
                                        $selected = ' selected';
                                        ?>

                                    <?php endif; ?>
                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>                                            
                        </div>
                        <div class="form-group">
                            <label>Фильтр товаров</label>
                            <?php $arr = [1 => 'Да', 0 => 'Нет']; ?>  
                            <select class="form-control" name="filter" id="filter">
                                <?php
                                foreach ($arr as $k => $v):
                                    $selected = '';
                                    if ($group->filter == $k):
                                        $selected = ' selected';
                                        ?>

                                    <?php endif; ?>
                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>                                            
                        </div>
                        <div class="form-group">
                            <label>Выводить в карточке продукта</label>
                            <?php $arr = [1 => 'Да', 0 => 'Нет']; ?>  
                            <select class="form-control" name="cartproduct" id="cartproduct">
                                <?php
                                foreach ($arr as $k => $v):
                                    $selected = '';
                                    if ($group->cartproduct == $k):
                                        $selected = ' selected';
                                        ?>

                                    <?php endif; ?>
                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>                                            
                        </div>
                        <div class="form-group">
                            <label for="metatitle">Порядок сортировки</label>
                            <input type="text" name="sortvalue" class="form-control" id="sortvalue" placeholder="Введите число" value="<?= h($group->sortvalue); ?>">
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="id" value="<?= $group->id; ?>">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->