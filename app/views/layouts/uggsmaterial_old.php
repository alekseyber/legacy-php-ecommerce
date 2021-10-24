<!doctype html>
<?php
$hash = '';
if (HASH_ON) {
    $hash = '?' . time();
}
$webp_on = \ishop\App::$app->getProperty('webp_on');
$dop_menu_items = \ishop\App::$app->getProperty('dop_menu_items');
$filter_content = \ishop\App::$app->getProperty('filter_content');
$menuTopCatalog = new app\widgets\menu\Menu([
    'tpl' => WWW . '/menu/menu_top_material.php',
    'container' => '',
    'cacheKey' => 'top_menu',
]);
$menuLeftCatalogHome = new app\widgets\menu\Menu([
    'tpl' => WWW . '/menu/menu_left_material_home.php',
    'container' => '',
    'cacheKey' => 'left_menu_home',
]);
$menuLeftCatalog = new app\widgets\menu\Menu([
    'tpl' => WWW . '/menu/menu_left_material.php',
    'container' => '',
]);
$controlleraction = \ishop\App::$app->getProperty('controlleraction');
$mainclass = !$controlleraction ? ' main-p' : '';
$cityData =  app\widgets\delivery\Delivery::getCityData();
?>
<html lang="ru">

<head>
    <base href="/">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?= $this->getMeta(); ?>
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="shortcut icon" href="/favicon.png" type="image/png">
    <link href="/assets/css/vendors~main.css" rel="stylesheet">
    <link href="/assets/css/main.css<?= $hash; ?>" rel="stylesheet">    
    <?= $this->getHeader(); ?>
</head>

