<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование категории <?= $category->title; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/category">Список категорий</a></li>
        <li class="active"><?= $category->title; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="js-form-edit-product" action="<?= ADMIN; ?>/category/edit" method="post" data-toggle="validator">
                <input type="hidden" name="id" value="<?= $category->id; ?>">
                <input type="hidden" name="cat_default" value="<?= $category->cat_default; ?>">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label for="title">Наименование категории</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Наименование категории" value="<?= h($category->title); ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок категории</label>
                                    <input type="text" name="htitle" class="form-control" id="title" placeholder="Заголовок категории" value="<?= h($category->htitle); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php if ($category->cat_default): ?>
                                    <h3>Главная категория</h3>
                                    <input type="hidden" name="parent_id" value="0">
                                <?php else: ?> 
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
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2">
                                <?php
                                $del = true;
                                $disabled = '';
                                $parent = \R::count('category', 'parent_id = ?', [$category->id]);
                                if ($category->cat_default || $parent) {
                                    $del = false;
                                    $disabled = ' disabled';
                                }
                                ?>
                                <div class="form-group">
                                    <label>Выводить категрию (<?= $parent; ?>)</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>  

                                    <?php if ($del): ?> 
                                        <select class="form-control" name="status" id="status">
                                        <?php else: ?>
                                            <input type="hidden" name="status" value="<?= $category->status; ?>">
                                            <select class="form-control" id="status">                                                
                                            <?php endif; ?>
                                            <?php
                                            foreach ($arr as $k => $v):
                                                $selected = '';
                                                if ($category->status == $k):
                                                    $selected = ' selected';
                                                    ?>
                                                <?php endif; ?>
                                                <option value="<?= $k; ?>"<?= $selected; ?><?= $disabled; ?>><?= $v; ?></option>
                                            <?php endforeach; ?>
                                        </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-2">                                
                                <div class="form-group">
                                    <label>Выводить на главной</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>  
                                    <select class="form-control" name="maintop" id="maintop">
                                        <?php
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($category->maintop == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-1">
                                <?php if ($category->cat_default): ?>
                                    <h3>Главная категория</h3>
                                    <input type="hidden" name="sortvalue" value="0">
                                <?php else: ?> 
                                    <div class="form-group has-feedback">
                                        <label for="sortvalue">Сортировка</label>
                                        <input type="text" name="sortvalue" class="form-control" id="sortvalue" pattern="^[1-9]{1,}$" value="<?= $category->sortvalue; ?>" required data-error="Допускаются цифры">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="alias">Alias No edit</label>
                                    <input type="hidden" name="alias" value="<?= $category->alias; ?>">
                                    <input type="text" class="form-control" placeholder="alias" value="<?= $category->alias; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Seo link</label>
                                    <input type="hidden" name="seolink" value="<?= $category->seolink; ?>">
                                    <input type="text" id="input-seolink" class="form-control" data-table="category" data-seolink="<?= $category->seolink; ?>" data-action="<?= ADMIN; ?>/ajax/editseolink" value="<?= $category->seolink; ?>">
                                    <button type="button" class="btn btn-primary edit-seolink">Сохранить Seolink</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Промо текст</label>
                            <textarea name="promo" class="form-control" rows="2" placeholder="Enter ..."><?= $category->promo; ?></textarea>
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
                            <textarea name="content" class="form-control" rows="40" placeholder="Enter ..."><?= h($category->content); ?></textarea>
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
                            <input type="text" name="meta_title" class="form-control" id="metatitle" placeholder="Meta Title" value="<?= h($category->meta_title); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <input type="text" name="meta_description" class="form-control" id="description" placeholder="Описание" value="<?= h($category->meta_description); ?>">
                        </div>
                        <div class="form-group">
                            <label for="keywords">Ключевые слова</label>
                            <input type="text" name="meta_keywords" class="form-control" id="keywords" placeholder="Ключевые слова" value="<?= h($category->meta_keywords); ?>">
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
                            <div class="col-md-6">
                                <div id="multi<?= $category->id; ?>" class="row">
                                    <div class="box box-success product-img-box">
                                        <div class="box-header with-border">
                                            <h5 class="box-title"></h5>
                                            <div class="box-tools pull-left"> 
<!--                                                <button type="button" class="btn btn-box-tool del-item-cat-img" title="Удалить" data-original-title="Удалить" data-id="<?= $category->id; ?>" data-src="<?= $category->img; ?>"><i class="fa fa-times"></i></button>-->
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <img src="<?= \ishop\App::$app->getProperty('img_category_property')['150']['path'] . $category->img; ?>" alt="" class="img-product img-fluid">
                                        </div>
                                        <div class="box-footer">
                                            <div class="form-group">                                        
                                                <input type="text" name="img" class="form-control" placeholder="Имя файла" value="<?= $category->img; ?>">
                                            </div>
                                        </div>
                                        <div class="overlay">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <div class="col-md-6">
                                <form class="fileupload" action="<?= ADMIN; ?>/category/add-image?id=<?= $category->id; ?>" method="POST" enctype="multipart/form-data" data-id="<?= $category->id; ?>">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Добавить файл...</span>                                                                
                                        <input class="fileupload-input" type="file" name="file">                                                                
                                    </span>
                                    <input type="hidden" name="id" value="<?= $category->id; ?>">
                                </form>
                                <div id="progress<?= $category->id; ?>">
                                    <div class="bar" style="width: 0%;"></div>
                                </div>
                                <p><small>Рекомендуемые размеры: 380х500</small></p>
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                    <div class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div><!--box box-widget-->
                <div class="box box-widget collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Фото thumb</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="multitr<?= $category->id; ?>" class="row">
                                    <div class="box box-success product-img-box">
                                        <div class="box-header with-border">
                                            <h5 class="box-title"></h5>
                                            <div class="box-tools pull-left"> 
<!--                                                <button type="button" class="btn btn-box-tool del-item-cat-img" title="Удалить" data-original-title="Удалить" data-id="<?= $category->id; ?>" data-src="<?= $category->img; ?>"><i class="fa fa-times"></i></button>-->
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <img src="<?= \ishop\App::$app->getProperty('img_thumb_category_property')['150']['path'] . $category->img_thumb; ?>" alt="" class="img-product img-fluid">
                                        </div>
                                        <div class="box-footer">
                                            <div class="form-group">                                        
                                                <input type="text" name="img_thumb" class="form-control" placeholder="Имя файла" value="<?= $category->img_thumb; ?>">
                                            </div>
                                        </div>
                                        <div class="overlay">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <div class="col-md-6">
                                <form class="fileupload" action="<?= ADMIN; ?>/category/add-image?id=<?= $category->id; ?>&amp;thumb=1" method="POST" enctype="multipart/form-data" data-id="<?= $category->id; ?>">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Добавить файл...</span>                                                                
                                        <input class="fileupload-input" type="file" name="file">                                                                
                                    </span>
                                    <input type="hidden" name="id" value="<?= $category->id; ?>">
                                </form>
                                <div id="progress<?= $category->id; ?>">
                                    <div class="bar" style="width: 0%;"></div>
                                </div>
                                <p><small>Рекомендуемые размеры: 50х50</small></p>
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