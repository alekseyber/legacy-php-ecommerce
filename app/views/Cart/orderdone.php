<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<div class="content-wr" data-selector="app-in-load-el">
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
                    <b class="disabled" itemprop="item">
                        <span itemprop="name">Заказ оформлен</span></b>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-page sec">
            <header class='sec-page-header'>
                <h1 class="sec-title">Заказ № <?= $order_num ?> получен</h1>
            </header>
            <p class="text-center fw-bold">Спасибо за Ваш заказ.</p>
            <p class="text-center fw-bold">В ближайшее время с Вами свяжется менеджер для согласования заказа.</p>
            <div class="text-center mt-2">
                <?php if ($webp_on) : ?>
                    <picture>
                        <source srcset="<?= imgSrcToWebP('/images/orderdoneimg.png') ?>" type="image/webp">
                        <source srcset="/images/orderdoneimg.png" type="image/png">
                        <img class="img-fluid" src="/images/orderdoneimg.png" alt="Спасибо за Ваш заказ.">
                    </picture>
                <?php else : ?>
                    <img class="img-fluid" src="/images/orderdoneimg.png" alt="Спасибо за Ваш заказ.">
                <?php endif; ?>
            </div>
        </div><!-- sec-page sec-->
    </div><!--  container-->
</div><!--  content-wr-->
<!-- <script></script> -->