<?php defined('_WORK') or die('Restricted access'); ?>
<div class="content-wr" data-selector="app-in-load-el" data-mode-status="productmain">
    <div class="container">
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <?= $breadcrumbs; ?>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div itemscope="" itemtype="http://schema.org/Product">
            <div class="sec-product sec" data-selector="main-product">
                <?php $p_widgets = new \app\widgets\product\Product(); ?>
                <?= $p_widgets->toString($product, 4); ?>
            </div><!-- sec-product sec-->
            <div class="sec-content sec">
                <div class="row ">
                    <div class="col-lg-9">
                        <div class="card descr-main-pr">
                            <div class="card-body">
                                <h2 class="card-title fw-bold">Детали</h2>
                                <div itemprop="description">
                                    <?= $content; ?>
                                    <?= $filter; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <?php if ($related) : ?>
                            <h2 class="h5 fw-bold mt-2 mb-2 text-center">Вам так же может понравиться</h2>
                            <?= $p_widgets->toString($related); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div> <!-- itemtype Product -->
        <?php if ($recentlyViewed) : ?>
            <div class="sec-products sec">
                <h2 class="sec-title">Недавно просмотренные</h2>
                <div class="row g-2 pr-row">
                    <?php foreach ($recentlyViewed as $item) : ?>
                        <div class="col-sm-6 col-lg-3 mt-1 mb-1">
                            <?= $p_widgets->toString($item); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div><!-- sec-products sec -->
        <?php endif; ?>
    </div><!--  container-->
</div><!--  content-wr-->