<?php
$parent_id = \ishop\App::$app->getProperty('parent_id');
$selected = '';
if ($id == $parent_id) {
    $selected = ' selected';
}
if (is_array($parent_id) && isset($parent_id[$id])) {
    $selected = ' selected';
}
if (!$tab) {
    $tab = [
        'level' => 2,
        'parent' => $id
    ];
    $class = ' class="l1 non-leaf"';
    $datapup = '';
} else {
    $class = ' class="l' . $tab['level'] . ' non-leaf"';
    $datapup = ' data-pup="' . $tab['parent'] . '"';
    $tab = [
        'level' => $tab['level'] ++,
        'parent' => $id
    ];
}
?>
<option value="<?= $id; ?>"<?= $selected; ?><?php if (isset($_GET['id']) && $id == $_GET['id']) echo ' disabled'; ?><?= $class . $datapup; ?>>
    <?= $category['title']; ?>
</option>
<?php if (isset($category['childs'])): ?>
    <?= $this->getMenuHtml($category['childs'], $tab) ?>
<?php endif; ?>