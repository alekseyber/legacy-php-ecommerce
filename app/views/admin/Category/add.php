<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новая категория
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/category">Список категорий</a></li>
        <li class="active">Новая категория</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form action="<?= ADMIN; ?>/category/add" method="post" data-toggle="validator">
                <input type="hidden" name="cat_default" value="0">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Создать</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label for="title">Наименование категории</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Наименование категории" value="<?= $_SESSION['form_data']['title'] ?? null ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок категории</label>
                                    <input type="text" name="htitle" class="form-control" id="title" placeholder="Заголовок категории" value="<?= $_SESSION['form_data']['htitle'] ?? null ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="parent_id">Родительская категория</label>
                                    <?php
                                    $category_menu = new \app\widgets\menu\Menu([
                                        'tpl' => WWW . '/menu/select.php',
                                        'container' => 'select',
                                        'cache' => 0,
                                        'cacheKey' => 'admin_select',
                                        'class' => 'form-control',
                                        'attrs' => [
                                            'name' => 'parent_id',
                                            'id' => 'parent_id',
                                        ],
                                        'prepend' => '<option value="0">Самостоятельная категория</option>',
                                    ]);
                                    echo $category_menu;
                                    //debug ($category);
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <?php
                                $status = $_SESSION['form_data']['status'] ?? 0;
                                ?>
                                <div class="form-group">
                                    <label>Выводить категрию</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>  
                                    <select class="form-control" name="status" id="status">
                                        <?php
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
                            <div class="col-md-1">
                                <div class="form-group has-feedback">
                                    <label for="sortvalue">Сортировка</label>
                                    <input type="text" name="sortvalue" class="form-control" id="sortvalue" pattern="^[1-9]{1,}$" value="1" required data-error="Допускаются цифры">
                                    <div class="help-block with-errors"></div>
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

                <div class="box box-widget collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Фото</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <?php $img = $_SESSION['form_data']['img'] ?? 'no_image.jpg'; ?>
                            <div class="col-md-6">
                                <div id="multi0" class="row">
                                    <div class="box box-success product-img-box">
                                        <div class="box-header with-border">
                                            <h5 class="box-title"></h5>
                                            <div class="box-tools pull-left"> 
                                                <button type="button" class="btn btn-box-tool del-item-cat-img" title="Удалить" data-original-title="Удалить" data-id="0" data-src="<?= h($img); ?>"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <img src="<?= \ishop\App::$app->getProperty('img_category_property')['150']['path'] . h($img); ?>" alt="" class="img-product img-fluid">
                                        </div>
                                        <div class="box-footer">
                                            <div class="form-group">                                        
                                                <input type="text" name="img" class="form-control" placeholder="Имя файла" value="<?= h($img); ?>">
                                            </div>
                                        </div>
                                        <div class="overlay">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
                            <div class="col-md-6">
                                <form class="fileupload" action="<?= ADMIN; ?>/category/add-image?id=0" method="POST" enctype="multipart/form-data" data-id="0">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Добавить файл...</span>                                                                
                                        <input class="fileupload-input" type="file" name="file">                                                                
                                    </span>
                                    <input type="hidden" name="id" value="0">
                                </form>
                                <div id="progress0">
                                    <div class="bar" style="width: 0%;"></div>
                                </div>
                                <p><small>Рекомендуемые размеры: 150х150</small></p>
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                    <div class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!--box box-widget-->
        </div>
    </div>
</section>
<!-- /.content -->