<div class="content-wr">
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
                <span class="material-icons mr-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-page sec">
            <header class='sec-page-header'>
                <h1 class="sec-title">Оформление заказа</h1>
            </header>
            <?php if (!empty($_SESSION['cart'])) : ?>
                <?php $cityData = app\widgets\delivery\Delivery::getCityData(); ?>
                <div class="sec-content sec card js-order-form" id="order-main-form">
                    <div class="card-body">
                        <form class="needs-validation" method="post" action="<?= \ishop\Link::getLink('cart/checkout'); ?>" novalidate>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend mr-2">
                                            <span class="input-group-text"><i class="material-icons">person_pin</i></span>
                                        </div>
                                        <input type="text" name="name" class="form-control" placeholder="Имя*" value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '' ?>" required>
                                        <div class="invalid-feedback">
                                            Введите, пожалуйста, имя!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend mr-2">
                                            <span class="input-group-text"><i class="material-icons">phone_iphone</i></span>
                                        </div>
                                        <input type="tel" name="phone" class="form-control" placeholder="Телефон*" value="<?= isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : '' ?>" pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$" required>
                                        <div class="invalid-feedback">
                                            Введите, пожалуйста, телефон!
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">Город:</div>
                                        <div class="js-city-btn-text mr-2 font-weight-bold"><?= $cityData['city_default'] ?></div>
                                        <button type="button" class="btn btn-danger bmd-btn-fab bmd-btn-fab-sm js-link-modal" data-href="#myModalpromo1" data-dann="{&quot;id&quot;:&quot;1&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;\/delivery&quot;}">
                                            <span class="material-icons">create</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="input-group form-group">
                                        <div class="input-group-prepend mr-2">
                                            <span class="input-group-text"><i class="material-icons">location_on</i></span>
                                        </div>
                                        <input type="text" name="address" class="form-control" placeholder="Адрес" value="<?= isset($_SESSION['form_data']['address']) ? $_SESSION['form_data']['address'] : '' ?>" aria-label="Адрес">
                                    </div>
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
                        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="js-cart-wrap">
                <?= app\widgets\cartmini\Cartmini::toString(false); ?>
            </div>
        </div><!-- sec-page sec-->
    </div><!--  container-->
</div><!--  content-wr-->
<!-- <script></script> -->