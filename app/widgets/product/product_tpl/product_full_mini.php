<?php defined('_WORK') or die('Restricted access'); ?>
<?php
$badgeclass = $product['bagdefixed'] == '1' ? 'tile-bagde-fixed' : '';
?>
<div class="js-tile tile tile-expanded" data-itemid="<?= $product['id']; ?>" itemscope="" itemtype="http://schema.org/Product">
    <div class="tile_image product-image" itemprop="image">
        <a class="thumb-link js-link-true2" href="<?= $product['link']; ?>" title="<?= $product['title']; ?>">
            <img src="<?= $product['img_thumb']; ?>" alt="<?= $product['title']; ?>" title="<?= $product['title']; ?>" srcset="<?= $product['srcset']; ?>"<?= $product['imgsizes']; ?>>
        </a>
    </div>
    <?php if ($product['bagdeproduct_id'] > 0): ?>
        <div class="badge">
            <div class="product_badge <?= $product['bagderedcolor'] == '1' ? 'badge-red' : ''; ?>">
                <span><?= $product['bagdetitle']; ?></span>
            </div>
        </div>	
    <?php endif; ?>
    <div class="tile_meta">
        <h2 class="product-name" itemprop="name">
            <a class='js-link-true2' href="<?= $product['link']; ?>">
                <span class="product-name_product">
                    <?= $product['title']; ?> <?= $product['gender']; ?>
                </span>
            </a>
        </h2>
        <div class="price product_price product-price " itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" role="heading" aria-level="3">
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
        </div>
        <div class="variations variations-product">            
            <?php if ($product['product_model'] != '3' && $product['product_model'] != '4'): ?>
                <div class="variations_color">	
                    <h4 class="variations_title variations_title-color">
                        Выбранный цвет:	<?= mb_strtoupper($product['color_title']); ?>							
                    </h4>
                    <div class="clearfix"></div>
                    <div class="swatches swatches-color">                    
                        <?php foreach ($product['modification'] as $k => $v): ?>
                            <?php $selected = ($product['color_default'] == $k) ? ' is-selected' : ''; ?>
                            <a class="js-swatch-product swatches_item<?= $selected; ?> swatchclr-multi-<?= $k; ?> swatchclr-<?= $k; ?>" href="<?= $v['linkColor']; ?>" title="Выберите цвет <?= mb_strtoupper($v['title']); ?>" data-productcard="product_full_mini">
                                <?= mb_strtoupper($v['title']); ?>
                            </a>
                        <?php endforeach; ?>                   
                    </div> <!-- swatches swatches-color -->
                </div> <!-- variations_color -->
            <?php endif; ?>
            <div class="clearfix"></div>
            <?php if ($product['sizesList']): ?>
                <div class="dropdown variations_size">
                    <button class="btn button-withe js-size-dropdown dropdown-toggle" type="button" id="sizedropdownmodelfull<?= $product['modification_id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php if ($product['sizes_select']): ?>
                            <?= $product['sizes_select']; ?>
                        <?php else: ?>                                    
                            Выберите размер
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu swatches swatches-size">
                        <h4 class="variations_title variations_title-size">Выберите размер</h4>
                        <div class="swatches swatches-size">
                            <?php foreach ($product['sizesList'] as $k => $v): ?>
                                <?php $selected = $v['selected'] ? ' is-selected' : ''; ?>
                                <?php $title = $v['selected'] ? 'Отменить выбор размера:' : 'Выбрать размер'; ?>
                                <a class="js-swatch-product swatches_item swatches_item-in-stock<?= $selected; ?>" title="<?= $title; ?> <?= $v['title']; ?>" data-productcard="product_full_mini" data-stock-msg="Размер <?= $v['title']; ?> - В наличии" href="<?= $v['linkSizes']; ?>">
                                    <?= $v['title']; ?>
                                </a>
                            <?php endforeach; ?>							
                        </div> <!-- swatches swatches-size -->
                        <div class="clearfix"></div>
                        <div class="js-stock-msg variations_stock-msg"></div>
                        <?php app\models\Product::addAjaxLinkProduct($product, 'sizesgroup'); ?> 
                        <?php if ($product['ajax_link'][0]): ?>                                    
                            <a href="#myModal<?= $product['ajax_link'][0]['nameid']; ?>" class="js-link-modal link-size-chart" data-toggle="modal" data-dann="<?= $product['ajax_link'][0]['dann']; ?>"><?= $product['ajax_link'][0]['link_title']; ?></a>
                        <?php endif; ?>
                    </div> <!-- dropdown-menu swatches swatches-size -->
                </div> <!-- dropdown variations_size -->
            <?php endif; ?> 
        </div> <!-- variations variations-product -->
        <div class="actions actions-product product-add-to-cart">            
            <form action="cart/add" method="post" class="actions_form pdpForm" novalidate="novalidate">
                <?php if ($product['addCard']): ?>
                    <?php foreach ($product['cardData'] as $k => $v): ?>
                        <?php if ($k == 'price'): ?>
                            <?php $v = $v * $curr['value']; ?>
                        <?php endif; ?>
                        <input name="cardData[<?= $k; ?>]" value="<?= $v; ?>" type="hidden">
                    <?php endforeach; ?>                                            
                    <button class="btn button-primary button-large checkout-now button-add-to-cart add-to-cart">
                        Добавить в корзину
                    </button>
                    <button class="btn button-secondary button-large checkout-now button-add-to-cart add-to-cart js-q-order">
                        Быстрый заказ
                    </button>
                <?php else: ?> 
                    <button class="btn button-primary button-large button-add-to-cart buy-btn-no-size-mini">
                        Добавить в корзину
                    </button>     
                <?php endif; ?>
            </form>
        </div> <!-- actions actions-product product-add-to-cart -->
    </div> <!-- tile_meta -->
</div> <!-- js-tile tile tile-expanded -->