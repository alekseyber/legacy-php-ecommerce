<div class="main">
    <div class="container-fluid">
        <div class="main-title">
            <header>
                <h1><?= $contData['htitle']; ?></h1>
            </header>
            <div class="promo-copy">
                <?= $contData['promo']; ?>
            </div>
            <nav class="refinement-nav">
                <div>
                    <button class="btn btn-transparent js-panel-btn panel-btn" data-panel="secondary">
                        Перейти к выбору товаров
                    </button> 
                </div>
            </nav> <!-- search-rezult-wrap -->
        </div><!-- main-title -->
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a itemprop="item" class='js-link-true2' href="/"><span itemprop="name">Главная</span></a>
                    <meta itemprop="position" content="1"></li>
                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a itemprop="item" class="active-breadcrumbs" href="<?= \ishop\Link::getLink('vozvrat'); ?>"><span itemprop="name">Возврат/обмен</span></a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">Назад</a>
        </nav>
        <div class="row primary">
            <div class="col-lg-3">
                <div class="js-off-canvas secondary">
                    <aside class="js-panel form-r">
                        <header>
                            <h2 class="refinement-header">Уточните выбор:</h2>
                            <button class="js-panel-close-btn">Close</button>
                        </header>
                        <div class="refinements">
                            <section class="catalog-nav">
                                <div class="refinement">
                                    <h3 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseCatProduct" aria-expanded="true" aria-controls="collapseCatProduct">
                                            Категория продукта
                                        </a>
                                    </h3>
                                    <nav id="collapseCatProduct" class="panel-collapse collapse in show">
                                        <?= $menuCatalog; ?>
                                    </nav>
                                </div> <!-- refinement -->
                            </section><!-- catalog-nav -->                            
                            <?= $filter; ?>                            
                        </div><!-- refinements -->   
                    </aside> <!-- js-panel form-r -->
                </div>   <!-- js-off-canvas -->
            </div>   <!--  col-md-3 -->
            <div class="col-lg-9 right-block">
                <div class="search-result-content view-grid">
                    <?php if ($form): ?>
                        <?php $reCAPTCHA = \ishop\App::$app->getProperty('reCAPTCHA'); ?>
                        <h2 class="h4">Отправить заявку на обмен/возврат</h2>
                        <form class="needs-validation" method="post" action="<?= \ishop\Link::getLink('vozvrat'); ?>" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-vozvrat">
                                        <?php if ($msgerror): ?>
                                            <p class="invalid-phone"><?= $msgerror; ?></p>
                                        <?php endif; ?>
                                        <p><strong>Введите, пожалуйста, номер телефона, указанный в заказе:</strong></p>
                                        <div class="input-group form-group">                                    
                                            <input type="tel" name="phone" class="form-control" placeholder="Телефон*" pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$" value="<?php echo isset($_SESSION['form_data']['phone']) ? h($_SESSION['form_data']['phone']) : null; ?>" required>
                                            <div class="invalid-feedback">
                                                Введите, пожалуйста, телефон!
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Выберите операцию из списка</label>
                                        <?php $arr = [0 => 'Обмен', 1 => 'Возврат']; ?>
                                        <?php
                                        $action = $_SESSION['form_data']['action'] ?? 0;
                                        ?>
                                        <select class="form-control form-group" name="action" required>
                                            <?php
                                            foreach ($arr as $k => $v):
                                                $selected = '';
                                                if ($action == $k):
                                                    $selected = ' selected';
                                                    // $sel = false;
                                                    ?>
                                                <?php endif; ?>
                                                <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                            <?php endforeach; ?>
                                        </select>                                            
                                        <div class="invalid-feedback">
                                            Выберите, пожалуйста, действие!
                                        </div>
                                        <button type="submit" class="btn button-add-to-cart button-primary checkout-now disabled" id="returnzakaz">Отправить</button>
                                        <button class="g-recaptcha d-none" data-sitekey="<?= $reCAPTCHA['key']; ?>" data-callback="checedcapt"></button>
                                    </div> 
                                </div>
                            </div>
                        </form>
                        <script>
                            (function () {
                                'use strict';
                                window.addEventListener('load', function () {
                                    var forms = document.getElementsByClassName('needs-validation');

                                    var validation = Array.prototype.filter.call(forms, function (form) {
                                        form.addEventListener('submit', function (event) {
                                            if (form.checkValidity() === false) {
                                                event.preventDefault();
                                                event.stopPropagation();
                                            }
                                            form.classList.add('was-validated');
                                        }, false);
                                    });
                                }, false);
                            })();
                            function checedcapt(token) {
                                $('#returnzakaz').removeClass('disabled');
                                //console.log('INvice test passed');
                            }
                            function onloadCallback() {
                                grecaptcha.execute();
                            }
                        </script>
                        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
                        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>
                        <p>В соответствии с частью 4 статьи 26.1 Федерального закона «О защите прав потребителей» Вы имеете право в течение 14-ти календарных дней после получения заказа вернуть или обменять новую, не ношенную, не утратившую товарный вид, обувь, без объяснения причин.</p>
                        <p>При отказе потребителя от товара продавец должен возвратить ему денежную сумму, уплаченную потребителем по договору, за исключением расходов продавца на доставку от потребителя возвращенного товара, которые по городам Москва и Санкт-Петербург составляют – 500 рублей, в других городах России от 500 рублей в зависимости от удаленности региона по расценкам транспортной компании СДЭК или любой другой транспортной компании на выбор клиента.</p>                    
                        <h3 class="h5">Возврат денежных средств за товар осуществляется:</h3>
                        <ul>
                            <li>В Москве наличными курьером при заборе заказа;</li>
                            <li>В других городах на карту любого банка физического лица, указанную клиентом, не позднее одного рабочего дня, с момента получения товара обратно в интернет-магазин.</li>
                        </ul>
                        <p>Для оформления заказа, просим заполнить форму, размещенную выше, либо позвонить по телефону: <strong><a href="tel:<?= $shop_tel_link; ?>"><?= $shop_tel; ?></a></strong></p>
                    <?php else: ?>
                        <?= $msg; ?>
                    <?php endif; ?>
                    <div class="clearfix"></div>
                </div> <!-- search-result-content view-grid -->
            </div> <!-- col-lg-9 right-block  -->
        </div> <!-- row primary -->
    </div> <!-- container-fluid -->
</div> <!-- main -->