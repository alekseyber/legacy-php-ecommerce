<?php
$endteg = '';
$class = $input['class'] ?? '';
$parentclass = $input['parentclass'] ?? '';
$idinput = $input['id'] ?? '';
if ($idinput) {
    $idinput = ' id="' . $idinput . '"';
}
if ($parentclass) {
    ?>
    <div class="<?= $parentclass ?>">
        <?php
        $endteg .= '</div>';
    }
    if (isset($input['label']) && $input['label']) {
        ?><label><?= $input['label'] ?></label><?php } ?>    
    <input class="form-control-file <?= $class ?>"<?= $idinput ?> type="file" name="<?= $input['name'] ?>" <?php include "jsv.php"; ?> />
<?= $endteg ?>