<body>
    <div id="modal-content-wrap"></div>
    <div class="bmd-layout-container bmd-drawer-f-l bmd-drawer-overlay<?= $mainclass; ?>" id="layout-container">
        <header class="bmd-layout-header">
            <div class="top-bg-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <button type="button" class="btn nav-link js-link-modal" data-href="#myModalpromo1" data-dann="{&quot;id&quot;:&quot;1&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;\/delivery&quot;}">
                            <span class="js-city-btn-text"><?= $cityData['city_default'] ?></span>
                        </button>
                    </li>
                    <?php foreach ($dop_menu_items as $item) : ?>
                        <li class="nav-item">
                            <a class="nav-link js-link-true2" href="<?= $item['href']; ?>"><?= $item['title']; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="navbar navbar-light bg-faded main-nav">
                <button class="navbar-toggler" type="button" data-toggle="drawer" data-target="#dw-p1">
                    <span class="sr-only">Toggle drawer</span>
                    <i class="material-icons">menu</i>
                </button>
                <a class="navbar-brand top-brand js-link-true2" href="/">
                    <?php if ($webp_on) : ?>
                        <picture>
                            <source srcset="<?= imgSrcToWebP("/images/logo_sm.png") ?>" type="image/webp">
                            <source srcset="/images/logo_sm.png" type="<?= getTypeImg("/images/logo_sm.png") ?>">
                            <img class="d-inline-block align-top" src="/images/logo_sm.png" alt="<?= \ishop\App::$app->getProperty('shop_name'); ?>">
                        </picture>
                    <?php else : ?>
                        <img class="d-inline-block align-top" src="/images/logo_sm.png" alt="<?= \ishop\App::$app->getProperty('shop_name'); ?>">
                    <?php endif; ?>
                </a>
                <ul class="navbar-nav catalog-menu-big">
                    <li class="nav-item">
                        <a class="nav-link js-link-true2" href="/"><i class="material-icons">home</i></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link js-link-true2" href="<?= \ishop\Link::getLink(\ishop\App::$app->getProperty('filter_default_uri')); ?>"><?= \ishop\App::$app->getProperty('name_default_uri'); ?></a>
                    </li>
                    <?= $menuTopCatalog; ?>
                </ul>
                <div class="search-wr">
                    <button class="btn bmd-btn-icon search-btn" data-toggle="collapse" data-target="#collapse-search" aria-controls="collapse-search">
                        <i class="material-icons">search</i>
                    </button>
                    <div id="collapse-search" class="collapse collapse-search-wr">
                        <input class="form-control" type="text" autocomplete="off" id="search" placeholder="Поиск...">
                    </div>
                </div>
                
                
                
                
                
                <div class="navbar-text">
                    <div class="phone-wr">
                        <i class="material-icons">phone</i>
                        <a class="phone" href="tel:<?= \ishop\App::$app->getProperty('shop_tel_link'); ?>"><?= \ishop\App::$app->getProperty('shop_tel'); ?></a>
                    </div>
                </div>
            </div>
        </header>
        <div id="dw-p1" class="bmd-layout-drawer bg-faded">
            <header>
                <a class="navbar-brand js-link-true2" href="/">
                    <span class="material-icons">home</span>
                    <span class="ml-1"><?= \ishop\App::$app->getProperty('shop_name'); ?></span>
                </a>
            </header>
            <div class="list-group">
                <div class="product-cat-menu sec-l-menu">
                    <div class="list-group-item m-t-menu" id="left-cat-menu">
                        <div class="tab-content">
                            <div class="tab-pane active" id="v-tab-Cathome">
                                <div class="nav nav-tabs">
                                    <?= $menuLeftCatalogHome; ?>
                                </div>
                            </div>
                            <?= $menuLeftCatalog; ?>
                        </div>
                    </div>
                </div><!-- product-cat-menu -->
                <?= $filter_content; ?>
                <div class="page-menu sec-l-menu">
                    <div class="list-group-item m-t-menu" id="left-page-menu">
                        <div class="tab-content">
                            <div class="tab-pane active" id="v-tab-Pagehome">
                                <div class="nav nav-tabs">
                                    <button type="button" class="btn btn-menu-nav js-l-return-tab" data-target="v-tab-Pagelist">
                                        <span>Информация</span>
                                        <span class="material-icons right">arrow_forward</span>
                                    </button>
                                </div>
                            </div>
                            <div class="tab-pane" id="v-tab-Pagelist">
                                <button type="button" class="btn btn-menu-nav js-l-return-tab" data-target="v-tab-Pagehome">
                                    <span class="material-icons left">arrow_back</span>
                                    <span>Информация</span>
                                </button>
                                <div class="m-t-con list-group">
                                    <?php foreach ($dop_menu_items as $item) : ?>
                                        <a class="list-group-item js-link-true2 drawer-cl" href="<?= $item['href']; ?>"><?= $item['title']; ?></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item m-t-menu">
                        <a class="btn btn-menu-nav btn-drk js-link-modal drawer-cl" href="#myModalpromo2" data-dann="{&quot;id&quot;:&quot;2&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;askquestion&quot;}">
                            <span>Заказать звонок</span>
                        </a>
                    </div>
                </div><!-- page-menu -->
            </div> <!-- list-group -->
        </div><!-- bmd-layout-drawer -->
        <main class="bmd-layout-content">
            <div class="fixed-bl d-none"></div>
            <div class="page-content" id="page-content">
                <?= $content; ?>
            </div><!--  page-content-->
            <div class="preloader">
                <div>
                    <div class="d-flex align-items-center">
                        <div class="spinner-border">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="top-scr-btn">
                <button type="button" class="btn btn-primary bmd-btn-fab" id="topScrBtn">
                    <i class="material-icons">arrow_upward</i>
                </button>
            </div>
            <div class="bottom-alerts">
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php
                        echo $_SESSION['success'];
                        unset($_SESSION['success']);
                        ?>
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
                <div class="alert alert-danger alert-dismissible fade" id="js-alert-danger">
                    <div class="js-allert-message"></div>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="alert alert-success alert-dismissible fade" id="js-alert-success">
                    <div class="js-allert-message"></div>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            </div>
            <footer>
                <div class="container">
                    <nav class="footer-menu">
                        <div class="row">
                            <div class="footer-menu-section col-lg-3">
                                <h5 class="panel-title">Контакты</h5>
                                <ul class="nav-pills" itemscope="" itemtype="http://schema.org/Organization">
                                    <li>
                                        <span itemprop="name"><?= \ishop\App::$app->getProperty('shop_name'); ?></span>
                                    </li>
                                    <li>
                                        <a class="footer-menu-nav-link" href="tel:<?= \ishop\App::$app->getProperty('shop_tel_link'); ?>" title="Телефон" itemprop="telephone">
                                            <i class="material-icons">phone</i>
                                            <?= \ishop\App::$app->getProperty('shop_tel'); ?></a>
                                    </li>

                                    <li>
                                        <a class="footer-menu-nav-link" href="mailto:<?= \ishop\App::$app->getProperty('shop_email'); ?>" title="E-MAIL" itemprop="email">
                                            <i class="material-icons">email</i>
                                            <?= \ishop\App::$app->getProperty('shop_email'); ?></a>
                                    </li>
                                    <li>
                                        <i class="material-icons">map</i>
                                        <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
                                            <span itemprop="streetAddress"><?= \ishop\App::$app->getProperty('shop_adress'); ?></span>
                                        </span>
                                    </li>
                                </ul>
                            </div> <!-- footer-menu-section col-md-3 -->
                            <div class="footer-menu-section col-lg-3">
                                <h5 class="panel-title">Каталог</h5>
                                <ul class="nav-pills">
                                    <li><a href="<?= \ishop\Link::getLink('women'); ?>" class="js-link-true2">Угги женские</a>
                                    </li>
                                    <li><a href="<?= \ishop\Link::getLink('men'); ?>" class="js-link-true2">Угги мужские</a>
                                    </li>
                                    <li><a href="<?= \ishop\Link::getLink('kids'); ?>" class="js-link-true2">Угги детские</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="footer-menu-section col-lg-3">
                                <h5 class="panel-title">Навигация</h5>
                                <ul class="nav-pills">
                                    <li><a href="<?= \ishop\Link::getLink('news'); ?>" class="js-link-true2">Новости, блог</a>
                                    </li>
                                    <li><a href="<?= \ishop\Link::getLink('comment'); ?>" class="js-link-true2">Отзывы</a></li>
                                    <li><a href="#myModalpromo2" class="js-link-modal" data-dann="{&quot;id&quot;:&quot;2&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;askquestion&quot;}">Задать вопрос</a></li>
                                    <li><a href="<?= \ishop\Link::getLink('vozvrat'); ?>">Заявка на возврат/обмен</a></li>
                                    <li><a href="<?= \ishop\Link::getLink('contacts'); ?>" class="js-link-true2">Контакты</a></li>
                                </ul>
                            </div>
                            <div class="footer-menu-section col-lg-3">
                                <h5 class="panel-title">Полезная информация</h5>
                                <ul class="nav-pills">
                                    <li><a href="<?= \ishop\Link::getLink('voprosy-otvety'); ?>" class="js-link-true2">Вопросы ответы</a>
                                    </li>
                                    <li><a href="<?= \ishop\Link::getLink('garantiya'); ?>" class="js-link-true2">Гарантия</a></li>
                                    <li><a href="<?= \ishop\Link::getLink('sizechart'); ?>" class="js-link-true2">Размерная сетка</a></li>
                                    <li><a href="<?= \ishop\Link::getLink('about'); ?>" class="js-link-true2">О магазине</a></li>
                                    <li><a href="<?= \ishop\Link::getLink('polzovatelskoe-soglashenie'); ?>" class="js-link-true2">Оферта</a></li>
                                </ul>
                            </div>
                        </div> <!-- row -->
                    </nav><!-- /.footer-menu -->
                    <p class="footer-copyright">&copy; <?= date('Y'); ?>
                        <?= \ishop\App::$app->getProperty('shop_name'); ?>. Все права защищены.</p>
                </div><!-- container -->
            </footer>
        </main>
    </div><!-- bmd-layout-container -->
    <div class="js-small-card-wrap" data-qty="0">
        <div class="cart-btn">
            <button type="button" class="btn btn-top-cart" id="cartBtn" data-toggle="modal" data-target="#Modalcard">
                <span class="material-icons">shopping_cart</span>
                <span class="badge badge-primary js-bage-cart-qty">0</span>
            </button>
        </div>
        <div class="modal fade small-cart-modal" id="Modalcard" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ваш заказ</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body js-small-card-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-raised btn-secondary" data-dismiss="modal">Продолжить покупки</button>
                        <a href="<?= \ishop\Link::getLink('cart/view'); ?>" class="btn btn-raised btn-primary add-order js-link-true2 js-cl-modal-close">Оформить заказ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal quickview product-modal fade" id="myModalquickviewProduct" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title c-pointer hov-op d-flex" data-dismiss="modal"><i class="material-icons mr-1">arrow_back</i>
                        Назад к покупкам</h6>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body js-pr-clr-load"></div><!-- modal-body -->
            </div>
        </div>
    </div> <!-- quickview-->
    <div class="modal add-to-cart-popup" id="addcardwindow" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавлено в корзину</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="add-cart-body"></div>
                    <div class="recommendation-content"></div>
                </div> <!-- modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-raised" data-dismiss="modal">Продолжить
                        покупки</button>
                    <a tabindex="0" href="<?= \ishop\Link::getLink('cart/view'); ?>" class="btn btn-primary btn-raised js-link-true2 js-cl-modal-close">Оформить заказ</a>
                </div>
            </div><!-- modal-content-->
        </div> <!-- modal-dialog -->
    </div>
    <!--add-to-cart-->
    <div class="modal" id="qorderwindow" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Быстрый заказ</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" method="post" action="<?= \ishop\Link::getLink('cart/checkout'); ?>" novalidate>
                        <div class="input-group form-group">
                            <div class="input-group-prepend mr-2">
                                <span class="input-group-text"><i class="material-icons">person_pin</i></span>
                            </div>
                            <input type="text" name="name" class="form-control" placeholder="Имя*" value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '' ?>" required>
                            <div class="invalid-feedback">
                                Введите, пожалуйста, имя!
                            </div>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend mr-2">
                                <span class="input-group-text"><i class="material-icons">phone_iphone</i></span>
                            </div>
                            <input type="tel" name="phone" class="form-control" placeholder="Телефон*" value="<?= isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : '' ?>" pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$" required>
                            <div class="invalid-feedback">
                                Введите, пожалуйста, телефон!
                            </div>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text mr-2"><i class="material-icons">mode_comment</i></span>
                            </div>
                            <textarea name="note" class="form-control" placeholder="Комментарии к заказу" rows="5"><?= isset($_SESSION['form_data']['note']) ? h($_SESSION['form_data']['note']) : '' ?></textarea>
                        </div>
                        <div class="checkbox igree">
                            <label>
                                <input class="" type="checkbox" checked="checked" name="i-agree" required>
                                <span> Я согласен с условиями </span>
                                <span>
                                    <a href="<?= \ishop\Link::getLink('polzovatelskoe-soglashenie'); ?>" target="_blank">публичной оферты и обработкой моих персональных данных в
                                        порядке, предусмотренном публичной офертой</a>
                                </span>
                                <span class="invalid-feedback">
                                    Поставьте, пожалуйста, галочку!
                                </span>
                            </label>
                        </div>
                        <div class="order-action">
                            <button type="submit" class="btn btn-primary btn-raised">Отправить</button>
                        </div>
                    </form>
                </div> <!-- modal-body -->
            </div><!-- modal-content-->
        </div> <!-- modal-dialog -->
    </div> <!-- modal  qorderwindow-->
    <?php $curr = \ishop\App::$app->getProperty('currency'); ?>
    <?php $js_link_true = \ishop\App::$app->getProperty('js_link_true') ? 1 : 0; ?>
    <?php $js_link_true2 = \ishop\App::$app->getProperty('js_link_true2') ? 1 : 0; ?>
    <?php $webp_on = \ishop\App::$app->getProperty('webp_on') ? 1 : 0; ?>
    <script>
        var path = '<?= PATH; ?>';
        var course = <?= $curr['value']; ?>;
        var symboleLeft = '<?= $curr['symbol_left']; ?>';
        var symboleRight = '<?= $curr['symbol_right']; ?>';
        var js_link_true = <?= $js_link_true; ?>;
        var js_link_true2 = <?= $js_link_true2; ?>;
        var webp_on = <?= $webp_on; ?>;
        var recomacces = <?= $_SESSION['recomacces']; ?>;
    </script>
    <script src="/assets/js/vendors~main.js"></script>
    <script src="/assets/js/main.js<?= $hash; ?>"></script>
    <?= $this->getJS(); ?>
    <?= $this->getFooterJs(); ?>
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