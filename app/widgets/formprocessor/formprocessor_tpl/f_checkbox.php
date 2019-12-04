<?php
$endteg = '';
$checked = '';
$class = $input['class'] ?? '';
$idinput = $input['id'] ?? '';
$messegeerror = $input['messegeerror'] ?? '';
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
    $name = $input['name'];
    $select = $input['select'] ?? [];
    if ($select && is_array($select)) :
        if ($idinput) {
            $idinput = ' id="' . $idinput . '"';
        }
        if (isset($input['label']) && $input['label']) {
            ?><div><strong><?= $input['label'] ?></strong></div><?php } ?>
        <?php
        $name .= '[]';
        foreach ($select as $k => $v) :
            $checked = '';
            if (isset($input['selected'][$k])) {
                $checked = ' checked ';
            }
            ?>
            <label><input class="form-check-input <?= $class ?>" type="checkbox" name="<?= $name ?>" value="<?= $k ?>"<?= $checked ?> /> <?= $v ?></label>
        <?php endforeach; ?>     
    <?php else: ?>
        <?php
        if (isset($input['swith']) && isset($input['selectedon'])):
            $checked = '';
            if ($input['selectedon'] == $input['value']) {
                $checked = ' checked ';
            } else {
                $input['value'] = $input['selectedon'];
            }
            if (!$idinput) {
                $idinput = $name . time() . mt_rand(0, 5);
            }
            ?>
            <div class="wrap-checkbox-on">
                <?php if (isset($input['label']) && $input['label']) { ?>
                    <div class="label-chekbox"><?= $input['label'] ?></div>
                <?php } ?>                
                <input class="<?= $class ?>" id="<?= $idinput ?>" type="checkbox" name="<?= $name ?>" value="<?= $input['value'] ?>"<?= $checked ?><?php include "jsv.php"; ?> />
                <label class="slider-v2" for="<?= $idinput ?>"></label>
            </div>
            <?php
        else:
            if ($idinput) {
                $idinput = ' id="' . $idinput . '"';
            }
            ?>
            <label><input class="form-check-input <?= $class ?>"<?= $idinput ?> type="checkbox" name="<?= $name ?>" value="<?= $input['value'] ?>"<?= $checked ?><?php include "jsv.php"; ?> />
                <?php if (isset($input['label']) && $input['label']) { ?><?= $input['label'] ?><?php } ?>
            </label>
        <?php endif; ?>  
    <?php endif; ?>
    <?php if ($messegeerror && !$adminlte && $validate) { ?><div class="invalid-feedback"><?= $messegeerror ?></div><?php } ?>
    <?php if ($adminlte && $validate) { ?><span class="glyphicon form-control-feedback" aria-hidden="true"></span><?php } ?>
    <?= $endteg ?>