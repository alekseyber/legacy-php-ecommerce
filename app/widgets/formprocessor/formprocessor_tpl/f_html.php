<?php
$endteg = '';
$parentclass = $input['parentclass'] ?? '';
if ($parentclass) {
    ?>
    <div class="<?= $parentclass ?>">
        <?php
        $endteg .= '</div>';
    }
    ?>
    <?php if (isset($input['label']) && $input['label']) { ?><label><?= $input['label'] ?></label><?php } ?>
    <?= $input['default_v'] ?>
    <?= $endteg ?>