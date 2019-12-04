<?php
$endteg = '';
$parentclass = $input['parentclass'] ?? '';
$class = $input['class'] ?? '';
if ($parentclass) {
    ?>
    <div class="<?= $parentclass ?>">
        <?php
        $endteg .= '</div>';
    }
    if (isset($input['label']) && $input['label']) {
        ?><div><?= $input['label'] ?></div><?php } ?>    
    <div class="<?= $class ?>"> 
        <?= $input['value'] ?>
    </div>
    <?= $endteg ?>