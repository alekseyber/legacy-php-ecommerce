<div class="content-wr" data-selector="app-in-load-el" data-mode-status="jsform">
    <div class="container">
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a class="js-link-true2" itemprop="item" href="/">
                        <span itemprop="name" class="material-icons">home</span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a class="disabled" itemprop="item" href="<?= \ishop\Link::getLink('cart/view'); ?>">
                        <span itemprop="name">Заказ оформлен</span></a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-page sec">
            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <header class='sec-page-header'>
                <h1 class="sec-title">Оформление заказа</h1>
				<?php $discont = getDiscontProc(); ?>
				<?php if ($discont): ?>
				<h5 class="sec-title">Ваша персональная скидка от суммы в корзине составляет - <?= $discont ?> %.</h5>				
				<?php endif; ?>
            </header>
            <?php if (!empty($_SESSION['cart'])) : ?>
                <?php $cityData = app\widgets\delivery\Delivery::getCityData(); ?>
                <div class="sec-content sec card" data-selector="order-form">
                    <div class="card-body" data-selector="js-form" data-name="order">
                        <form class="needs-validation" method="post" action="<?= \ishop\Link::getLink('cart/checkout'); ?>" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order-name" class="bmd-label-floating">Имя*</label>
                                        <input 
                                            type="text" 
                                            id="order-name"
                                            name="name"
                                            class="form-control"
                                            value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '' ?>" 
                                            required
                                            data-selector="textinput"
                                            >
                                        <div class="invalid-feedback">
                                            Введите, пожалуйста, имя!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order-phone" class="bmd-label-floating">Телефон*</label>
                                        <input 
                                            type="tel" 
                                            name="phone"
                                            data-selector="textinput"
                                            id="order-phone"
                                            data-selector="textinput"
                                            class="form-control"
                                            value="<?= isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : '' ?>"
                                            pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$"
                                            required
                                            inputmode="tel"
                                            >
                                        <div class="invalid-feedback">
                                            Введите, пожалуйста, телефон!
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 d-flex align-items-end">
                                    <div class="d-flex align-items-center mt-3">
                                        <div class="me-2">Город:</div>
                                        <div class="fw-bold me-2 fs-4" data-selector="city-btn-cart"><?= $cityData['city_default'] ?></div>
                                        <button type="button" class="btn btn-raised btn-secondary js-link-modal btn-sm" data-href="#myModalpromo1" data-dann="{&quot;id&quot;:&quot;1&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;\/delivery&quot;}">
                                            Изменить
                                        </button> 
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="order-address" class="bmd-label-floating">Адрес</label>
                                        <input 
                                            data-selector="textinput"
                                            id="order-address"
                                            type="text" 
                                            name="address" 
                                            class="form-control" 
                                            value="<?= isset($_SESSION['form_data']['address']) ? $_SESSION['form_data']['address'] : '' ?>" 
                                            >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="order-note" class="bmd-label-floating">Комментарии к заказу</label>
                                <textarea
                                    name="note"
                                    id="order-note"
                                    data-selector="textareainput"
                                    class="form-control"
                                    rows="5"
                                    ><?= isset($_SESSION['form_data']['note']) ? h($_SESSION['form_data']['note']) : '' ?></textarea>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" name="i-agree" type="checkbox" checked value="1" id="order-i-agree" required>
                                <label class="form-check-label small" for="order-i-agree">
                                    <span> Я согласен с условиями </span>
                                    <span>
                                        <a href="<?= \ishop\Link::getLink('polzovatelskoe-soglashenie'); ?>" target="_blank">публичной оферты и обработкой моих персональных данных в
                                            порядке, предусмотренном публичной офертой</a>
                                    </span>
                                </label>
                                <div class="invalid-feedback">
                                    Поставьте, пожалуйста, галочку!
                                </div>
                            </div>
                            <div class="order-action">
                                <button type="submit" class="btn btn-primary btn-raised">Отправить</button>
                            </div>
                        </form>
                        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div data-selector="cart-wrap">
                <?= app\widgets\cartmini\Cartmini::toString(false); ?>
            </div>
        </div><!-- sec-page sec-->
    </div><!--  container-->
</div><!--  content-wr-->
<!-- <script></script> -->