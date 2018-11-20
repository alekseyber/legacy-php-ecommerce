<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование UTM <?= $utm->title; ?>        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        
        <li><a href="<?= ADMIN; ?>/utm">Список UTM</a></li>        
        <li class="active"><?= $utm->title; ?></li>        
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="js-form-edit-product" action="<?= ADMIN; ?>/utm/edit" method="post" data-toggle="validator">
                <input type="hidden" name="id" value="<?= $utm->id; ?>">                              
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-primary" href="<?= ADMIN; ?>/utm/copyutm?id=<?= $utm->id; ?>">Копировать UTM</a>
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="name">Код UTM*</label>
                                    <input type="text" name="key_item" class="form-control" id="key_item" placeholder="Код UTM" value="<?= h($utm->key_item); ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Заголовок</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?= h($utm->title); ?>">                                    
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
                                            if ($utm->status == $k):
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
                                            if ($utm->all_on == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="title">Примечание</label>
                                    <input type="text" name="description" class="form-control" id="description" placeholder="Примечание" value="<?= h($utm->description); ?>">                                    
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Промо текст</label>
                            <textarea name="promo" class="form-control" rows="2" placeholder="Enter ..."><?= $utm->promo; ?></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- /.content -->