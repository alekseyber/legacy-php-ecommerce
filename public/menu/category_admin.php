<?php defined('_WORK') or die('Restricted access'); ?>
<?php
$parent = isset($category['childs']);
$del = true;
if ($category['cat_default'] || $parent) {
    $del = false;
}


$status = $category['status'] ? '<b class="status-cat"><i class="fa fa-dot-circle-o"></i></b>' : '<b class="status-cat"><i class="fa fa-circle-o"></i></b>';
$count = \R::count('product', 'category_id = ?', [$id]);
$count = '<b class="status-cat">' . $count . '</i></b>';

if ($del) {
    $delete = '<a href="' . ADMIN . '/category/delete?id=' . $id . '" class="delete del-cat"><i class="fa fa-fw fa-close text-danger"></i></a>';
} else {
    //$delete = '<i class="fa fa-fw fa-close"></i>';
    $delete = '<b class="del-cat"><i class="fa fa-fw fa-close"></i></b>';
}
?>
<p class="item-p">

    <a class="list-group-item" href="<?= ADMIN; ?>/category/edit?id=<?= $id; ?>"><?= $category['title']; ?></a>
    <span class="cat-action">
        <span><?= $count; ?></span>
        <?php if (!$category['cat_default']): ?>
            <span>   
                <label for="sortvalue">Сорт:</label>            
                <input type="text" name="sortvalue" class="cat-sortvalue js-input-form" pattern="^[1-9]{1,}$" value="<?= $category['sortvalue']; ?>" data-id="<?= $id; ?>" data-oldval="<?= $category['sortvalue']; ?>" data-pattern="true" data-table="category" data-action="<?= ADMIN; ?>/category/edit-value">           
            </span>
        <?php endif; ?>

        <a class="btn btn-success" href="<?= ADMIN; ?>/category/edit?id=<?= $id; ?>">Редактировать</a>
        <a class="btn btn-info" href="<?= ADMIN; ?>/product?id=<?= $id; ?>">Продукты</a>
        <span><?= $status; ?></span>        
        <span><?= $delete; ?></span>

    </span>



</p>
<?php if ($parent): ?>
    <div class="list-group">
        <?= $this->getMenuHtml($category['childs']); ?>
    </div>
<?php endif; ?>
