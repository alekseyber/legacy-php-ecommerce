<?php if ($products) : ?>
    <div data-selector="app-in-load-el">
        <?php $p_widgets = new \app\widgets\product\Product(); ?>
        <div class="row g-2 pr-row">
            <?php foreach ($products as $product) : ?>
                <div class="col-sm-6 col-lg-3 mt-1 mb-1">
                    <?= $p_widgets->toString($product); ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?= $paginationstr; ?>
    </div>
<?php endif; ?>