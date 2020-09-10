<?php defined('_WORK') or die('Restricted access'); ?>
<div class="main">    
    <?php
    if ($product):
        $curr = \ishop\App::$app->getProperty('currency');
        //debug($product);
        ?>
        <div class="product-main pdp-main" itemscope="" itemtype="http://schema.org/Product">
            <div class="mainproduct">
                <?php $p_widgets = new \app\widgets\product\Product(); ?>
                <?= $p_widgets->toString($product, 4); ?>
            </div>	<!-- mainproduct 	 -->
            <div class="clearfix"></div>
            <div class="product_secondary">
                <a class="product_view-details js-scrollto" href="#product-details">Детали</a>
                <div class="container-fluid">
                    <nav class="nav-breadcrumb">
                        <ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
                            <?= $breadcrumbs; ?>
                        </ol>
                        <a href="javascript:history.back();" class="breadcrumbs-back">Назад</a>
                    </nav><!-- nav-breadcrumb -->
                </div> <!-- container-fluid -->
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <section id="product-details" class="description description-product">
                                <h2>Детали</h2>
                                <div class="description_model">
                                    Model:
                                    <span itemprop="productID"><?= $product['sku']; ?></span>
                                </div>
                                <div id="short-description" itemprop="description"><?= $content; ?></div>
                                <?= $filter; ?>  
                                <meta itemprop="sku" content="<?= $product['sku']; ?>">
                                <meta itemprop="brand" content="<?= $product['brand_title']; ?>">
                            </section> <!-- description description-product -->
                        </div> <!-- col-lg-8 -->
                        <?php if ($recentlyViewed || $related): ?>
                            <?php $p_widgets = new \app\widgets\product\Product(); ?>
                        <?php endif; ?>
                        <div class="col-lg-4">
                            <div class="recommendation-zone recommendation-zone-highlight">
                                <div class="recommendations recommendations-1 recommendations-expanded">
                                    <?php if ($related): ?>
                                        <div class="recommendations_copy">
                                            <h2 class="recommendations_header">
                                                Вам также может понравиться
                                            </h2>
                                        </div> <!-- recommendations_copy -->
                                        <div class="recommendations_content product_full_mini">
                                            <?= $p_widgets->toString($related, 3); ?>
                                        </div> <!-- recommendations_content -->
                                    <?php endif; ?>
                                </div> <!-- recommendations recommendations-1 recommendations-expanded -->
                            </div> <!-- ecommendation-zone recommendation-zone-highlight -->
    <!--                            <section class="video video-product"></section>-->
                        </div> <!-- col-lg-4 -->
                    </div> <!-- row -->	
                </div> <!-- container -->
            </div> <!-- product_secondary -->
            <div class="product_last_view view-grid">
                <div class="container">
                    <div class="recommendation-zone">
                        <?php if ($recentlyViewed): ?>
                            <div class="recommendations_copy">
                                <h2 class="recommendations_header">
                                    Недавно просмотренные
                                </h2>
                            </div>
                            <div class="products-row row">
                                <?php foreach ($recentlyViewed as $v): ?>
                                    <div class="col-6 col-md-4 col-lg-3 products-col-nofl product_model_1">                                        
                                        <?= $p_widgets->toString($v, 1); ?>
                                    </div> <!-- col-6 col-md-4 col-lg-3 products-col -->
                                <?php endforeach; ?>
                            </div> <!-- products-row row -->
                        <?php endif; ?>
                    </div> <!-- recommendation-zone -->
                </div> <!-- container -->
            </div> <!-- search-result-content view-grid -->
        </div><!-- product-main -->
    <?php endif; ?>
</div> <!-- main -->