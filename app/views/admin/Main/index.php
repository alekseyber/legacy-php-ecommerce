<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Панель управления
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?= $countNewOrders; ?></h3>
                    <p>Новые заказы </p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="<?= ADMIN; ?>/order" class="small-box-footer">Все заказы <i class="fa fa-arrow-circle-right"></i></a>
                <a href="<?= ADMIN; ?>/cupons" class="small-box-footer">Промокоды <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3><?= $countProducts ?></h3>
                    <p>Товары</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cubes"></i>
                </div>
                <a href="<?= ADMIN; ?>/product" class="small-box-footer">Все товары <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= $countNews; ?></h3>
                    <p>Новости</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bullhorn"></i>
                </div>
                <a href="<?= ADMIN; ?>/news" class="small-box-footer">Все новости <i class="fa fa-arrow-circle-right"></i></a>                
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= $countCategories; ?></h3>
                    <p>Категории</p>
                </div>
                <div class="icon">
                    <i class="fa fa-navicon"></i>
                </div>
                <a href="<?= ADMIN; ?>/category" class="small-box-footer">Все категории <i class="fa fa-arrow-circle-right"></i></a>
                <a href="<?= ADMIN; ?>/utm" class="small-box-footer">UTM <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3><?= $countPage; ?></h3>
                    <p>Страницы</p>
                </div>
                <div class="icon">
                    <i class="fa fa-file-text-o" aria-hidden="true"></i>
                </div>
                <a href="<?= ADMIN; ?>/page" class="small-box-footer">Все страницы <i class="fa fa-arrow-circle-right"></i></a>
                <a href="<?= ADMIN; ?>/page/edit?main=true" class="small-box-footer">Главная <i class="fa fa-arrow-circle-right"></i></a>
                <a href="<?= ADMIN; ?>/citypage" class="small-box-footer">Страницы городов <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= $countFilterGr; ?></h3>
                    <p>Группы фильтров</p>
                </div>
                <div class="icon">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                </div>
                <a href="<?= ADMIN; ?>/filter/attribute-group" class="small-box-footer">Группы фильтров <i class="fa fa-arrow-circle-right"></i></a>
                <a href="<?= ADMIN; ?>/filter/attribute" class="small-box-footer">Атрибуты фильтров <i class="fa fa-arrow-circle-right"></i></a>
                <a href="<?= ADMIN; ?>/filter/color" class="small-box-footer">Цвета <i class="fa fa-arrow-circle-right"></i></a>
                <a href="<?= ADMIN; ?>/sizes" class="small-box-footer">Размеры <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?= $countComment; ?></h3>
                    <p>Новые отзывы</p>
                </div>
                <div class="icon">
                    <i class="fa fa-bullhorn"></i>
                </div>               
                <a href="<?= ADMIN; ?>/comment" class="small-box-footer">Отзывы <i class="fa fa-comments"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>Настройки</h3>                   
                </div>
                <div class="icon">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </div>
                <a href="<?= ADMIN; ?>/lastmodified" class="small-box-footer">Дата в заголовках <i class="fa fa-clock-o"></i></a>
                <a href="<?= ADMIN; ?>/cache" class="small-box-footer">Кеширование <i class="fa fa-database"></i></a>
                <a href="<?= ADMIN; ?>/parser" class="small-box-footer">Парсер <i class="fa fa-paw"></i> </a>  
                <a href="<?= ADMIN; ?>/headerstr" class="small-box-footer">Счетчики <i class="fa fa-pie-chart"></i></a>
                <a href="<?= ADMIN; ?>/params" class="small-box-footer">Общие настройки <i class="fa fa-cog"></i></a>
            </div>
        </div>





    </div>

</section>
<!-- /.content -->