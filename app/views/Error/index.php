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
                        <span itemprop="name">Страница не найдена</span></b>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-products sec">
            <header class='sec-products-header'>
                <h1 class="sec-title">Страница не найдена</h1>
                <div class="btn-group sort-panel">
                    <button class="btn btn-raised btn-secondary me-3 btn-cat-menu" type="button" data-toggle="drawer" data-target="#dw-p1">
                        Меню
                    </button>
                </div>
            </header>
            <div class="sec-content sec card wrap-404">
                <div class="card-body text-center">
                    <?php if ($webp_on) : ?>
                        <picture>
                            <source srcset="<?= imgSrcToWebP('/images/404.png') ?>" type="image/webp">
                            <source srcset="/images/404.png" type="image/png">
                            <img class="img-fluid" src="/images/404.png" alt="Страница не найдена">
                        </picture>
                    <?php else : ?>
                        <img class="img-fluid" src="/images/404.png" alt="Страница не найдена">
                    <?php endif; ?>                    
                    <p>Неправильно набран адрес, или такой страницы на сайте <span class="fw-bold"><?= PATH; ?></span> больше не существует.</p>
                    <p><a class="fw-bold js-link-true2" href="/">Вернитесь на главную</a></p>
                </div>
            </div>
        </div><!-- sec-products sec-->
    </div><!--  container-->
</div><!--  content-wr-->