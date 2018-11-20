<?php
$parent_id = \ishop\App::$app->getProperty('parent_id');
$selected = '';
if ($id == $parent_id) {
  $selected = ' selected';  
}
if (is_array($parent_id) && isset($parent_id[$id])) {
    $selected = ' selected';
}
    
?>

<option value="<?= $id; ?>"<?= $selected; ?><?php if (isset($_GET['id']) && $id == $_GET['id']) echo ' disabled'; ?>>
    <?= $tab . $category['title']; ?>
</option>
<?php if (isset($category['childs'])): ?>
    <?= $this->getMenuHtml($category['childs'], '&nbsp;' . $tab . '-') ?>
<?php endif; ?>