<?php
$endteg = '';
$class = $input['class'] ?? '';
$classselectbkgr = '';
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
    if (isset($input['bgrstatus'])) {
        $class = $input['value'] ? ' success' : '';
        $classselectbkgr = 'order-status-select';
        $endteg .= '</div>';
        ?>
        <div class="order-status-bgr<?= $class; ?>">
        <?php } ?>
        <?php if (isset($input['label']) && $input['label']) { ?><label><?= $input['label'] ?></label><?php } ?>  
        <select class="form-control formprcessor select multiple <?= $class ?> <?= $classselectbkgr ?>"<?= $idinput ?> name="<?= $input['name'] ?>" <?php include "jsv.php"; ?> >
            <option></option>
            <?php
            $select = $input['select'] ?? [];
            $selecton = false;
            foreach ($select as $k => $v) :
                $selected = '';
                if ($input['value'] == $k) :
                    $selected = 'selected ';
                    $selecton = true;
                    ?>
                <?php endif; ?>
                <option <?= $selected; ?>value="<?= $k; ?>"><?= $v; ?></option>
            <?php endforeach; ?>
        </select>
        <?= $endteg ?>