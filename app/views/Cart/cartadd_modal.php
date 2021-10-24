<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<?php if (!empty($_SESSION['cart'][$ID])) : ?>
    <?php $product = $_SESSION['cart'][$ID]; ?>    
    <div class="row no-gutters border-bottom border-top">
        <div class="col-3">
            <a class="text-center js-link-true2" data-selector="modal-close" href="<?= $product['link'] ?>" title="<?= $product['titlecard'] ?>">
                <?php if ($webp_on) : ?>
                    <picture>
                        <source srcset="<?= imgSrcToWebP($product['img_thumb']) ?>" type="image/webp">
                        <source srcset="<?= $product['img_thumb'] ?>" type="<?= getTypeImg($product['img_thumb']) ?>">
                        <img class="card-img" src="<?= $product['img_thumb'] ?>" alt="<?= $product['titlecard'] ?>">
                    </picture>
                <?php else : ?>
                    <img src="<?= $product['img_thumb'] ?>" class="card-img" alt="<?= $product['titlecard'] ?>">
                <?php endif; ?>
            </a>
        </div>
        <div class="col-9">
            <div class="pt-2">
                <div class="card-title">
                    <a class="js-link-true2" data-selector="modal-close" href="<?= $product['link'] ?>" title="<?= $product['titlecard'] ?>"><?= $product['titlecard'] ?></a>
                </div>
                <div class="card-text">
                    <?php if ($product['color_title']) : ?>
                        <div class="text-muted">
                            <span class="label">Цвет:</span>
                            <span class="value"><?= mb_strtoupper($product['color_title']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($product['sizes_title']) : ?>
                        <div class="text-muted">
                            <span class="label">Размер:</span>
                            <span class="value"><?= $product['sizes_title'] ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="price-product">
                    <div class="label">Цена:</div>
                    <?php if ($product['old_price']) : ?>
                        <div class="old-price"><?= $_SESSION['cart.currency']['symbol_left'] . $product['old_price'] . $_SESSION['cart.currency']['symbol_right']; ?></div>
                    <?php endif; ?>
                    <div class="price"> <?= $_SESSION['cart.currency']['symbol_left'] . $product['price'] . $_SESSION['cart.currency']['symbol_right']; ?></div>
                </div>
            </div>
        </div>
    </div>    
<?php endif; ?>