<?php
$endteg = '';
$class = $input['class'] ?? '';
$parentclass = $input['parentclass'] ?? '';
$name = $input['name'] . '[]';
$idinput = $input['id'] ?? '';
if ($idinput) {
    $idinput = ' id="' . $idinput . '"';
}
if (isset($input['parentclass'])) {
    ?>
    <div class="<?= $input['parentclass'] ?>">
        <?php
        $endteg .= '</div>';
    }
    if (isset($input['label']) && $input['label']) {
        ?><label><?= $input['label'] ?></label><?php } ?>  
    <select multiple class="form-control formprcessor select multiple <?= $class ?>"<?= $idinput ?> name="<?= $name ?>" <?php include "jsv.php"; ?> >
        <option></option>
        <?php
        $select = $input['select'] ?? [];
        foreach ($select as $k => $v) :
            $selected = '';
            if (isset($input['selected'][$k])) {
                $selected = ' selected ';
            }
            ?>            
            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
        <?php endforeach; ?>
    </select>
    <?= $endteg ?>