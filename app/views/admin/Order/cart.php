<?php
$qty = 0;
foreach ($order_products as $product):
    ?>
    <tr>        
        <td>
            <?php if ($product->img_thumb) : ?>
                <?php if ($product->link) : ?>
                    <a href="<?= $product->link; ?>" target="_blank"><img class="img-thumbnail" src="<?= $product->img_thumb; ?>" style="max-height: 60px;" alt=""></a>
                <?php else: ?>
                    <img class="img-thumbnail" src="<?= $product->img_thumb; ?>" style="max-height: 60px;" alt="">
                <?php endif; ?>
            <?php endif; ?>
        </td>
        <td>
            <input type="text" name="cart[<?= $product->id; ?>][title]" class="form-control" value="<?= $product->title; ?>">
        </td>
        <td>
            <?php $qty += $product->qty; ?>
            <input type="number" name="cart[<?= $product->id; ?>][qty]" class="form-control" value="<?= $product->qty; ?>">
        </td>
        <td><input type="text" name="cart[<?= $product->id; ?>][price]" class="form-control" value="<?= $product->price; ?>"></td>
    </tr>
<?php endforeach; ?>
<tr>
    <td colspan="4"> 
        <div class="form-group">                                        
            <label>Добавить товар</label>
            <select name="newproduct" class="form-control select2" id="newcartproduct"></select>
        </div> 
    </td>
</tr>
<tr class="active">
    <td colspan="2">
        <b>Итого:</b>
    </td>
    <td><b><?= $qty; ?></b></td>
    <td><b><?= $summ; ?> <?= $currency; ?></b></td>
</tr>
