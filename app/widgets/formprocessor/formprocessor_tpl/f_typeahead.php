<?php
$endteg = '';
$class = $input['class'] ?? '';
$parentclass = $input['parentclass'] ?? '';
$messegeerror = $input['messegeerror'] ?? '';
$idinput = $input['id'] ?? 'typeahead_' . $input['name'];
if ($adminlte && $validate && !empty($input['rules'])) {
    $parentclass .= ' has-feedback';
}
$changid = isset($input['changid']);
if (isset($input['messegeerror'])) {
    $parentclass .= ' form-group';
}
$remote = '';
$title = 'title';
if (isset($input['remote'])) {
    $remote = $input['remote'];
}
$select = '';
if (isset($input['selectdata'][1])) {
    $title = $input['selectdata'][1];
    if ($input['select']) {
        $newselect = [];
        $i = 0;
        foreach ($input['select'] as $k => $v) {
            $newselect[$i]['id'] = $k;
            $newselect[$i][$title] = $v;
            $i++;
        }
        //  debug($newselect, 1);
        if ($newselect) {
            $select = json_encode($newselect);
        }
    }
}
if ($parentclass) {
    ?>
    <div class="<?= $parentclass ?>">
        <?php
        $endteg .= '</div>';
    }
    ?>
    <?php if (isset($input['label']) && $input['label']) { ?><label><?= $input['label'] ?></label><?php } ?>
    <input type="text" name="<?= $input['name'] ?>" id="<?= $idinput ?>" class="form-control typeahead <?= $class ?>" placeholder="<?= $input['default_v'] ?>" value="<?= $input['value'] ?>" <?php include "jsv.php"; ?>>
    <?php if ($messegeerror && !$adminlte && $validate) { ?><div class="invalid-feedback"><?= $messegeerror ?></div><?php } ?>
    <?php if ($adminlte && $validate) { ?><span class="glyphicon form-control-feedback" aria-hidden="true"></span><?php } ?>
    <?= $endteg ?>
    <?php
    if ($this->controller_obj) {
        ob_start();
        require 'jsbottom.php';
        $jsbottom = ob_get_clean();
        $this->controller_obj->setJs($jsbottom, false);
    } else {
        include '/formprocessor_tpl/jsbottom.php';
    }
    ?>