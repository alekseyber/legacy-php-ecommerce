<?php defined('_WORK') or die('Restricted access'); ?>
<div class="card tile tile-expanded2 tile-product"> 
    <div class="tile-container product product-tile" data-itemid="<?= $product['id']; ?>" itemscope="" itemtype="http://schema.org/Product">
        <?php
        //debug ($product);
        //die;
        $hover = ($product['imgto'] == 1) ? 'true' : 'false';

        $badgeclass = ($product['bagdeproduct_id'] > 0) ? '' : 'd-none ';
        $badgeclass .= $product['bagdefixed'] == '1' ? 'tile-bagde-fixed' : '';
        ?>
        <div class="gender"><?= $product['gender']; ?></div>
        <div class="tile-image product-image" itemprop="image">
            <a class="thumb-link js-link-true2" href="<?= $product['link']; ?>" title="<?= $product['title'] . ' | ' . $product['gender']; ?>" data-thumb="<?= $product['img_thumb']; ?>" data-hover="<?= $hover; ?>" data-srcset="<?= $product['srcset']; ?>">
                <img class="card-img" src="<?= $product['img_thumb']; ?>" alt="<?= $product['title'] . ' | ' . $product['gender']; ?>" title="<?= $product['title'] . ' | ' . $product['gender']; ?>" srcset="<?= $product['srcset']; ?>"<?= $product['imgsizes']; ?> data-alt_hoverimage="<?= $product['imgtoimg_srcset']; ?>">
            </a>
        </div><!-- tile-image product-image -->
        <div class="tile-badge <?= $badgeclass; ?>">
            <div class="card-img-overlay product-badge <?= $product['bagderedcolor'] == '1' ? 'badge-red' : ''; ?>">                                                        
                <span><?= $product['bagdetitle']; ?></span>
            </div>
        </div>
        <div class="card-body tile-meta">
            <h3 class="tile-name product-name card-title h5" itemprop="name">
                <a class="product-name-link js-link-true2" href="<?= $product['link']; ?>" title="<?= $product['title'] . ' | ' . $product['gender']; ?>">
                    <?= $product['title']; ?>
                </a>
            </h3>
            <div class="tile-price product-pricing" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                <?php $oldpriceclass = ($product['old_price']) ? '' : 'd-none '; ?>

                <span class="product-sales-price old-price <?= $oldpriceclass; ?>" title="Цена без скидки">
                    <?= $curr['symbol_left']; ?><span class="old-price-value"><?= $product['old_price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?>
                </span>
                <span class="product-sales-price action-price" title="Цена продажи">
                    <?= $curr['symbol_left']; ?><span class="action-price-value"><?= $product['price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?>
                </span>
                <meta itemprop="price" content="<?= $product['price'] * $curr['value']; ?>">
                <meta itemprop="priceCurrency" content="<?= $curr['code']; ?>">
            </div><!-- tile-price product-pricing -->
            <div class="tile-swatches tile-swatches-list product-swatches tile_shop">
                <div class="variations variations-product" data-current="{&quot;color&quot;:{&quot;displayName&quot;:&quot;Color&quot;,&quot;value&quot;:&quot;BLK&quot;,&quot;displayValue&quot;:&quot;BLACK&quot;},&quot;size&quot;:{&quot;displayName&quot;:&quot;Size&quot;,&quot;value&quot;:&quot;05&quot;,&quot;displayValue&quot;:&quot;5&quot;}}">
                    <?php if ($product['product_model'] != '3' && $product['product_model'] != '4'): ?>
                        <div class="variations_color">
                            <h4 class="variations_title variations_title-color">
                                Выбранный цвет:	<?= mb_strtoupper($product['color_title']); ?>							
                            </h4>
                            <div class="clearfix"></div>
                            <div class="swatches swatches-color">
                                <?php foreach ($product['modification'] as $k => $v): ?>
                                    <?php $selected = ($product['color_default'] == $k) ? ' is-selected' : ''; ?>
                                    <a class="js-swatch-product swatches_item<?= $selected; ?> swatchclr-multi-<?= $k; ?> swatchclr-<?= $k; ?>" href="<?= $v['linkColor']; ?>" title="Выберите цвет <?= mb_strtoupper($v['title']); ?>" data-productcard="product_model_1">
                                        <?= mb_strtoupper($v['title']); ?>
                                    </a>
                                <?php endforeach; ?>                                        
                            </div> <!-- swatches swatches-color -->
                        </div> <!-- variations_color -->
                    <?php endif; ?> 
                    <div class="clearfix"></div>
                    <?php if ($product['sizesList']): ?>
                        <div class="variations_size">                                						
                            <div class="swatches swatches-size">
                                <div class="dropdown variations_size dropup">
                                    <button class="btn button-withe js-size-dropdown dropdown-toggle" type="button" id="sizedropdownmodel1<?= $product['modification_id']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?php if ($product['sizes_select']): ?>
                                            <?= $product['sizes_select']; ?>
                                        <?php else: ?>                                    
                                            Выберите размер
                                        <?php endif; ?>
                                    </button>
                                    <div class="dropdown-menu swatches swatches-size">
                                        <h4 class="variations_title variations_title-size">Выберите размер</h4>
                                        <div class="clearfix"></div>
                                        <?php foreach ($product['sizesList'] as $k => $v): ?>
                                            <?php $selected = $v['selected'] ? ' is-selected' : ''; ?>
                                            <?php $title = $v['selected'] ? 'Отменить выбор размера:' : 'Выбрать размер'; ?>
                                            <a class="js-swatch-product swatches_item swatches_item-in-stock<?= $selected; ?>" title="<?= $title; ?> <?= $v['title']; ?>" data-productcard="product_model_1" data-stock-msg="Размер <?= $v['title']; ?> - В наличии" href="<?= $v['linkSizes']; ?>">
                                                <?= $v['title']; ?>
                                            </a>
                                        <?php endforeach; ?>                                            
                                        <div class="clearfix"></div>
                                        <div class="js-stock-msg variations_stock-msg"></div>
                                    </div> <!-- dropdown-menu swatches swatches-size -->
                                </div> <!-- dropdown variations_size -->
                            </div> <!-- swatches swatches-size -->
                            <div class="clearfix"></div>
                        </div> <!-- variations_size -->
                    <?php endif; ?>
                </div> <!-- variations variations-product -->
                <div class="actions actions-product product-add-to-cart">
                    <!--   <h2 class="visually-hidden"></h2>   -->
                    <form action="cart/add" method="post" class="actions_form pdpForm" novalidate="novalidate">
                        <?php if ($product['addCard']): ?>
                            <?php foreach ($product['cardData'] as $k => $v): ?>
                                <?php if ($k == 'price'): ?>
                                    <?php $v = $v * $curr['value']; ?>
                                <?php endif; ?>
                                <input name="cardData[<?= $k; ?>]" value="<?= $v; ?>" type="hidden">
                            <?php endforeach; ?>                                            
                            <button class="btn button-primary checkout-now button-large button-add-to-cart add-to-cart">
                                Добавить в корзину
                            </button>
                        <?php else: ?> 
                            <button class="btn button-primary button-large button-add-to-cart buy-btn-no-size-mini">
                                Добавить в корзину
                            </button>     
                        <?php endif; ?>
                    </form>
                </div> <!-- actions actions-product product-add-to-cart -->
            </div><!-- tile-swatches tile-swatches-list product-swatches tile_shop -->
        </div><!-- card-body tile-meta -->
    </div><!-- tile-container product product-tile -->    
</div><!-- card tile tile-product -->