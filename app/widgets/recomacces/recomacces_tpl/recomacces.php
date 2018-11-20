<?php defined('_WORK') or die('Restricted access'); ?>
<div class="recommendation-zone recommendation-zone-added is-full">
    <?php if ($this->products): ?>
        <?php $products = $this->products; ?>
        <div class="recommendations recommendations-2 recommendations-mini">
            <div class="recommendations_copy">
                <h2 class="recommendations_header">
                    Рекомендуемые аксессуары
                </h2>
            </div>            
            <div class="container-fluid">
                <div class="recommendations_content row">	
                    <?php foreach ($products as $product): ?>
                        <div class="col-6 products-col">
                            <?= $product ?>
                        </div><!--  col-6  -->
                    <?php endforeach; ?>
                </div> <!-- recommendations_content row -->
            </div> <!-- container-fluid -->
        </div> <!-- recommendations recommendations-2 recommendations-mini -->
    <?php endif; ?>
</div> <!-- recommendation-zone recommendation-zone-added is-full -->