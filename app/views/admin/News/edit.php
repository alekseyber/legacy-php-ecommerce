<!-- Content Header (Page header) -->
<section class="content-header">
    <?php $smallTitle = mb_strimwidth($news->title, 0, 20, '...'); ?>
    <h1>
        Ред. новости: <?= $smallTitle; ?>      
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>

        <li><a href="<?= ADMIN; ?>/news">Список новостей</a></li>        
        <li class="active"><?= $smallTitle; ?></li>

    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="js-form-edit-product" action="<?= ADMIN; ?>/news/edit" method="post" data-toggle="validator">
                <input type="hidden" name="id" value="<?= $news->id; ?>">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?= h($news->title); ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Выводить новость</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>
                                    <select class="form-control" name="status" id="status">
                                        <?php
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($news->status == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="alias">Alias</label>
                                    <input type="hidden" name="alias" value="<?= $news->alias; ?>">
                                    <input type="text" class="form-control" placeholder="alias" value="<?= $news->alias; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seo link</label>
                                    <input type="hidden" name="seolink" value="<?= $news->seolink; ?>">
                                    <input type="text" id="input-seolink" class="form-control" data-table="news" data-seolink="<?= $news->seolink; ?>" data-action="<?= ADMIN; ?>/ajax/editseolink" value="<?= $news->seolink; ?>">
                                    <button type="button" class="btn btn-primary edit-seolink">Сохранить Seolink</button>
                                </div>
                            </div>                
                        </div>
                        <div class="form-group">
                            <label>Промо текст</label>
                            <textarea name="promo" class="form-control" rows="2" placeholder="Enter ..."><?= $news->promo; ?></textarea>
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
                            <textarea name="content" id="editor1" cols="80" rows="20"><?= $news->content; ?></textarea>
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
                            <input type="text" name="meta_title" class="form-control" id="metatitle" placeholder="Meta Title" value="<?= h($news->meta_title); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <input type="text" name="meta_description" class="form-control" id="description" placeholder="Описание" value="<?= h($news->meta_description); ?>">
                        </div>
                        <div class="form-group">
                            <label for="keywords">Ключевые слова</label>
                            <input type="text" name="meta_keywords" class="form-control" id="keywords" placeholder="Ключевые слова" value="<?= h($news->meta_keywords); ?>">
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
                                <div id="multi<?= $news->id; ?>" class="row">
                                    <div class="box box-success product-img-box">
                                        <div class="box-header with-border">
                                            <h5 class="box-title"></h5>
<!--                                            <div class="box-tools pull-left"> 
                                                <button type="button" class="btn btn-box-tool del-item-cat-img" title="Удалить" data-original-title="Удалить" data-id="<?= $news->id; ?>" data-src="<?= $news->img; ?>"><i class="fa fa-times"></i></button>
                                            </div>-->
                                        </div>
                                        <div class="box-body">
                                            <img src="<?= \ishop\App::$app->getProperty('img_news_property')['410']['path'] . $news->img; ?>" alt="" class="img-product img-fluid">
                                        </div>
                                        <div class="box-footer">
                                            <div class="form-group">                                        
                                                <input type="text" name="img" class="form-control" placeholder="Имя файла" value="<?= $news->img; ?>">
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
                                <form class="fileupload" action="<?= ADMIN; ?>/news/add-image?id=<?= $news->id; ?>" method="POST" enctype="multipart/form-data" data-id="<?= $news->id; ?>">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Добавить файл...</span>                                                                
                                        <input class="fileupload-input" type="file" name="file">                                                                
                                    </span>
                                    <input type="hidden" name="id" value="<?= $news->id; ?>">
                                </form>
                                <div id="progress<?= $news->id; ?>">
                                    <div class="bar" style="width: 0%;"></div>
                                </div>
                                <p><small>Рекомендуемые размеры: 410х240</small></p>
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