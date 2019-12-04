<?php defined('_WORK') or die('Restricted access'); ?>

<li class='nav-item'>
    <?php
    $title = mb_strtolower($category['title']);
    ?>
    <?php if (!isset($category['childs'])): ?>
        <?php $link = \ishop\Link::getLinkProduct($category, 0, 'category'); ?>
        <?php if ($link == $tab): ?>
            <b class="nav-link active"><?= $title; ?></b>
        <?php else: ?>
            <a class = "nav-link js-link-true2" href="<?= $link; ?>" title="Выбрать: <?= $title; ?>"><?= $category['title']; ?></a>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (isset($category['childs'])): ?>
        <div class="panel-title">
            <a class="collapsed" data-toggle="collapse" href="#collapseCatProduct<?= $category['alias']; ?>" aria-expanded="false" aria-controls="collapseCatProduct<?= $category['alias']; ?>"><?= $category['title']; ?></a>
        </div>    
        <ul id="collapseCatProduct<?= $category['alias']; ?>" class="children-list panel-collapse collapse">
            <li class="nav-item">
                <?php $link = \ishop\Link::getLinkProduct($category, 0, 'category'); ?>                
                    <?php if ($link == $tab): ?>
                    <b class="nav-link active"><?= $title; ?></b>
                <?php else: ?>
                    <a class = "nav-link js-link-true2" href="<?= $link; ?>" title="Выбрать: все <?= $title; ?>">Все <?= $title; ?></a>
                <?php endif; ?>
            </li>
            <?= $this->getMenuHtml($category['childs'], $tab); ?>
        </ul>
    <?php endif; ?>
</li>