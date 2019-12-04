<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новая новость        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>

        <li><a href="<?= ADMIN; ?>/news">Список новостей</a></li>        
        <li class="active">Новая новость</li>

    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="" action="<?= ADMIN; ?>/news/add" method="post" data-toggle="validator">                              
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Создать</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>" required>
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
                            <textarea name="content" id="editor1" cols="80" rows="20"><?php echo isset($_SESSION['form_data']['content']) ? h($_SESSION['form_data']['content']) : null; ?></textarea>
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
                            <div class="col-md-6">
                                <?php $img = $_SESSION['form_data']['img'] ?? 'no_image.jpg'; ?>
                                <div id="multi0" class="row">
                                    <div class="box box-success product-img-box">
                                        <div class="box-header with-border">
                                            <h5 class="box-title"></h5>
                                            <div class="box-tools pull-left"> 
<!--                                                 <button type="button" class="btn btn-box-tool del-item-cat-img" title="Удалить" data-original-title="Удалить" data-id="<?= $news->id; ?>" data-src="<?= $news->img; ?>"><i class="fa fa-times"></i></button> -->
                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <img src="<?= \ishop\App::$app->getProperty('img_news_property')['410']['path'] . $img; ?>" alt="" class="img-product img-fluid">
                                        </div>
                                        <div class="box-footer">
                                            <div class="form-group">                                        
                                                <input type="text" name="img" class="form-control" placeholder="Имя файла" value="<?= $img; ?>">
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
                                <form class="fileupload" action="<?= ADMIN; ?>/news/add-image?id=0" method="POST" enctype="multipart/form-data" data-id="0">
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Добавить файл...</span>                                                                
                                        <input class="fileupload-input" type="file" name="file">                                                                
                                    </span>
                                    <input type="hidden" name="id" value="<?= $img; ?>">
                                </form>
                                <div id="progress<?= $img; ?>">
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