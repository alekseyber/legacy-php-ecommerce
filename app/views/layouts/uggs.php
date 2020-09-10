<!doctype html>
<?php
$hash = '';
if (HASH_ON) {
    $hash = '?' . time();
}
?>
<html lang="ru">
    <head>
        <base href="/">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">        
        <!--        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">-->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= $this->getMeta(); ?>
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="/css/typeaheadjs.css" rel="stylesheet">        
        <link href="/css/swiper.min.css" rel="stylesheet">
        <link href="/css/ion.rangeSlider.css" rel="stylesheet">        
        <link href="/css/style.min.css<?= $hash; ?>" rel="stylesheet">
        <link href="/apple-touch-icon.png" rel="apple-touch-icon">
        <link href="/favicon.ico" rel="icon">
        <?= $this->getHeader(); ?>

    </head>
    <body> 
        <div class="page-wrap">            
            <header>
                <div class="inf-page_collapse-top collapse" id="inf-page_collapse-top" aria-expanded="false" role="note">
                    <a class="close alert-dismissible" data-toggle="collapse" href="#inf-page_collapse-top" aria-expanded="true" aria-controls="inf-page_collapse-top" aria-label="Close">&times;</a>
                    <!-- <div class="clearfix"></div> -->
                    <div class="container">
                        <div class="inf-page_collapse-top_content">
                            <!-- include ajax top text -->
                            <div class="h4">Бесплатная доставка при заказе от 5 000 ₽</div>
                            <p>Все заказы, поступившие до 17:00 и подтвержденные нашим менеджером, доставляются по Москве в этот или следующий день, по Санкт-Петербургу &mdash; через день.</p>
                            <p>Сроки доставки в другие регионы России вы можете уточнить у наших операторов по телефону <br> <a href="tel:<?= \ishop\App::$app->getProperty('shop_tel_link'); ?>"><?= \ishop\App::$app->getProperty('shop_tel'); ?></a>.</p>
                            <p>При заказе от 10 000 ₽ доставка бесплатная!</p>
                            <!-- include ajax top text -->
                        </div> <!-- inf-page_collapse-top_content -->
                    </div> <!-- container -->
                </div>  <!-- inf-page_collapse-top collapse in -->
                <nav class="header_metabar">
                    <div class="container-fluid d-sm-flex">       
                        <ul class="header_metabar-list d-inline-flex d-sm-flex">
                            <li class="d-none d-md-block header_metabar-flag"></li>
                            <li><a href="tel:<?= \ishop\App::$app->getProperty('shop_tel_link'); ?>"><?= \ishop\App::$app->getProperty('shop_tel'); ?></a></li>
                            <li><a class="js-link-true2" href="<?= \ishop\Link::getLink('delivery'); ?>">Доставка и оплата</a></li>
                            <li class="d-none d-md-block"><a class="js-link-true2" href="<?= \ishop\Link::getLink('news'); ?>">Блог</a></li>
                            <li class="d-none d-md-block"><a href="<?= \ishop\Link::getLink('vozvrat'); ?>">Вернуть/обменять</a></li>                            
                        </ul>
                        <a class="header_metabar-promo d-none d-sm-block" role="button" data-toggle="collapse" data-campaign-id="free-delivery" href="#inf-page_collapse-top" aria-expanded="true" aria-controls="inf-page_collapse-top">Бесплатная доставка
                            <span class=""></span> от 5 000 <span class="rouble"><span class="rouble-text">руб</span></span></a>
                    </div>
                </nav> <!-- header_metabar -->                
                <!-- top-navbar -->
                <div class="top-navbar-wrap">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <div class="basket-small" aria-haspopup="true">
                            <?= app\widgets\cartmini\Cartmini::toString(); ?>
                        </div> <!-- basket-small -->
                        <a class="navbar-brand" href="/"><?= \ishop\App::$app->getProperty('shop_name'); ?></a>
                        <a class="mobile-header-search d-block d-xl-none" role="button" data-toggle="collapse" href="#header-search-collapse-top" aria-expanded="true" aria-controls="header-search-collapse-top"><i class="fa fa-search" aria-hidden="true"></i></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse mt-3 mt-lg-auto" id="navbarSupportedContent">
                            <ul class="navbar-nav mr-auto">
                                <?php
                                $menuCatalog = new app\widgets\menu\Menu([
                                    'tpl' => WWW . '/menu/menu_top.php',
                                    'container' => '',
                                    'cacheKey' => 'top_menu',
                                    'count_del' => 3,
                                ]);
                                ?>                           
                                <?= $menuCatalog; ?>
                                <li class="nav-item">
                                    <a class="nav-link js-link-true2" href="<?= \ishop\Link::getLink('sizechart'); ?>">Размерная сетка</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link js-link-true2" href="<?= \ishop\Link::getLink('contacts'); ?>">Контакты</a>
                                </li>
                            </ul>
                        </div> <!-- collapse navbar-collapse mt-3 mt-lg-auto -->
                    </nav> <!-- navbar navbar-expand-lg navbar-light -->
                    <div class="header-search-collapse-top collapse in" id="header-search-collapse-top" role="search" aria-expanded="true">
                        <div class="header-search">
                            <form action="search" method="get" autocomplete="off" class="form-inline my-lg-0">
                                <div class="input-group">
                                    <input class="form-control mr-sm-2 typeahead" type="search" id="typeahead" name="s" placeholder="ЧТО ВЫ ИЩЕТЕ?" aria-label="Поиск">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit"><i class="fa fa-search" aria-hidden="true"></i>
                                        </button> 
                                    </div>
                                </div><!-- input-group -->
                            </form>
                        </div> <!-- header-search -->
                    </div> <!-- header-search-collapse-top collapse in -->
                </div><!-- top-navbar-wrap -->
            </header>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php
                                echo $_SESSION['error'];
                                unset($_SESSION['error']);
                                ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <?php
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                                ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="modal-content-wrap"></div>
                <div class="content-wrap" id="content-wrap">
                    <?= $content; ?>
                </div>
                <div class="preloader">
                    <div>
                        <div>
                            <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
                            <span class="sr-only">Загрузка...</span>
                        </div>
                    </div>
                </div> 
            </div>
            <footer>
                <div class="container">
                    <nav class="footer-menu">
                        <div class="row">
                            <div class="footer-menu-section col-md-3">
                                <h5 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFootContact" aria-expanded="true" aria-controls="collapseFootContact">Контакты</a>
                                </h5>
                                <div id="collapseFootContact" class="panel-collapse collapse">
                                    <ul class="nav-pills" itemscope="" itemtype="http://schema.org/Organization">
                                        <li>
                                            <span itemprop="name"><?= \ishop\App::$app->getProperty('shop_name'); ?></span>
                                        </li>
                                        <li>   
                                            <a class="footer-menu-nav-link" href="tel:<?= \ishop\App::$app->getProperty('shop_tel_link'); ?>" title="Телефон" itemprop="telephone"><i class="fa fa-phone" aria-hidden="true"></i>
                                                <?= \ishop\App::$app->getProperty('shop_tel'); ?></a>
                                        </li>

                                        <li>
                                            <a class="footer-menu-nav-link" href="mailto:<?= \ishop\App::$app->getProperty('shop_email'); ?>" title="E-MAIL" itemprop="email"><i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                <?= \ishop\App::$app->getProperty('shop_email'); ?></a>
                                        </li>
                                        <li>
                                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                                            <span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress"><?= \ishop\App::$app->getProperty('shop_adress'); ?></span></span>
                                        </li>
                                    </ul>
                                </div>
                            </div> <!-- footer-menu-section col-md-3 -->
                            <div class="footer-menu-section col-md-3">
                                <h5 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFootCatalog" aria-expanded="true" aria-controls="collapseFootCatalog">Каталог</a>
                                </h5>
                                <div id="collapseFootCatalog" class="panel-collapse collapse">
                                    <ul class="nav-pills">
                                        <li><a href="<?= \ishop\Link::getLink('women'); ?>" title="Угги женские" class="js-link-true2">Угги женские</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('men'); ?>" title="Угги мужские" class="js-link-true2">Угги мужские</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('kids'); ?>" title="Угги детские" class="js-link-true2">Угги детские</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('women/new'); ?>" title="Новые коллекции угги" class="js-link-true2">Новинки угги</a></li>
                                    </ul>
                                </div>
                            </div> <!-- footer-menu-section col-md-3 -->
                            <div class="footer-menu-section col-md-3">
                                <h5 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFootNavigat" aria-expanded="true" aria-controls="collapseFootNavigat">Навигация</a>
                                </h5>
                                <div id="collapseFootNavigat" class="panel-collapse collapse">
                                    <ul class="nav-pills">
                                        <li><a href="<?= \ishop\Link::getLink('news'); ?>" class="js-link-true2" title="Новости, блог">Новости, блог</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('comment'); ?>" class="js-link-true2" title="Отзывы">Отзывы</a></li>                                        
                                        <li><a href="#myModalpromo2" role="button" class="js-link-modal js-popup" title="Задать вопрос" data-toggle="modal" data-dann="{&quot;id&quot;:&quot;2&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;askquestion&quot;}">Задать вопрос</a></li>                                        
                                        <li><a href="<?= \ishop\Link::getLink('vozvrat'); ?>" title="Контакты">Заявка на возврат/обмен</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('contacts'); ?>" class="js-link-true2" title="Контакты">Контакты</a></li>
                                    </ul>
                                </div>
                            </div> <!-- footer-menu-section col-md-3 -->
                            <div class="footer-menu-section col-md-3">
                                <h5 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFootPolinf" aria-expanded="true" aria-controls="collapseFootPolinf">Полезная информация</a>
                                </h5>
                                <div id="collapseFootPolinf" class="panel-collapse collapse">
                                    <ul class="nav-pills">
                                        <li><a href="<?= \ishop\Link::getLink('voprosy-otvety'); ?>" class="js-link-true2" title="Вопросы ответы">Вопросы ответы</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('garantiya'); ?>" class="js-link-true2" title="Гарантия">Гарантия</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('sizechart'); ?>" class="js-link-true2" title="Как выбрать размер">Размерная сетка</a></li>
                                        <li><a href="<?= \ishop\Link::getLink('polzovatelskoe-soglashenie'); ?>" class="js-link-true2" title="Оферта">Оферта</a></li>
                                    </ul>
                                </div>
                            </div> <!-- footer-menu-section col-md-3 -->
                        </div> <!-- row -->
                    </nav><!-- /.footer-menu -->
                    <p class="footer-copyright d-none d-md-block">&copy; <?= date('Y'); ?> <?= \ishop\App::$app->getProperty('shop_name'); ?>. All Rights Reserved.</p>
                </div><!-- container -->
            </footer>
            <!-- Modal -->
            <div class="modal add-to-cart-popup" id="addcardwindow" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Добавлено в корзину</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>			
                        <div class="modal-body">
                            <div class="mini-cart-product first"></div>
                            <div class="mini-cart-product-footer">
                                <a tabindex="0" href="<?= \ishop\Link::getLink('cart/view'); ?>" class="btn btn-fancy checkout-now">
                                    <span>Оформить заказ</span>
                                </a>
                                <button type="button" class="btn continue-shopping btn-transparent" data-dismiss="modal" aria-hidden="true">Продолжить покупки</button>
                            </div> <!-- mini-cart-product-footer -->
                            <div class="recommendation-content"></div>
                        </div> <!-- modal-body -->
                    </div><!-- modal-content-->
                </div> <!-- modal-dialog -->
            </div> <!-- modal fade add-to-cart-popup -->
            <div class="modal" id="qorderwindow" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Быстрый заказ</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>			
                        <div class="modal-body">
                            <form class="needs-validation" method="post" action="<?= \ishop\Link::getLink('cart/checkout'); ?>" novalidate>
                                <div class="input-group form-group">                                        
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
                                    </div>                                        
                                    <input type="text" name="name" class="form-control" placeholder="Имя*" value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '' ?>" required>
                                    <div class="invalid-feedback">
                                        Введите, пожалуйста, имя!
                                    </div>
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                                    </div>
                                    <input type="tel" name="phone" class="form-control" placeholder="Телефон*" value="<?= isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : '' ?>" pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$" aria-label="Телефон*" required>
                                    <div class="invalid-feedback">
                                        Введите, пожалуйста, телефон!
                                    </div>
                                </div>
                                <div class="input-group form-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-comment-o" aria-hidden="true"></i></span>
                                    </div>
                                    <textarea name="note" class="form-control" placeholder="Комментарии к заказу"><?= isset($_SESSION['form_data']['note']) ? $_SESSION['form_data']['note'] : '' ?></textarea>
                                </div>
                                <div class="checkbox input-group form-group">
                                    <label>
                                        <input class="form-control required igree" type="checkbox" checked name="i-agree" required> 
                                        <span> Я согласен с условиями </span>
                                        <span>
                                            <a href="<?= \ishop\Link::getLink('polzovatelskoe-soglashenie'); ?>" target="_blank">публичной оферты и обработкой моих персональных данных в порядке, предусмотренном публичной офертой</a> 
                                        </span>
                                        <span class="invalid-feedback">
                                            Поставьте, пожалуйста, галочку!
                                        </span>
                                    </label>

                                </div>
                                <div class="action-werap">
                                    <button type="submit" class="btn button-add-to-cart button-primary checkout-now">Отправить</button>
                                </div>
                            </form>
                        </div> <!-- modal-body -->
                    </div><!-- modal-content-->
                </div> <!-- modal-dialog -->
            </div> <!-- modal  qorderwindow-->
            <div class="modal quickview fade" id="myModalquickviewProduct" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content product_modal">
                        <div class="modal-header">
                            <h5 class="modal-title">Загружается...</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ...
                        </div>
                    </div>
                </div>
            </div> <!-- modal quickview -->
        </div><!-- page-wrap -->
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <?php $curr = \ishop\App::$app->getProperty('currency'); ?>
        <?php $js_link_true = \ishop\App::$app->getProperty('js_link_true') ? 'true' : 'false'; ?>
        <?php $js_link_true2 = \ishop\App::$app->getProperty('js_link_true2') ? 'true' : 'false'; ?>
        <script>
            var path = '<?= PATH; ?>',
                    course = <?= $curr['value']; ?>,
                    symboleLeft = '<?= $curr['symbol_left']; ?>',
                    symboleRight = '<?= $curr['symbol_right']; ?>',
                    js_link_true = <?= $js_link_true; ?>,
                    js_link_true2 = <?= $js_link_true2; ?>,
                    recomacces = <?= $_SESSION['recomacces']; ?>;
        </script>        
        <script src="/js/jquery-3.3.1.min.js"></script>     
<!--        <script src="js/popper.min.js"></script>-->
        <script src="/js/bootstrap.bundle.min.js"></script>        
<!--        <script src="js/bootstrap.min.js"></script>-->
        <script src="/js/ion.rangeSlider.min.js"></script>        
        <script src="/js/swiper/swiper.min.js"></script> 
        <script src="/js/jquery.inputmask.bundle.min.js"></script>
        <!-- Swiper JS Initialize Swiper -->
        <script>
            if ($(".basket-small *").is(".cart-small-full")) {
                var swipersmcard = new Swiper('.js-cart-small-swiper', {
                    slidesPerView: 2,
                    direction: 'vertical',
                    spaceBetween: 5,
                    watchOverflow: true,

                    navigation: {
                        nextEl: '.cart-small-swiper-button-next',
                        prevEl: '.cart-small-swiper-button-prev',
                    },

                });
            }

        </script>
        <script src="/js/typeahead/typeahead.bundle.min.js"></script>
        <script src="/js/scripts.js<?= $hash; ?>"></script>
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