<?php
$endteg = '';
$checked = '';
$class = $input['class'] ?? '';
$messegeerror = $input['messegeerror'] ?? '';
$idinput = $input['id'] ?? '';
$name = $input['name'];
if ($idinput) {
    $idinput = ' id="' . $idinput . '"';
}
if ($input['value']) {
    $checked = ' checked ';
}
if (isset($input['checked'])) {
    if ($input['checked'] === true) {
        $checked = ' checked ';
    } else {
        $checked = '';
    }
}
if (!isset($input['parentclass'])) {
    $input['parentclass'] = 'form-check';
} else {
    $input['parentclass'] .= ' form-check';
}
?>
<?php if (isset($input['parentclass'])) { ?>
    <div class="<?= $input['parentclass'] ?>">
        <?php
        $endteg .= '</div>';
    }
    ?>
    <?php
    $select = $input['select'] ?? [];
    if ($select && is_array($select)) :
        ?>
        <?php if (isset($input['label']) && $input['label']) { ?><div><strong><?= $input['label'] ?></strong></div><?php } ?>
        <?php
        $selecton = false;
        foreach ($select as $k => $v) :
            $checked = '';
            if ($input['value'] == $k) :
                $checked = ' checked ';
                $selecton = true;
                ?>
            <?php endif; ?>
            <label><input class="form-check-input <?= $class ?>" type="radio" name="<?= $name ?>" value="<?= $k ?>"<?= $checked ?> /> <?= $v ?></label>
        <?php endforeach; ?> 
        <?php if (!$selecton) : ?>
            <label><input class="form-check-input <?= $class ?>" type="radio" checked /> Не выбрано</label>
        <?php endif; ?>
    <?php else: ?>
        <label><input class="form-check-input <?= $class ?>"<?= $idinput ?> type="radio" name="<?= $name ?>" value="<?= $input['value'] ?>"<?= $checked ?><?php include "jsv.php"; ?> />
            <?php if (isset($input['label']) && $input['label']) { ?><?= $input['label'] ?><?php } ?>
        </label>
    <?php endif; ?>
    <?php if ($messegeerror && !$adminlte && $validate) { ?><div class="invalid-feedback"><?= $messegeerror ?></div><?php } ?>
    <?php if ($adminlte && $validate) { ?><span class="glyphicon form-control-feedback" aria-hidden="true"></span><?php } ?>
    <?= $endteg ?>