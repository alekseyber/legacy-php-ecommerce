<?php
$endteg = '';
$class = $input['class'] ?? '';
$parentclass = $input['parentclass'] ?? '';
$idinput = $input['id'] ?? '';
if ($idinput) {
    $idinput = ' id="' . $idinput . '"';
}
?>
<?php if ($parentclass) { ?>
    <div class="<?= $parentclass ?>">
        <?php
        $endteg .= '</div>';
    }
    ?>
    <?php if (isset($input['label']) && $input['label']) { ?><label><?= $input['label'] ?></label><?php } ?>    
    <input class="form-control-range <?=$class ?>"<?= $idinput ?> type="range" name="<?= $input['name'] ?>" value="<?= $input['value'] ?>"/>    
    <?= $endteg ?>