<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый слайд
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/slider">Слайды</a></li>
        <li class="active">Новый слайд</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN; ?>/slider/add" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label>Имя</label>
                            <input type="text" name="name" class="form-control" placeholder="Имя" value="<?php echo isset($_SESSION['form_data']['name']) ? h($_SESSION['form_data']['name']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label>Фоновое изображение</label>
                            <input type="text" name="img" class="form-control" id="rustitle" placeholder="Фоновое изображение" value="<?php echo isset($_SESSION['form_data']['img']) ? h($_SESSION['form_data']['img']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label>Контент</label>    
                            <textarea class="form-control" name="content" rows="5" placeholder=""><?php echo isset($_SESSION['form_data']['content']) ? $_SESSION['form_data']['content'] : null; ?></textarea>
                        </div>
                        <div class="form-group form-check">
                            <div class="wrap-checkbox-on">
                                <div class="label-chekbox">Вкл/Выкл</div>
                                <input class="" id="status" type="checkbox" name="status" value="<?php echo isset($_SESSION['form_data']['status']) ? $_SESSION['form_data']['status'] : 0; ?>">
                                <label class="slider-v2" for="status"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Порядок сортировки</label>
                            <input type="text" name="sortvalue" class="form-control" id="sortvalue" placeholder="Введите число" value="<?php echo isset($_SESSION['form_data']['sortvalue']) ? h($_SESSION['form_data']['sortvalue']) : null; ?>">
                        </div>                        
                    </div>
                    <div class="box-footer">                        
                        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
                        <input type="hidden" name="slider_id" value="1">
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>