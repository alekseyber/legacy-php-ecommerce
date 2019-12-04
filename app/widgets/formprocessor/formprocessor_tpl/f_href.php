<?php
$endteg = '';
$parentclass = $input['parentclass'] ?? '';
$class = $input['class'] ?? '';
//$name2 = $input['name2'] ?? '';
if ($parentclass) {
    ?>
    <div class="<?= $parentclass ?>">
        <?php
        $endteg .= '</div>';
    }
?>    
    <a class="<?= $class ?>" href="<?= $input['default_v'] ?><?= $input['value'] ?>" <?php include "jsv.php"; ?>><?= $input['label2'] ?></a>
    <?= $endteg ?>