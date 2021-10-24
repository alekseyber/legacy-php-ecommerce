<?php defined('_WORK') or die('Restricted access'); ?>
<?php $webp_on = $this->options['webp_on']; ?>
<div class="card product">
    <a class="js-link-true2 pr-l-link js-l-edit" href="<?= $product['link']; ?>">
        <div class="pr-bage">
            <?php if ($product['bagdeproduct_id']) : ?>
                <div class="bage<?= $product['bagderedcolor'] == '1' ? ' primary' : ''; ?>"><?= $product['bagdetitle']; ?></div>
            <?php endif; ?>
        </div>
        <div class="pr-media">
            <div>
                <?php if ($webp_on) : ?>
                    <picture>
                        <source srcset="<?= imgSrcToWebP($product['srcset']) ?>" type="image/webp" sizes="<?= $product['srcsetsizes']; ?>">
                        <source srcset="<?= $product['srcset']; ?>" type="<?= getTypeImg($product['img_thumb']) ?>" sizes="<?= $product['srcsetsizes']; ?>">
                        <img class="card-img-top" src="<?= $product['img_thumb']; ?>" alt="<?= $product['title'] . ' | ' . $product['gender']; ?>">
                    </picture>
                <?php else : ?>
                    <img class="card-img-top" src="<?= $product['img_thumb']; ?>" alt="<?= $product['title'] . ' | ' . $product['gender']; ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="pr-meta card-body">
            <?php if ($product['gender']) : ?>
                <div class="pr-gender"><?= $product['gender']; ?></div>
            <?php endif; ?>
            <div class="pr-title"><?= $product['title']; ?></div>
        </div>
    </a>
    <div class="card-body">
        <div class="price-product">
            <div class="old-price"><span class="value"><?php if ($product['old_price']) : ?><?= $product['old_price'] * $curr['value']; ?><?php endif; ?></span></div>
            <div class="price"><?= $curr['symbol_left']; ?><span class="value"><?= $product['price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?></div>
        </div>
        <?php if ($product['product_model'] != '3' && $product['product_model'] != '4' && $product['modification']) : ?>
            <div class="colors-product">
                <div class="colors-title">Выберите цвет:</div>
                <div class="colors-body">
                    <?php
                    foreach ($product['modification'] as $key_modif => $modification) :
                        $selected = '';
                        $selected = ($product['color_default'] === $key_modif) ? ' selected' : '';
                        $badgeon = $modification['bagdeproduct_id'] ? 'true' : 'false';
                        $badgesale = ($modification['bagderedcolor'] == '1') ? 'true' : 'false';
                        $price = $product['price'] * $curr['value'];
                        if ($modification['price']) {
                            $price = $modification['price'] * $curr['value'];
                        }
                        $old_price = '';
                        if ($modification['old_price'] > 0) {
                            $old_price = $modification['old_price'] * $curr['value'];
                        }
                        ?>
                        <a class="btn colors-btn js-colors-btn bmd-btn-icon bmd-btn-icon-sm<?= $selected; ?>" style="background-color: <?= $modification['colorkey']; ?>;" href="<?= $modification['linkColor']; ?>" data-price="<?= $price; ?>" data-oldprice="<?= $old_price; ?>" data-badgeon="<?= $badgeon; ?>" data-badge="<?= $modification['bagdetitle']; ?>" data-badgesale="<?= $badgesale; ?>" data-thumb="<?= $modification['data_js']; ?>">
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="btn-wr">
            <button type="button" data-href="<?= $product['link']; ?>" class="btn btn-primary btn-raised btn-add-card js-quickview js-b-edit">Подробнее</button>
        </div>
    </div>
</div>