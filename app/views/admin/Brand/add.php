<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый Бренд
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/brand">Бренды</a></li>
        <li class="active">Новый бренд</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN; ?>/brand/add" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label>Имя</label>
                            <input type="text" name="title" class="form-control" placeholder="Имя" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label>Alias</label>
                            <input type="text" name="alias" class="form-control" placeholder="Alias" value="<?php echo isset($_SESSION['form_data']['alias']) ? h($_SESSION['form_data']['alias']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label>Изображение</label>
                            <input type="text" name="img" class="form-control" id="rustitle" placeholder="Фоновое изображение" value="<?php echo isset($_SESSION['form_data']['img']) ? h($_SESSION['form_data']['img']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label>Описание</label>
                            <input type="text" name="description" class="form-control" placeholder="Описание" value="<?php echo isset($_SESSION['form_data']['description']) ? h($_SESSION['form_data']['description']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label>Контент</label>    
                            <textarea class="form-control" name="content" rows="5" placeholder=""><?php echo isset($_SESSION['form_data']['content']) ? $_SESSION['form_data']['content'] : null; ?></textarea>
                        </div>                      
                    </div>
                    <div class="box-footer">                        
                        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>                       
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>