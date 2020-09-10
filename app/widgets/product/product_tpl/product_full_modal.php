<?php $curr = $this->options['curr']; ?>
<div class="product-modals-wrap">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <div class="product_images product-image-container">
                    <div id="product-images-modal" class="js-product-images carousel-pdp carousel slide" data-ride="carousel"  data-interval="false">
                        <div class="carousel-inner">
                            <?php
                            if ($product['gallery']):
                                $i = 1;
                                $countimg = count($product['gallery']);
                                foreach ($product['gallery'] as $v):
                                    $active = ($i == 1) ? ' active' : '';
                                    ?>
                                    <div class="carousel-item<?= $active; ?>">
                                        <div class="zoom">
                                            <img class="item_image d-block" itemprop="image" src="<?= $v['img_main']; ?>" srcset="<?= $v['srcset']; ?>"<?= $v['imgsizes']; ?> alt="<?= $product['title']; ?> - image <?= $i; ?> of <?= $countimg; ?>">
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                endforeach;
                                ?>
                            <?php elseif ($product['img']): ?>
                                <div class="carousel-item active">
                                    <img class="item_image d-block" itemprop="image" src="<?= $product['img_main']; ?>" alt="<?= $product['title']; ?> - image 1 of 1">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="carousel_nav carousel_nav-icons">
                            <a class="carousel-control-prev" href="#product-images-modal" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true">
                                    <svg width="24" height="24" viewBox="0 0 24 24" x="0" y="160">
                                    <path fill="#333" d="M9 3v6h15v6h-15v6l-9-8.91z"></path>
                                    </svg>
                                </span>	
                            </a>
                            <a class="carousel-control-next" href="#product-images-modal" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true">
                                    <svg width="24" height="24" viewBox="0 0 24 24" x="40" y="160">
                                    <path fill="#333" d="M15 3l9 8.91-9 9.090v-6h-15v-6h15v-6z"></path>
                                    </svg>
                                </span>
                            </a>
                        </div> <!-- carousel_nav carousel_nav-icons -->
                        <div class="thumbnails thumbnails-pdp carousel-indicators" aria-hidden="true">
                            <?php
                            if ($product['gallery']):
                                $i = 1;
                                foreach ($product['gallery'] as $v):
                                    ?>
                                    <img class="thumbnails_item " data-target="#product-images-modal" data-slide-to="<?= $i - 1; ?>" src="<?= $v['img_thumb']; ?>" alt="<?= $product['title']; ?> - thumbnail image <?= $i - 1; ?> of <?= $countimg; ?>" data-wa-event="multiViewClick" data-wa-event-type="click">
                                    <?php
                                    $i++;
                                endforeach;
                                ?>
                            <?php endif; ?>
                        </div> <!-- thumbnails thumbnails-pdp carousel-indicators -->
                    </div>	 <!-- js-product-images carousel slide -->
                </div> <!-- product__images product-image-container -->
            </div> <!-- col-md-6 -->
            <div class="col-sm-6 modal-right">
                <div class="product_primary product-detail">
                    <div id="product-content" class="js-product-content">
                        <div class="product_content">
                            <div class="product_detail">
                                <div class="product_heading">
                                    <h1 class="product-name" itemprop="name">
                                        <a class='js-link-true2' href="<?= $product['link']; ?>">
                                            <span class="product-name_gender">
                                                <?= $product['gender']; ?>
                                            </span>
                                            <span class="product-name_product">
                                                <?= $product['title']; ?>
                                            </span>
                                        </a>
                                    </h1>
                                    <div>
                                        <div class="price product_price product-price" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" role="heading" aria-level="3">
                                            <meta itemprop="priceCurrency" content="<?= $curr['code']; ?>">
                                            <link itemprop="availability" href="http://schema.org/InStock">
                                            <link itemprop="itemCondition" href="http://schema.org/NewCondition">
                                            <span class="product-price-wrap">  
                                                <?php if ($product['old_price']): ?>
                                                    <span class="product-sales-price old-price" title="Цена без скидки: <?= $curr['symbol_left']; ?><?= $product['old_price'] * $curr['value']; ?> <?= $curr['symbol_right']; ?>"> 
                                                        <?= $curr['symbol_left']; ?><?= $product['old_price'] * $curr['value']; ?> <?= $curr['symbol_right']; ?>
                                                    </span>
                                                <?php endif; ?>
                                                <span class="price_standard product_price-sales price-sales" aria-label="Цена: <?= $curr['symbol_left']; ?><?= $product['price'] * $curr['value']; ?> <?= $curr['symbol_right']; ?>" tabindex="0">
                                                    <?= $curr['symbol_left']; ?><?= $product['price'] * $curr['value']; ?> <?= $curr['symbol_right']; ?>                                                
                                                    <meta itemprop="price" content="<?= $product['price'] * $curr['value']; ?>">								
                                                </span>
                                            </span> <!-- product-price-wrap -->
                                        </div><!-- price product_price product-price -->
                                        <!--  <div class="pdp-share-button">
                                          </div>  pdp-share-button -->
                                    </div>
                                </div>  <!-- product_heading -->
                                <div class="product_tab-loyalty">
                                    <img class="logouggmain" alt="<?= $product['brand_title']; ?>" src="<?= $product['brand_img']; ?>">
                                </div> <!-- product_tab-loyalty -->
                                <div class="variations variations-product">
                                    <?php if ($product['product_model'] != '3' && $product['product_model'] != '4'): ?>
                                        <div class="variations_color">                                        
                                            <h4 class="variations_title variations_title-color">
                                                Выбранный цвет: <?= mb_strtoupper($product['color_title']); ?>							
                                            </h4>
                                            <div class="clearfix"></div>
                                            <div class="swatches swatches-color">                                        
                                                <?php foreach ($product['modification'] as $k => $v): ?>
                                                    <?php $selected = ($product['color_default'] == $k) ? ' is-selected' : ''; ?>
                                                    <a class="js-swatch-product swatches_item<?= $selected; ?> swatchclr-multi-<?= $k; ?> swatchclr-<?= $k; ?>" href="<?= $v['linkColor']; ?>" title="Выберите цвет <?= mb_strtoupper($v['title']); ?>" data-productcard="product_modal">
                                                        <?= mb_strtoupper($v['title']); ?>
                                                    </a>
                                                <?php endforeach; ?>                                         
                                            </div> <!-- swatches swatches-color -->
                                        </div> <!-- variations_color -->
                                    <?php endif; ?> 
                                    <div class="clearfix"></div>
                                    <?php if ($product['sizesList']): ?>
                                        <div class="variations_size">
                                            <h4 class="variations_title variations_title-size">Выберите размер</h4>	
                                            <div class="js-stock-msg variations_stock-msg"></div>							
                                            <div class="swatches swatches-size">
                                                <?php foreach ($product['sizesList'] as $k => $v): ?>
                                                    <?php $selected = $v['selected'] ? ' is-selected' : ''; ?>
                                                    <?php $title = $v['selected'] ? 'Отменить выбор размера:' : 'Выбрать размер'; ?>
                                                    <a class="js-swatch-product swatches_item swatches_item-in-stock<?= $selected; ?>" title="<?= $title; ?> <?= $v['title']; ?>" data-productcard="product_modal" data-stock-msg="Размер <?= $v['title']; ?> - В наличии" href="<?= $v['linkSizes']; ?>">
                                                        <?= $v['title']; ?>
                                                    </a>
                                                <?php endforeach; ?>
                                                <p class="p-no-select-size">Пожалуйста, выберите размер</p>
                                            </div> <!-- swatches swatches-size -->
                                            <div class="clearfix"></div>
                                            <?php if ($product['ajax_link'][0]): ?>                                    
                <!--                                                <a href="#myModal<?= $product['ajax_link'][0]['nameid']; ?>" class="js-link-modal link-size-chart" data-toggle="modal" data-dann="<?= $product['ajax_link'][0]['dann']; ?>"><?= $product['ajax_link'][0]['link_title']; ?></a>-->
                                            <?php endif; ?>
                                        </div> <!-- variations_size -->
                                    <?php endif; ?> 
                                </div> <!-- variations variations-product -->
                                <div class="actions actions-product product-add-to-cart">
                                    <!-- <h2 class="visually-hidden">Product Actions</h2> -->
                                    <form action="cart/add" method="post" class="actions_form pdpForm" novalidate="novalidate">
                                        <?php if ($product['addCard']): ?>
                                            <?php foreach ($product['cardData'] as $k => $v): ?>
                                                <?php if ($k == 'price'): ?>
                                                    <?php $v = $v * $curr['value']; ?>
                                                <?php endif; ?>
                                                <input name="cardData[<?= $k; ?>]" value="<?= $v; ?>" type="hidden">
                                            <?php endforeach; ?>                                            
                                            <button class="btn button-primary checkout-now button-large button-add-to-cart add-to-cart">                                                
                                                <?= $product['b_cart_text']; ?>
                                            </button>
                                            <button class="btn button-secondary button-large checkout-now button-add-to-cart add-to-cart js-q-order">
                                                Быстрый заказ
                                            </button>
                                        <?php else: ?> 
                                            <button class="btn button-primary button-large button-add-to-cart buy-btn-no-size-prim">
                                                Добавить в корзину
                                            </button>  
                                        <?php endif; ?>
                                    </form>
                                </div> <!-- actions actions-product product-add-to-cart -->
                                <p class="text-center">

                                    <?php if ($product['ajax_link'][1]): ?>
                                        <?= $product['ajax_link'][1]['link_title']; ?>
            <!--                                        <a href="#myModal<?= $product['ajax_link'][1]['nameid']; ?>" class="js-link-modal js-popup" data-toggle="modal" data-dann="<?= $product['ajax_link'][1]['dann']; ?>"><?= $product['ajax_link'][1]['link_title']; ?></a>-->
                                    <?php endif; ?>
                                </p>
                            </div> <!-- product_detail -->
                        </div> <!-- product_content --> 
                    </div> <!-- js-product-content -->
                </div>	<!-- product_primary product-detail	 -->
            </div> <!-- col-md-6 -->
        </div><!-- row -->
    </div><!-- container-fluid-->
</div><!-- product-modal-wrap-->