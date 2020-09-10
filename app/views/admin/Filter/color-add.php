<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый цвет
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/filter/color">Цвета</a></li>
        <li class="active">Новый цвет</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN; ?>/filter/color-add" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Имя Лат</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Имя цвета лат" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="rustitle">Имя Рус</label>
                            <input type="text" name="rustitle" class="form-control" id="rustitle" placeholder="Имя цвета рус" value="<?php echo isset($_SESSION['form_data']['rustitle']) ? h($_SESSION['form_data']['rustitle']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="sortvalue">Порядок сортировки</label>
                            <input type="text" name="sortvalue" class="form-control" id="sortvalue" placeholder="Введите число" value="<?php echo isset($_SESSION['form_data']['sortvalue']) ? h($_SESSION['form_data']['sortvalue']) : null; ?>">
                        </div>
                        <div class="form-group">
                            <label for="search_str">Для поиска</label>
                            <input type="text" name="search_str" class="form-control" id="search_str" placeholder="Для поиска" value="<?php echo isset($_SESSION['form_data']['search_str']) ? h($_SESSION['form_data']['search_str']) : null; ?>">                            
                        </div>
                        <div class="form-group has-feedback">
                            <label for="alias">Alias</label>
                            <input type="text" name="alias" class="form-control" id="alias" placeholder="Alias" value="<?php echo isset($_SESSION['form_data']['alias']) ? h($_SESSION['form_data']['alias']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback ">
                            <label for="colorkey">Код цвета</label>
                            <input type="text" name="colorkey" class="form-control" id="colorkey" placeholder="Код цвет" value="<?php echo isset($_SESSION['form_data']['colorkey']) ? h($_SESSION['form_data']['colorkey']) : null; ?>">
                            <span class="glyphicon form-control-feedback"></span>
                        </div>
                        <div class="form-group form-check">
                            <div><strong>Группа</strong></div>
                            <?php
                            $tablegr_id = $_SESSION['form_data']['tablegr_id'] ?? 0;
                            foreach ($colorgrups as $key => $val) :
                                if ($tablegr_id == $key) {
                                    $checked = ' checked';
                                } else {
                                    $checked = '';
                                }
                                ?>
                                <label><input class="form-check-input" type="radio" name="tablegr_id" value="<?= $key ?>"<?= $checked ?>><?= $val ?></label>
                            <?php endforeach; ?>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>    
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
                    </div>
                </form>                
            </div>
        </div>
    </div>

</section>
<!-- /.content -->