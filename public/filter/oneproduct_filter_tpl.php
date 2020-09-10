<?php defined('_WORK') or die('Restricted access'); ?>
<?php
$i = 0;
$s = 0;
$count = 5;
$arr = [0 => [], 1 => [], 2 => []];
foreach ($this->filter as $v) {
    if ($v['cartproduct']) {

        $index = 0;
        foreach ($v['attrs'] as $key => $val) {
            $index = $s == 0 && $i > $count;
            if ($s == 1)
                $index = 2;
            $arr[$index][] = $val['title'];
            $i++;
        }
        $s++;
    }
}
?>
<ul>
    <?php foreach ($arr[0] as $v) : ?>
        <li> <?= $v; ?></li>
    <?php endforeach; ?>
</ul>
<?php if ($arr[1] || $arr[2]) : ?>
    <?php if ($arr[1]) : ?>
        <ul>
            <?php foreach ($arr[1] as $v) : ?>
                <li> <?= $v; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($arr[2]) : ?>
        <div class="description_country">
            <?php foreach ($arr[2] as $v) : ?>
                <p><?= $v; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>