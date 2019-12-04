<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список категорий
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Список категорий</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <a href="<?= ADMIN; ?>/category/add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить категорию</a>
            <div class="box">
                <div class="box-body">
                    <?php
                    $category = new \app\widgets\menu\Menu([
                        'tpl' => WWW . '/menu/category_admin.php',
                        'container' => 'div',
                        'cache' => 0,
                        'cacheKey' => 'admin_cat',
                        'class' => 'list-group list-group-root well',
                        'admin' => true,
                    ]);
                    echo $category;
                    ?>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->