<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование группы размеров <?= $sizesgroup->title; ?>        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/sizes">Группы размеров</a></li>        
        <li class="active"><?= $sizesgroup->title; ?></li>

    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form class="js-form-edit-product" action="<?= ADMIN; ?>/sizes/grupp-edit" method="post" data-toggle="validator">
                <input type="hidden" name="id" value="<?= $sizesgroup->id; ?>">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок</label>                                    
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?= h($sizesgroup->title); ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="prefix">Prefix</label>                                    
                                    <input type="text" name="prefix" class="form-control" placeholder="Prefix" value="<?= $sizesgroup->prefix; ?>">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="prefix">Link title</label>                                    
                                    <input type="text" name="link_title" class="form-control" placeholder="link_title" value="<?= $sizesgroup->link_title; ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-5">                               
                                <?= $selectSizes; ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="content">Размерная сетка - описание</label>                            
                                    <textarea name="content" id="editor1" cols="80" rows="40"><?= h($sizesgroup->content); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>
<!-- /.content -->