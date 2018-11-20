<?php defined('_WORK') or die('Restricted access'); ?>
<?php
foreach ($this->filter as $v) {
    if ($v['cartproduct']) {        
        foreach ($v['attrs'] as $key => $val) { 
            $arr[0][] = $val['title'];
        }        
    }
}
?>
<div>
    <ul>
        <?php foreach ($arr[0] as $v): ?>
        <li> <?=$v;?></li>
        <?php endforeach; ?>
    </ul>
</div>
