<?php
$endteg = '';
$submit = true;
$name = $input['name'] ?? '';
$class = $input['class'] ?? '';
$value = $input['default_v'] ?? '';
if (isset($input['parentclass'])) {
    ?>
    <div class="<?= $input['parentclass'] ?>">
        <?php
        $endteg .= '</div>';
    }
    ?>
    <button type="submit" name="<?= $name ?>" class="<?= $class ?>" <?php include "jsv.php"; ?>><?= $value ?></button>
<?= $endteg ?>