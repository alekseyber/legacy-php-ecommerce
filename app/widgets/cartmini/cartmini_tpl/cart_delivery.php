<?php $symbol_left = $_SESSION['cart.currency']['symbol_left'] ?? null; ?>
<?php $symbol_right = $_SESSION['cart.currency']['symbol_right'] ?? null; ?>
<?php $delivery = diveltostring(); ?>
<?php $delivery_pvz = diveltostring(1); ?>
<div data-selector="app-in-load-el">
    <div class="fw-bold h5">Доставка</div>
    <table class="table cart-itog table-hover">
        <thead class='cart-itog-heder-cart'>
            <tr>
                <th class="section-header"></th>
                <th class="section-header"></th>
            </tr>
        </thead>
        <tbody> 
            <?php if ($delivery) : ?>
                <?php if ($delivery_pvz && isset($_SESSION['delivery_pvz'])) : ?>
                    <tr class="cart-row-divel">
                        <td class="colcell1-calc" colspan='2'><?= $delivery_pvz ?></td>
                    </tr>
                <?php else: ?>
                    <tr class="cart-row-divel">
                        <td class="colcell1-calc" colspan='2'><?= $delivery ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="cart-row-divel">
                    <td class="colcell1-calc" colspan='2'>
                        <button type="button" class="btn btn-raised btn-secondary js-link-modal btn-sm" data-href="#myModalpromo1" data-dann="{&quot;id&quot;:&quot;1&quot;,&quot;name&quot;:&quot;promo&quot;,&quot;modal&quot;:true,&quot;content_url&quot;:&quot;\/delivery&quot;}">
                            Изменить
                        </button>
                    </td>
                </tr>
            <?php else : ?>
                <tr class="cart-row-divel">
                    <td class="colcell1">Доставка Москва при покупке от <?= $symbol_left ?>5000 <?= $symbol_right ?>:</td>
                    <td class="colcell2">Бесплатно</td>
                </tr>
                <tr class="cart-row-divel">
                    <td class="colcell1">Доставка Москва при покупке до <?= $symbol_left ?>5000 <?= $symbol_right ?>:</td>
                    <td class="colcell2"><?= $symbol_left ?>300 <?= $symbol_right ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>