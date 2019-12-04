<?php
$endteg = '';
$rows = $input['rows'] ?? '3';
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
    <textarea class="form-control <?= $class ?>"<?= $idinput ?> name="<?= $input['name'] ?>" rows="<?= $rows ?>" placeholder="<?= $input['default_v'] ?>" <?php include "jsv.php"; ?>><?= $input['value'] ?></textarea>
    <?php if ($messegeerror && !$adminlte && $validate) { ?><div class="invalid-feedback"><?= $messegeerror ?></div><?php } ?>
    <?php if ($adminlte && $validate) { ?><span class="glyphicon form-control-feedback" aria-hidden="true"></span><?php } ?>
    <?= $endteg ?>



