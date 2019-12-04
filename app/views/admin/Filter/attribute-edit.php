<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование фильтра <?= h($attr->value); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/filter/attribute">Список фильтров</a></li>
        <li class="active">Редактирование</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN; ?>/filter/attribute-edit" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="value">Наименование</label>
                            <input type="text" name="value" class="form-control" id="value" placeholder="Наименование" required value="<?= h($attr->value); ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="value">Tag</label>
                            <input type="text" name="tags" class="form-control" id="tags" placeholder="Тег" value="<?= h($attr->tags); ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="value">Значение сортировки</label>
                            <input type="text" name="sortvalue" value="<?= $attr->sortvalue; ?>" class="form-control" id="sortvalue" placeholder="Введите любое число">                            
                        </div>
                        <div class="form-group">
                            <label>Выводить параметр</label>
                            <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>  
                            <select class="form-control" name="status" id="status">
                                <?php
                                foreach ($arr as $k => $v):
                                    $selected = '';
                                    if ($k == $attr->status):
                                        $selected = ' selected';
                                        ?>
                                    <?php endif; ?>
                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>                                            
                        </div>
                        <div class="form-group">
                            <label for="category_id">Группа</label>
                            <select name="attr_group_id" id="category_id" class="form-control">
                                <?php foreach ($attrs_group as $item): ?>
                                    <option value="<?= $item->id; ?>"<?php if ($item->id == $attr->attr_group_id) echo ' selected'; ?>><?= $item->title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="id" value="<?= $attr->id; ?>">
                        <input type="hidden" name="sdvig" value="<?= $attr->sdvig; ?>">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->