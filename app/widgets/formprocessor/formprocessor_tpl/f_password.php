<?php
$endteg = '';
$parentclass = $input['parentclass'] ?? '';
$messegeerror = $input['messegeerror'] ?? '';
$class = $input['class'] ?? '';
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
    <input class="form-control <?php
    if (isset($input['class'])) {
        echo $input['class'];
    }
    ?>" type="password" name="<?= $input['name'] ?>"<?= $idinput ?> class="<?= $idinput ?>" value="<?= $input['value'] ?>" placeholder="<?= $input['default_v'] ?>" <?php include "jsv.php"; ?> />
    <?php if ($messegeerror && !$adminlte && $validate) { ?><div class="invalid-feedback"><?= $messegeerror ?></div><?php } ?>
    <?php if ($adminlte && $validate) { ?><span class="glyphicon form-control-feedback" aria-hidden="true"></span><?php } ?>
    <?= $endteg ?>