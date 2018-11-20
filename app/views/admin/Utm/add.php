<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новая UTM
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/utm">Список UTM</a></li>
        <li class="active">Новая UTM</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="" action="<?= ADMIN; ?>/utm/add" method="post" data-toggle="validator">                             
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Создать</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="name">Код UTM*</label>
                                    <input type="text" name="key_item" class="form-control" id="key_item" placeholder="Код UTM" value="<?php echo isset($_SESSION['form_data']['key_item']) ? h($_SESSION['form_data']['key_item']) : null; ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Заголовок</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>">                                    
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>UTM включен</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>                                     
                                    <select class="form-control" name="status" id="status">                                        
                                        <?php
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if (1 == $k):
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
                                    <label>UTM показывать везде</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>                                     
                                    <select class="form-control" name="all_on" id="all_on">                                        
                                        <?php
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if (0 == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Примечание</label>
                                    <input type="text" name="description" class="form-control" id="description" placeholder="Примечание" value="<?php echo isset($_SESSION['form_data']['description']) ? h($_SESSION['form_data']['description']) : null; ?>">                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Промо текст</label>
                            <textarea name="promo" class="form-control" rows="2" placeholder="Enter ..."><?php echo isset($_SESSION['form_data']['promo']) ? h($_SESSION['form_data']['promo']) : null; ?></textarea>
                        </div>
                    </div>
                </div>
            </form>
            <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
        </div>
    </div> 
</section>
<!-- /.content -->