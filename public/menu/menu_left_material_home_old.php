<?php defined('_WORK') or die('Restricted access'); ?>
<?php if (isset($category['childs'])) : ?>
    <button type="button" class="btn btn-menu-nav js-l-return-tab" data-target="v-tab-Cat<?= $category['alias']; ?>">
        <span><?= $category['title']; ?></span>
        <span class="material-icons right">arrow_forward</span>
    </button>
<?php else : ?>
    <?php $link = \ishop\Link::getLinkProduct($category, 0, 'category'); ?>
    <a class="btn btn-menu-nav js-link-true2 drawer-cl" href="<?= $link; ?>">
        <span><?= $category['title']; ?></span>
    </a>
<?php endif; ?>