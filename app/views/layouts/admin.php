<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <base href="/">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">        
        <link rel="shortcut icon" href="/favicon.png" type="image/png">
        <?= $this->getMeta(); ?>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="/adminlte/bower_components/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="/adminlte/bower_components/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.min.css">  
        <!-- Font Awesome -->
        <link rel="stylesheet" href="/adminlte/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="/adminlte/bower_components/Ionicons/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="/adminlte/dist/css/AdminLTE.min.css">        
        <link rel="stylesheet" href="/adminlte/dist/css/skins/_all-skins.min.css">        
        <link rel="stylesheet" href="/adminlte/css/jqueryfileupload/jquery.fileupload.css">        
        <link rel="stylesheet" href="/adminlte/css/select2totree.css">        
        <link rel="stylesheet" href="/adminlte/css/typeaheadjs.css">
        <link rel="stylesheet" href="/adminlte/css/select2-bootstrap.min.css">
        <link rel="stylesheet" href="/adminlte/my.css">

        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">

            <header class="main-header">
                <!-- Logo -->
                <a href="<?= PATH; ?>" class="logo" target="_blank">
                    <span class="logo-mini"><?= \ishop\App::$app->getProperty('shop_name'); ?></span>
                    <span class="logo-lg"><?= \ishop\App::$app->getProperty('shop_name'); ?></span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>

                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="/adminlte/dist/img/user2-160x160.jpg" class="user-image" alt="User Image"><?= $_SESSION['user']['name']; ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">

                                        <p>
                                            <?= $_SESSION['user']['name']; ?>
                                            <small></small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?= ADMIN; ?>/user/edit?id=<?= $_SESSION['user']['id']; ?>" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="/user/logout" class="btn btn-default btn-flat">Sign out</a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="/adminlte/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p><?= $_SESSION['user']['name']; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- search form -->
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search...">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">Меню</li>
                        <!-- Optionally, you can add icons to the links -->
                        <li><a href="<?= ADMIN ?>"><i class="fa fa-home"></i> <span>Home</span></a></li>
                        <li><a href="<?= ADMIN ?>/order"><i class="fa fa-shopping-cart"></i> <span>Заказы</span></a></li>
                        <li><a href="<?= ADMIN ?>/category"><i class="fa fa-navicon"></i> <span>Категории</span></a></li>
                        <li><a href="<?= ADMIN ?>/product"><i class="fa fa-cubes"></i> <span>Товары</span></a></li>                        
                        <li class="treeview">
                            <a href="#"><i class="fa fa-users"></i> <span>Пользователи</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= ADMIN ?>/user">Список пользователей</a></li>
                                <li><a href="<?= ADMIN ?>/user/add">Добавить пользователя</a></li>
                                <li><a href="<?= ADMIN ?>/user/add-block">IP в блок</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-usd"></i> <span>Валюты</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= ADMIN ?>/currency">Список валют</a></li>
                                <li><a href="<?= ADMIN ?>/currency/add">Добавить валюту</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-filter"></i> <span>Фильтры</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= ADMIN ?>/filter/attribute-group">Группы фильтров</a></li>
                                <li><a href="<?= ADMIN ?>/filter/attribute">Фильтры</a></li>                                
                                <li><a href="<?= ADMIN ?>/filter/color">Цвета</a></li>
                                <li><a href="<?= ADMIN ?>/sizes">Размеры</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-pencil-square-o"></i> <span>Страницы</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= ADMIN ?>/page">Список страниц</a></li>
                                <li><a href="<?= ADMIN ?>/page/edit?main=true">Главная страница</a></li>
                                <li><a href="<?= ADMIN ?>/page/add">Добавить</a></li>
                            </ul>
                        </li>
                        <li><a href="<?= ADMIN ?>/news"><i class="fa fa-bullhorn"></i> <span>Новости</span></a></li>
                        <li><a href="<?= ADMIN ?>/comment"><i class="fa fa-comments" aria-hidden="true"></i> <span>Отзывы</span></a></li>
                        <li><a href="<?= ADMIN ?>/slider"><i class="fa fa-sliders"></i> <span>Слайдер</span></a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-cog"></i> <span>Настройки</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= ADMIN ?>/lastmodified">Дата в заголовках</a></li>
                                <li><a href="<?= ADMIN ?>/headerstr">Счетчики</a></li>
                                <li><a href="<?= ADMIN ?>/cache">Кеширование</a></li>
                                <li><a href="<?= ADMIN ?>/params">Общие настройки</a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>
                <?= $content; ?>
                <div id="messege-rezult-success" class="callout callout-success messege-sost">
                    <h4></h4>	
                    <p></p>
                </div>
                <div id="messege-rezult-danger" class="callout callout-danger messege-sost">
                    <h4></h4>	
                    <p></p>
                </div>
            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.4.0
                </div>
                <strong>Copyright &copy; 2014-<?= date('Y'); ?> Almsaeed Studio.</strong> All rights reserved.
            </footer>
            <div class="control-sidebar-bg"></div>
        </div>        

        <script>
            var path = '<?= PATH; ?>',
                    adminpath = '<?= ADMIN; ?>';
        </script>

        <!-- jQuery 3 -->
        <script src="/adminlte/bower_components/jquery/dist/jquery.min.js"></script>
<!--        <script src="/js/ajaxupload.js"></script>-->
<!--        <script src="/js/dropzone.js"></script>-->
        <script src="/adminlte/js/jqueryfileupload/vendor/jquery.ui.widget.js"></script>
        <script src="/adminlte/js/jqueryfileupload/jquery.iframe-transport.js"></script>
        <script src="/adminlte/js/jqueryfileupload/jquery.fileupload.js"></script>

        <!-- Bootstrap 3.3.7 -->
        <script src="/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="/adminlte/bower_components/select2/dist/js/select2.full.js"></script>
        <script src="/adminlte/js/validator.js"></script>
        <!-- AdminLTE App -->
        <script src="/adminlte/dist/js/adminlte.min.js"></script>
        <script src="/adminlte/bower_components/ckeditor/ckeditor.js"></script>
        <script src="/adminlte/bower_components/ckeditor/adapters/jquery.js"></script>
        <script src="/adminlte/bower_components/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js"></script>
        <script src="/adminlte/bower_components/bootstrap-datepicker-master/dist/locales/bootstrap-datepicker.ru.min.js"></script>

        <script src="/adminlte/js/jquery.inputmask.bundle.min.js"></script>
        <script src="/adminlte/js/typeahead/typeahead.bundle.min.js"></script>
        <script src="/adminlte/js/select2totree.js"></script>

        <script src="/adminlte/my.js"></script>
        <?= $this->getJS(); ?>

        <?php
        if (DEBUG) {
            $logs = \R::getDatabaseAdapter()
                    ->getDatabase()
                    ->getLogger();
            debug($logs->grep('SELECT'));
        }
        ?>

    </body>
</html>
