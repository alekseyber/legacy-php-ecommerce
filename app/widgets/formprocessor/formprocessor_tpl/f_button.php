<?php
$endteg = '';
$name = $input['name'] ?? '';
$class = $input['class'] ?? '';
$value = $input['default_v'] ?? '';
$idinput = $input['id'] ?? '';
if ($idinput) {
    $idinput = " id='$idinput'";
}
if (isset($input['parentclass'])) {
    ?>
    <div class="<?= $input['parentclass'] ?>">
        <?php
        $endteg .= '</div>';
    }
    ?>
    <button type="button" name="<?= $name ?>" class="<?= $class ?>" <?= $idinput ?> <?php include "jsv.php"; ?>><?= $value ?></button>
    <?= $endteg ?>