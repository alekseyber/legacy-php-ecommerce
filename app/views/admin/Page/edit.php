<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование <?= $str; ?>страницы <?= $page->title; ?>        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <?php if ($main): ?>                    
            <li class="active">Главная страница</li>
        <?php else: ?>
            <li><a href="<?= ADMIN; ?>/page">Список страниц</a></li>        
            <li class="active"><?= $page->title; ?></li>
        <?php endif; ?>

    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="js-form-edit-product" action="<?= ADMIN; ?>/page/edit" method="post" data-toggle="validator">
                <input type="hidden" name="id" value="<?= $page->id; ?>">
                <input type="hidden" name="main" value="<?= $page->main; ?>">               
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?= h($page->title); ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="name">Имя страницы</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Имя страницы" value="<?= h($page->name); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <?php
                                $disabled = '';
                                if ($main) {
                                    $disabled = ' disabled';
                                }
                                ?>
                                <div class="form-group">
                                    <label>Выводить страницу</label>
                                    <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>
                                    <?php if (!$main): ?> 
                                        <select class="form-control" name="status" id="status">
                                        <?php else: ?>
                                            <input type="hidden" name="status" value="<?= $page->status; ?>">
                                            <select class="form-control" id="status">                                                
                                            <?php endif; ?>
                                            <?php
                                            foreach ($arr as $k => $v):
                                                $selected = '';
                                                if ($page->status == $k):
                                                    $selected = ' selected';
                                                    ?>
                                                <?php endif; ?>
                                                <option value="<?= $k; ?>"<?= $selected; ?><?= $disabled; ?>><?= $v; ?></option>
                                            <?php endforeach; ?>
                                        </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="alias">Alias</label>
                                    <input type="hidden" name="alias" value="<?= $page->alias; ?>">
                                    <input type="text" class="form-control" placeholder="alias" value="<?= $page->alias; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Seo link</label>
                                    <input type="hidden" name="seolink" value="<?= $page->seolink; ?>">
                                    <input type="text" id="input-seolink" class="form-control" data-table="page" data-seolink="<?= $page->seolink; ?>" data-action="<?= ADMIN; ?>/ajax/editseolink" value="<?= $page->seolink; ?>">
                                    <button type="button" class="btn btn-primary edit-seolink">Сохранить Seolink</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Промо текст</label>
                            <textarea name="promo" class="form-control" rows="2" placeholder="Enter ..."><?= $page->promo; ?></textarea>
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
                            <textarea name="content" class="form-control" rows="40" placeholder="Enter ..."><?= h($page->content); ?></textarea>
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
                            <input type="text" name="meta_title" class="form-control" id="metatitle" placeholder="Meta Title" value="<?= h($page->meta_title); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <input type="text" name="meta_description" class="form-control" id="description" placeholder="Описание" value="<?= h($page->meta_description); ?>">
                        </div>
                        <div class="form-group">
                            <label for="keywords">Ключевые слова</label>
                            <input type="text" name="meta_keywords" class="form-control" id="keywords" placeholder="Ключевые слова" value="<?= h($page->meta_keywords); ?>">
                        </div>
                    </div><!-- box-body -->
                </div><!--box box-widget-->
            </form>
        </div>
    </div>
</section>
<!-- /.content -->