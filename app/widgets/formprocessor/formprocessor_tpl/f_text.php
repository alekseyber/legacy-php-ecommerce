<?php
$endteg = '';
$class = $input['class'] ?? '';
$parentclass = $input['parentclass'] ?? '';
$messegeerror = $input['messegeerror'] ?? '';
$idinput = $input['id'] ?? '';
if ($idinput) {
    $idinput = ' id="' . $idinput . '"';
}
if ($adminlte && $validate && !empty($input['rules'])) {
   $parentclass .= ' has-feedback';   
}
if (isset($input['messegeerror'])) {
    $parentclass .= ' form-group';
}
?>
<?php if ($parentclass) { ?>
    <div class="<?= $parentclass ?>">
        <?php
        $endteg .= '</div>';
    }
    ?>
    <?php if (isset($input['label']) && $input['label']) { ?><label><?= $input['label'] ?></label><?php } ?>
    <input class="form-control <?=$class ?>"<?= $idinput ?> type="text" name="<?= $input['name'] ?>" value="<?= $input['value'] ?>" placeholder="<?= $input['default_v'] ?>" <?php include "jsv.php"; ?> />
    <?php if ($messegeerror && !$adminlte && $validate) { ?><div class="invalid-feedback"><?= $messegeerror ?></div><?php } ?>
    <?php if ($adminlte && $validate) { ?><span class="glyphicon form-control-feedback" aria-hidden="true"></span><?php } ?>
    <?= $endteg ?>