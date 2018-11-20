<?php defined('_WORK') or die('Restricted access'); ?>
<?php
//debug ($product);
//die;
$hover = ($product['imgto'] == 1) ? 'true' : 'false';

$badgeclass = ($product['bagdeproduct_id'] > 0) ? '' : 'd-none ';
$badgeclass .= $product['bagdefixed'] == '1' ? 'tile-bagde-fixed' : '';
?>

<div class="card js-tile tile-mini" data-itemid="<?= $product['id']; ?>" itemscope="" itemtype="http://schema.org/Product">       
    <div class="tile_image product_image product-image" itemprop="image">
        <a class="thumb-link js-quickview" href="<?= $product['link']; ?>" title="<?= $product['title']; ?>" data-thumb="<?= $product['img_thumb']; ?>" data-hover="<?= $hover; ?>" data-srcset="<?= $product['srcset']; ?>">
            <img class="card-img" src="<?= $product['img_thumb']; ?>" alt="<?= $product['title']; ?>" title="<?= $product['title']; ?>" srcset="<?= $product['srcset']; ?>"<?= $product['imgsizes']; ?> data-alt_hoverimage="<?= $product['imgtoimg_srcset']; ?>">
        </a>
    </div>
    <div class="tile_meta">
        <h2 class="product-name" itemprop="name">
            <a class='js-quickview' href="<?= $product['link']; ?>">
                <span class="product-name_product">
                    <?= $product['title']; ?>
                </span>
            </a>
        </h2>
        <div class="price product_price product-price " itemprop="offers" itemscope="" itemtype="http://schema.org/Offer" role="heading" aria-level="3">
            <?php $oldpriceclass = ($product['old_price']) ? '' : 'd-none '; ?>
            <meta itemprop="priceCurrency" content="<?= $curr['code']; ?>">
            <link itemprop="availability" href="http://schema.org/InStock">
            <link itemprop="itemCondition" href="http://schema.org/NewCondition">

            <span class="price_standard product_price-sales price-sales" aria-label="Цена: <?= $curr['symbol_left']; ?><?= $product['old_price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?>" tabindex="0">
                <span class="product-sales-price old-price" title="Цена без скидки">
                    <?= $curr['symbol_left']; ?><span class="old-price-value"><?= $product['old_price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?>
                </span>
                <span class="product-sales-price action-price" title="Цена продажи">
                    <?= $curr['symbol_left']; ?><span class="action-price-value"><?= $product['price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?>
                </span>
            </span>
            <meta itemprop="price" content="<?= $product['price'] * $curr['value']; ?>">
        </div> <!-- price product_price product-price -->
        <div class="tile_quickadd">
            <a class="js-quickview" href="<?= $product['link']; ?>" title="<?= $product['title']; ?>">
                <span>Добавить в корзину</span>
            </a>
        </div> <!-- tile_quickadd -->
    </div> <!-- tile_meta -->    
</div> <!-- card js-tile tile tile-mini -->