<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новая страница города
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/citypage">Список страниц</a></li>
        <li class="active">Новая страница</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="" action="<?= ADMIN; ?>/citypage/add" method="post" data-toggle="validator">                             
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
                                <div class="form-group">
                                    <label for="city_name">Город для вывода</label>
                                    <input type="text" name="city_name" class="form-control" id="city_name" placeholder="Город" value="<?php echo isset($_SESSION['form_data']['city_name']) ? h($_SESSION['form_data']['city_name']) : null; ?>" >                                    

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php $arr = ['Выключен', 'Включен']; ?>   
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Выводить страницу</label>                                                                     
                                    <select class="form-control" name="status" id="status">                                        
                                        <?php
                                        $status = $_SESSION['form_data']['status'] ?? 1;
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="content_on">Выводить контент</label>                                                                   
                                    <select class="form-control" name="content_on" id="content_on">                                        
                                        <?php
                                        $status = $_SESSION['form_data']['content_on'] ?? 1;
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_id_selector">Введите город для расчета</label>
                                    <input type="text" class="form-control typeahead" placeholder="Введите город..." id="city_id_selector" autocomplete="off" value="<?php echo isset($_SESSION['form_data']['city_id']) ? $_SESSION['form_data']['city_id'] : null; ?>">
                                    <input type="hidden" name="city_id" id="city_id" value="<?php echo isset($_SESSION['form_data']['city_id']) ? $_SESSION['form_data']['city_id'] : null; ?>" >
                                </div>  
                            </div>
                        </div> 
                    </div>
                </div>
            </form>
            <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
        </div>
    </div> 
</section>
<!-- /.content -->