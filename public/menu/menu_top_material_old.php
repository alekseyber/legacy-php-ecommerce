<?php defined('_WORK') or die('Restricted access'); ?>
<?php $path_img = \ishop\App::$app->getProperty('img_category_path'); ?>
<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<?php if ($category['parent_id'] > 0) : ?>
    <?php if (!isset($category['childs'])) : ?>
        <?php if ($tab === 'groups') : ?>
            <a class="list-group-item js-link-true2" href="<?= \ishop\Link::getLinkProduct($category, 0, 'category'); ?>"><?= $category['title']; ?></a>
        <?php else : ?>
            <li class="nav-item"><a class="nav-link js-link-true2" href="<?= \ishop\Link::getLinkProduct($category, 0, 'category'); ?>"><?= $category['title']; ?></a></li>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (isset($category['childs'])) : ?>
        <li class='dropdown nav-item'>
            <button class="nav-link dropdown-toggle" type="button" id="collapseCatProductTop<?= $category['alias']; ?>" data-toggle="dropdown">
                <?= $category['title']; ?>
            </button>
            <div class="card catalog-top-item dropdown-menu">
                <div class="row no-gutters">
                    <div class="col-6">
                        <div class="card-body">
                            <div class="list-group">
                                <a class="list-group-item js-link-true2" href="<?= \ishop\Link::getLinkProduct($category, 0, 'category'); ?>">Все <?= mb_strtolower($category['title']); ?></a>
                                <?= $this->getMenuHtml($category['childs'], 'groups'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <?php $img = $path_img . $category['img']; ?>
                        <?php if ($webp_on) : ?>
                            <picture>
                                <source srcset="<?= imgSrcToWebP($img) ?>" type="image/webp">
                                <source srcset="<?= $img ?>" type="<?= getTypeImg($img) ?>">
                                <img class="card-img img-thumbnail" src="<?= $img ?>" alt="<?= $category['title']; ?>">
                            </picture>
                        <?php else : ?>
                            <img src="<?= $img ?>" class="card-img img-thumbnail" alt="<?= $category['title']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </li>
    <?php endif; ?>
<?php endif; ?>