<!doctype html>
<?php
	$hash = '';
	if (HASH_ON) {
		$hash = '?' . time();
	}
	$webp_on = \ishop\App::$app->getProperty('webp_on') ? true : false;
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
	$cityData = app\widgets\delivery\Delivery::getCityData();
	$curr = \ishop\App::$app->getProperty('currency');
	$js_link_true = \ishop\App::$app->getProperty('js_link_true') ? 1 : 0;
	$js_link_true2 = \ishop\App::$app->getProperty('js_link_true2') ? 1 : 0;
	$reCAPTCHA = \ishop\App::$app->getProperty('reCAPTCHA');
	$rez = [
    'course' => $curr['value'],
    'symbLeft' => $curr['symbol_left'],
    'symbRight' => $curr['symbol_right'],
    'reCAPTCHA' => $reCAPTCHA['key'],
    'webp_on' => $webp_on,
	];
	if (!$js_link_true) {
		$rez['jsLOff'] = true;
	}
	if (!$js_link_true2) {
		$rez['jsLOff2'] = true;
	}
	$paramsJson = h(json_encode($rez));
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
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script defer="defer" src="/assets/js/700.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/677.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/600.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/281.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/135.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/app-d1ac236a.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/app-4d77c890.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/app-7e07305a.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/app-611a5edf.js?569208ae69b563c27090"></script><script defer="defer" src="/assets/js/app-40b701ec.js?569208ae69b563c27090"></script><link href="/assets/css/app-9d2749cb.css?569208ae69b563c27090" rel="stylesheet">		
        <?= $this->getHeader(); ?>
		
	</head>
    <body data-params="<?= $paramsJson; ?>">
        <div data-selector="modal-content"></div>
        <div class="bmd-layout-container bmd-drawer-f-l bmd-drawer-overlay">
            <header class="bmd-layout-header">
                <div class="top-bg-nav">
                    <ul class="nav">
                        <li class="nav-item">
                            <button type="button" class="btn nav-link js-link-modal" data-selector="city-btn" data-href="#myModalpromo1" data-dann="{&quot;id&quot;:&quot;1&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;\/delivery&quot;}">
                                <?= $cityData['city_default'] ?>
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
                        <button
						class="btn bmd-btn-icon search-btn"
						type="button"
						data-bs-toggle="collapse"
						data-bs-target="#collapse-search"
						aria-expanded="false"
						aria-controls="collapse-search"
						>
                            <i class="material-icons">search</i>
						</button>
                        <div id="collapse-search" class="collapse collapse-search-wr">
                            <div
							class="dropdown"
							data-selector="app-typeahead"
							data-bs-source="products"
							>
                                <div data-bs-toggle="dropdown-t">
                                    <div class="input-group reset-btn">
                                        <input
										type="text"
										class="form-control"
										placeholder="Поиск..."                                         
										autocomplete="new-password"
										/>
                                        <button class="btn-close" data-selector="app-typeahead-clear" type="button"><i class="material-icons">close</i></button>
									</div>                 
								</div>
                                <ul class="dropdown-menu"></ul>
							</div>
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
                        <span class="ms-1"><?= \ishop\App::$app->getProperty('shop_name'); ?></span>
					</a>
                    <button type="button" class="navbar-toggler" data-toggle="drawer" data-target="#dw-p1" aria-expanded="false">                      
                        <span class="sr-only">Toggle drawer</span>
                        <span class="material-icons right">arrow_back</span>
					</button>
				</header>
                <div class="list-group">
                    <div class="product-cat-menu sec-l-menu">
                        <div class="list-group-item m-t-menu" id="left-cat-menu">
                            <div 
							class="tab-content"                                
							data-selector="tableftmenugrupp"
							data-bs-def-target="#v-tab-cathome"
							data-bs-leftcatalog="true"
							>
                                <div class="tab-pane active" id="v-tab-cathome">
                                    <div class="nav nav-tabs">
                                        <?= $menuLeftCatalogHome; ?>
									</div>
								</div>
                                <?= $menuLeftCatalog; ?>
							</div>
						</div>
					</div>
                    <div data-selector="app-left-filter"><?= $filter_content; ?></div>                    
                    <div class="page-menu sec-l-menu">
                        <div class="list-group-item m-t-menu" id="left-page-menu">
                            <div 
							class="tab-content"
							data-selector="tableftmenugrupp"
							>
                                <div class="tab-pane active" id="v-tab-pagehome">
                                    <div class="nav nav-tabs">
                                        <button 
										type="button" 
										class="btn btn-menu-nav" 
										data-bs-target="#v-tab-pagelist"
										data-bs-toggle="tableftmenu"
										>
                                            <span>Информация</span>
                                            <span class="material-icons right">arrow_forward</span>
										</button>
									</div>
								</div>
                                <div class="tab-pane" id="v-tab-pagelist">
                                    <button 
									type="button" 
									class="btn btn-menu-nav" 
									data-bs-target="#v-tab-pagehome"
									data-bs-toggle="tableftmenu"
									>
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
                            <button 
							type="button" 
							class="btn btn-menu-nav btn-drk drawer-cl" 
							data-href="#askquestion"
							data-toggle="askquestion"
							data-title="Заказать звонок"
							><span>Заказать звонок</span></button>
						</div>
					</div>
				</div>
			</div>
            <main class="bmd-layout-content">
                <div class="fixed-bl d-none"></div>
                <div class="page-content" data-selector="page-content">
                    <?= $content; ?>
				</div>
                <div class="preloader">
                    <div>
                        <div class="d-flex align-items-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
							</div>
						</div>
					</div>
				</div>
                <div class="top-scr-btn">
                    <button type="button" class="btn btn-primary bmd-btn-fab">
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
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						
					</div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])) : ?>
					<div class="alert alert-success alert-dismissible fade show">
						<?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
						?>
						<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
					</div>
                    <?php endif; ?>
                    <div
					class="alert alert-danger alert-dismissible fade"
					data-selector="alert-danger"
					data-message=""            
					>            
                        <button
						type="button"
						class="btn-close"
						data-bs-dismiss="alert"
						></button>
					</div>
                    <div
					class="alert alert-success alert-dismissible fade"
					data-selector="alert-success"
					data-message=""
					>            
                        <button
						type="button"
						class="btn-close"
						data-bs-dismiss="alert"
						></button>
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
								</div>
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
                                        <li><a href="#askquestion" data-toggle="askquestion" data-title="Задать вопрос">Задать вопрос</a></li>
                                        <li><a class="js-link-true2" href="<?= \ishop\Link::getLink('vozvrat'); ?>">Заявка на возврат/обмен</a></li>
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
							</div>
						</nav>
                        <p class="footer-copyright">&copy; <?= date('Y'); ?>
						<?= \ishop\App::$app->getProperty('shop_name'); ?>. Все права защищены.</p>
					</div>
				</footer>
			</main>
		</div>
        <?php $cartqty = $_SESSION['cart.qty'] ?? 0; ?>
        <button
		type="button"
		class="btn btn-top-cart"      
		data-qty="<?= $cartqty; ?>"
		data-selector="btn-top-cart"     
		><span class="material-icons">shopping_cart</span> </button>
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