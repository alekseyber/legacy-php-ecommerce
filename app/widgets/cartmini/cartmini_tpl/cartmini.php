<?php defined('_WORK') or die('Restricted access'); ?>
<?php $cartqty = $_SESSION['cart.qty'] ?? 0; ?>
<?php $cartsum = $_SESSION['cart.sum'] ?? 0; ?>
<?php $symbol_left = $_SESSION['cart.currency']['symbol_left'] ?? null; ?>
<?php $symbol_right = $_SESSION['cart.currency']['symbol_right'] ?? null; ?>
<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<div data-qty="<?= $cartqty ?>" data-selector="app-in-load-cart-mini">
    <?php if (empty($_SESSION['cart'])) : ?>
        <div class="cart-small-empty">
            <?= $cartEmpty; ?>
            <div class="row mt-2 ps-2 pe-2">
                <div class="p-1 col-sm-6">
                    <button type="button" class="btn btn-raised btn-secondary w-100" data-bs-dismiss="modal">Продолжить покупки</button>
                </div>            
            </div>
        </div>
    <?php else : ?>
        <?php $products = $_SESSION['cart']; ?>
        <?php foreach ($products as $key => $item) : ?>           
            <div class="row no-gutters mb-2 border-bottom">
                <div class="col-3 text-center">
                    <?php if ($webp_on) : ?>
                        <picture>
                            <source srcset="<?= imgSrcToWebP($item['img_thumb']) ?>" type="image/webp">
                            <source srcset="<?= $item['img_thumb'] ?>" type="<?= getTypeImg($item['img_thumb']) ?>">
                            <img class="card-img" src="<?= $item['img_thumb'] ?>" alt="<?= $item['title'] ?>">
                        </picture>
                    <?php else : ?>
                        <img src="<?= $item['img_thumb'] ?>" class="card-img" alt="<?= $item['title'] ?>">
                    <?php endif; ?>
                </div>
                <div class="col-7">                        
                    <div class="pt-2">
                        <a class="fw-bold js-link-true2" data-selector="modal-close" href="<?= $item['link'] ?>"><?= $item['title'] ?></a>
                        <?php
                        if ($item['qty'] > 1) {
                            $summitem = ' х ' . $item['qty'] . ' шт. = ' . $symbol_left . $item['summ'] . ' ' . $symbol_right;
                        } else {
                            $summitem = ' х ' . $item['qty'] . ' шт.';
                        }
                        ?>
                        <div class="mt-2" data-price="<?= $item['price'] ?>">
                            <div><span class="price"><?= $symbol_left ?><?= $item['price'] ?> <?= $symbol_right ?></span><span><?= $summitem ?></span></div>
                        </div>
                    </div>                        
                </div>
                <div class="col-2 text-center">
                    <a class="text-danger" data-selector="cart-small-del" href="<?= \ishop\Link::getLink('cart/delete'); ?>?id=<?= $key ?>" data-id="<?= $key ?>">                        
                        <span class="material-icons">delete</span>
                    </a>
                </div>
            </div>           
        <?php endforeach; ?>
        <div class="mt-2 mb-3 text-end fw-bold">Итого: <?= $symbol_left ?><span><?= $cartsum ?></span> <?= $symbol_right ?></div>
        <div class="row mt-2 ps-2 pe-2">
            <div class="p-1 col-sm-6">
                <button type="button" class="btn btn-raised btn-secondary w-100" data-bs-dismiss="modal">Продолжить покупки</button>
            </div>
            <div class="p-1 col-sm-6">
                <a class="btn btn-raised btn-primary w-100 js-link-true2" href="<?= \ishop\Link::getLink('cart/view'); ?>" data-selector="modal-close">Оформить заказ</a>  
            </div>
        </div>
    <?php endif; ?>
</div>