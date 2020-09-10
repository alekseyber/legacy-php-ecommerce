<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование общих настроек городов        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>       
        <li><a href="<?= ADMIN; ?>/citypage">Список страниц</a></li>        
        <li class="active">Общие настройки</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12"> 
            <form class="js-form-edit-product" action="<?= ADMIN; ?>/citypage/edit-settings" method="post" data-toggle="validator">
                <input type="hidden" name="id" value="<?= $citysettings->id; ?>">

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
                                    <label for="title">Заголовок на результатом расчета</label>
                                    <input type="text" name="divel_title" class="form-control" id="divel_title" placeholder="Заголовок" value="<?= h($citysettings->divel_title); ?>">
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>                           
                        </div> 
                        <div class="form-group">
                            <label for="content_top">Блок текста над расчетом</label>
                            <textarea name="content_top" class="form-control editorfield" id="content_top" rows="2" placeholder="Enter ..."><?= $citysettings->content_top; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="content_bottom">Блок текста под и особенностями расчетом</label>
                            <textarea name="content_bottom" class="form-control editorfield" id="content_bottom" rows="2" placeholder="Enter ..."><?= $citysettings->content_bottom; ?></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- /.content -->