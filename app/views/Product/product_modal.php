<?php $p_widgets = new \app\widgets\product\Product(); ?>
<div itemscope="" itemtype="http://schema.org/Product">
    <?= $p_widgets->toString($product, 5); ?>
</div>