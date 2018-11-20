<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новая страница
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/page">Список страниц</a></li>
        <li class="active">Новая страница</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="" action="<?= ADMIN; ?>/page/add" method="post" data-toggle="validator">                             
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Создать</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>" required>                                    
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name">Имя страницы</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Имя страницы" value="<?php echo isset($_SESSION['form_data']['name']) ? h($_SESSION['form_data']['name']) : null; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Выводить страницу</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>                                    
                                    <select class="form-control" name="status" id="status">                                        
                                        <?php
                                        $status = $_SESSION['form_data']['status'] ?? 0;
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($status == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Промо текст</label>
                            <textarea name="promo" class="form-control" rows="2" placeholder="Enter ..."><?php echo isset($_SESSION['form_data']['promo']) ? h($_SESSION['form_data']['promo']) : null; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="box box-widget collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Контент</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="content">Контент</label>
                            <textarea name="content" class="form-control" rows="40" placeholder="Enter ..."><?php echo isset($_SESSION['form_data']['content']) ? h($_SESSION['form_data']['content']) : null; ?></textarea>
                        </div>
                    </div><!-- /.box-body -->
                </div><!--box box-widget-->
                <div class="box box-widget collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Мета теги</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body"> 
                        <div class="form-group">
                            <label for="metatitle">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" id="metatitle" placeholder="Meta Title" value="<?php echo isset($_SESSION['form_data']['meta_title']) ? h($_SESSION['form_data']['meta_title']) : null; ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <input type="text" name="meta_description" class="form-control" id="description" placeholder="Описание" value="<?php echo isset($_SESSION['form_data']['meta_description']) ? h($_SESSION['form_data']['meta_description']) : null; ?>">
                        </div>
                        <div class="form-group">
                            <label for="keywords">Ключевые слова</label>
                            <input type="text" name="meta_keywords" class="form-control" id="keywords" placeholder="Ключевые слова" value="<?php echo isset($_SESSION['form_data']['meta_keywords']) ? h($_SESSION['form_data']['meta_keywords']) : null; ?>">
                        </div>
                    </div><!-- box-body -->
                </div><!--box box-widget-->
            </form>
            <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
        </div>
    </div> 
</section>
<!-- /.content -->