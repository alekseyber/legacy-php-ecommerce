<?php $empty = empty($_SESSION['cart']); ?>
<div class="prt_cart card" data-selector="app-in-load-cart" data-empty="<?= $empty; ?>">
    <div class="card-body">
        <?php if (!$empty) : ?>
            <?php $symbol_left = $_SESSION['cart.currency']['symbol_left'] ?? null; ?>
            <?php $symbol_right = $_SESSION['cart.currency']['symbol_right'] ?? null; ?>
            <h3 class="card-title fw-bold">Корзина</h3>
            <div class="table-responsive">
                <table class="table item-list">
                    <thead class='table-heder-cart'>
                        <tr>
                            <th>
                                <div class="section-header">Продукт</div>
                                <div class="section-header">Детали</div>
                                <div class="section-header">Цена</div>
                                <div class="section-header">Кол-во</div>
                                <div class="section-header header-total-price">Сумма</div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $id => $item) : ?>
                            <tr class="cart-row-product">
                                <td class="product-col">
                                    <div class="col-image">
                                        <div class="item-image">
                                            <a class="js-link-true2" href="<?= $item['link'] ?>" title="<?= $item['title'] ?>">
                                                <img class="cart-img-item img-fluid" src="<?= $item['img_thumb'] ?>" alt="<?= $item['title'] ?>" title="<?= $item['title'] ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-details">
                                        <div class="item-details">
                                            <div class="product-list-item">
                                                <div class="name">
                                                    <a class="js-link-true2" href="<?= $item['link'] ?>" title="<?= $item['title'] ?>"><?= $item['title'] ?></a>
                                                </div>
                                                <div class="gender">
                                                    <span class="label">Пол:</span>
                                                    <span class="value"><?= $item['gender'] ?></span>
                                                </div>
                                                <?php if ($item['color_title']) : ?>
                                                    <div class="attribute attribute-colour">
                                                        <span class="label">Цвет:</span>
                                                        <span class="value">
                                                            <?= mb_strtoupper($item['color_title']) ?> / <?= $item['color_rustitle'] ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($item['sizes_title']) : ?>
                                                    <div class="attribute attribute-size">
                                                        <span class="label">Размер:</span>
                                                        <span class="value">
                                                            <?= $item['sizes_title'] ?>
                                                        </span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="item-quantity-details">
                                            <ul class="product-availability-list">
                                                <li class="is-in-stock">
                                                    <span class="in-stock">В наличии</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="item-price">
                                            <span class="label d-lg-none">
                                                Цена
                                            </span>
                                            <?php if ($item['old_price'] > 0) : ?>
                                                <span class="old-price"><?= $item['old_price'] ?></span>
                                            <?php endif; ?>
                                            <span class="price-sales"><?= $symbol_left ?><?= $item['price'] ?> <?= $symbol_right ?></span>
                                        </div>
                                        <div class="item-quantity">
                                            <span class="label d-lg-none">Количество</span>
                                            <div class="form-row ">
                                                <select class="form-select" data-selector="cart-item-qty" data-id="<?= $id ?>" name="qty_item">
                                                    <?php
                                                    $sost = false;
                                                    for ($i = 1; $i < 6; $i++) :
                                                        $selected = $item['qty'] == $i ? 'selected' : '';
                                                        if ($selected)
                                                            $sost = true;
                                                        ?>
                                                        <option value="<?= $i ?>" <?= $selected ?>><?= $i ?></option>
                                                    <?php endfor; ?>
                                                    <?php if (!$sost) : ?>
                                                        <option value="$item['qty']" selected><?= $item['qty'] ?></option>
                                                    <?php endif; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="item-total">
                                            <span class="label d-lg-none">
                                                Сумма
                                            </span>
                                            <span class="price-total">
                                                <?= $symbol_left ?><?= $item['price'] * $item['qty'] ?> <?= $symbol_right ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr class="cart-row-actions">
                                <td>
                                    <ul class="edit-remove">
                                        <li>
                                            <div class="item-edit-details">
                                                <button type="button" data-href="<?= $item['linkedit'] ?>" class="btn btn-raised btn-secondary js-quickview">Редактировать</button>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="item-delete">
                                                <a class="cart-small-deleteitem" href="<?= \ishop\Link::getLink('cart/delete'); ?>?id=<?= $id ?>" title="Удалить <?= $item['title'] ?>" data-id="<?= $id ?>" data-qty="<?= $item['qty'] ?>" data-tpl="cart">Удалить из корзины</a>
                                            </div>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="cart-row-cupon">
                            <td>
                                <a class="collapsed" data-bs-toggle="collapse" href="#collapsecupon">У меня есть промокод</a>
                                <div id="collapsecupon" class="collapse">
                                    <form 
                                        class="needs-validation" 
                                        method="post" 
                                        action="<?= \ishop\Link::getLink('cart/cupon'); ?>" 
                                        novalidate
                                        data-selector="app-cupon-form"
                                        >
                                        <div class="d-flex align-items-end mt-2">
                                            <div class="form-group">
                                                <label for="order-cupon_name" class="bmd-label-floating">Введите промокод</label>  
                                                <input type="text" name="cupon_name" class="form-control"  id="order-cupon_name" data-selector="textinput" required="">
                                                <div class="invalid-feedback">
                                                    Промокод не введен!
                                                </div>
                                            </div>
                                            <div class="ms-2">
                                                <button type="submit" class="btn btn-raised btn-secondary">Применить</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table cart-itog table-hover">
                    <thead class='cart-itog-heder-cart'>
                        <tr>
                            <th class="section-header"></th>
                            <th class="section-header"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_SESSION['promo']) && $_SESSION['promo'] < 1) : ?>
                            <tr class="cart-row-itog">
                                <td class="colcell1">Итого без скидки по промокоду:</td>
                                <td class="colcell2">
                                    <?= $_SESSION['cart.currency']['symbol_left'] . ($_SESSION['cart.sum'] / $_SESSION['promo']) . " {$_SESSION['cart.currency']['symbol_right']}" ?>
                                </td>
                            </tr>
                            <tr class="cart-row-itog">
                                <td class="colcell1">Скидка по промокоду:</td>
                                <td class="colcell2">
                                    <?= ((1 - $_SESSION['promo']) * 100) ?>%
                                </td>
                            </tr>
                        <?php endif; ?>
                        <tr class="cart-row-itog">
                            <td class="colcell1">Итого:</td>
                            <td class="colcell2">
                                <?= $_SESSION['cart.currency']['symbol_left'] . $_SESSION['cart.sum'] . " {$_SESSION['cart.currency']['symbol_right']}" ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div data-selector="deliv-inf-bl">
                    <?= $delivinfCont ?>
                </div>
            </div>
        <?php else : ?>
            <h3 class="card-title fw-bold">Корзина пуста</h3>            
            <?= $cartEmpty; ?>         
        <?php endif; ?>
    </div>
</div>