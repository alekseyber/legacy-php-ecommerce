<?php defined('_WORK') or die('Restricted access'); ?>
<?php $link = \ishop\Link::getLinkProduct($category, 0, 'category'); ?>
<?php if (isset($category['childs'])) : ?>
    <div class="tab-pane" id="v-tab-cat<?= $category['alias']; ?>">
        <button type="button" class="btn btn-menu-nav" data-bs-target="#v-tab-cathome" data-bs-toggle="tableftmenu">
            <span class="material-icons left">arrow_back</span>
            <span><?= $category['title']; ?></span>
        </button>
        <div class="m-t-con list-group">
            <a class="list-group-item js-link-true2 drawer-cl" href="<?= $link; ?>">Все <?= mb_strtolower($category['title']); ?></a>
            <?= $this->getMenuHtml($category['childs'], 'itemchilds'); ?>
        </div>
    </div>
<?php else : ?>
    <?php if ($tab === 'itemchilds') : ?>
        <a class="list-group-item js-link-true2 drawer-cl" href="<?= $link; ?>"><?= $category['title']; ?></a>
    <?php endif; ?>
<?php endif; ?>