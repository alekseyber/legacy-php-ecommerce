<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование слайда <?= h($form->name); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/slider">Слайды</a></li>
        <li class="active">Редактирование</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <?= $form ?>
                </div>
            </div>
        </div>
    </div>
</section>