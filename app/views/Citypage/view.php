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
                    <a class="disabled" itemprop="item" href="<?= \ishop\Link::getLink($contData['link']); ?>">
                        <span itemprop="name"><?= $contData['htitle']; ?></span></a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-products sec">
            <header class='sec-products-header'>
                <h1 class="sec-title"><?= $contData['htitle']; ?></h1>
                <div class="btn-group sort-panel">
                    <button class="btn btn-raised btn-secondary me-3 btn-cat-menu" type="button" data-toggle="drawer" data-target="#dw-p1">
                        Меню
                    </button>
                </div>
            </header>
            <?php if ($contData['content']) : ?>
                <div class="sec-content sec card">
                    <div class="card-body">
                        <div class="city-sec city-top-wrap"><?= $contData['content_top'] ?></div>
                        <?php if ($contData['catsTop']) : ?>
                            <div class="sec-main-cat sec">
                                <div class="row no-gutters">
                                    <?php foreach ($contData['catsTop'] as $cat) : ?>
                                        <div class="col-4">
                                            <a class="js-link-true2 img-cat-l" href="<?= \ishop\Link::getLinkProduct($cat, 0, 'category') ?>">
                                                <div class="media-cat">
                                                    <?php if ($webp_on) : ?>
                                                        <picture>
                                                            <source srcset="<?= imgSrcToWebP($contData['catPathImg'] . $cat['img']) ?>" type="image/webp">
                                                            <source srcset="<?= $contData['catPathImg'] . $cat['img'] ?>" type="<?= getTypeImg($cat['img']) ?>">
                                                            <img class="card-img" src="<?= $contData['catPathImg'] . $cat['img'] ?>" alt="<?= $cat['title'] ?>">
                                                        </picture>
                                                    <?php else : ?>
                                                        <img class="card-img" src="<?= $contData['catPathImg'] . $cat['img'] ?>" alt="<?= $cat['title'] ?>">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="img-cat-title"><?= $cat['title'] ?></div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div> <!-- sec-main-cat -->
                        <?php endif; ?>
                        <div class="city-sec city-inf-wrap">
                            <?php if ($contData['divel_title']) : ?>
                                <h2 class="city-divel-title"><?= $contData['divel_title'] ?></h2>
                            <?php endif; ?>
                            <?= $contentDelivery ?>
                        </div>
                        <?php if ($contData['content_on'] && $contData['content']) : ?>
                            <div class="city-sec city-content-wrap"><?= $contData['content'] ?></div>
                        <?php endif; ?>
                        <div class="city-sec city-bottom-wrap"><?= $contData['content_bottom'] ?></div>
                    </div>
                </div>
        </div><!-- sec-products sec-->
    <?php endif; ?>
    </div><!--  container-->
</div><!--  content-wr-->