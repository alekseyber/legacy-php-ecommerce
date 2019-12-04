<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование цвета <?= h($title); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/filter/color">Цвета</a></li>
        <li class="active">Редактирование</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <?= $form ?>
                </div>
<!--                <form action="<?= ADMIN; ?>/filter/color-edit" method="post" data-toggle="validator">
                <div class="box-body">
                    <div class="form-group has-feedback">
                        <label for="title">Имя Лат</label>
                        <input type="text" name="title" class="form-control" id="title" placeholder="Имя цвета лат" value="<?= h($color->title); ?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <label for="rustitle">Имя Рус</label>
                        <input type="text" name="rustitle" class="form-control" id="rustitle" placeholder="Имя цвета рус" value="<?= h($color->rustitle); ?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                    <div class="form-group">
                        <label for="sortvalue">Порядок сортировки</label>
                        <input type="text" name="sortvalue" class="form-control" id="sortvalue" placeholder="Введите число" value="<?= h($color->sortvalue); ?>">
                    </div>
                    <div class="form-group">
                        <label for="search_str">Для поиска</label>
                        <input type="text" name="search_str" class="form-control" id="search_str" placeholder="Для поиска" value="<?= h($color->search_str); ?>">                            
                    </div>
                    <div class="form-group has-feedback">
                        <label for="alias">Alias</label>
                        <input type="text" name="alias" class="form-control" id="alias" placeholder="Alias" value="<?= $color->alias; ?>">
                        <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                    </div>
                </div>
                <div class="box-footer">
                    <input type="hidden" name="id" value="">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </form>-->
            </div>
        </div>
    </div>
</section>
<!-- /.content -->