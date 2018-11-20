<?php defined('_WORK') or die('Restricted access'); ?>
<?php $path_img = \ishop\App::$app->getProperty('img_category_path'); ?>
<?php if ($category['parent_id'] > 0): ?>
    <li class='nav-item dropdown'>
        <?php $title = mb_strtolower($category['title']); ?>
        <?php if (!isset($category['childs'])): ?>
        <li class="nav-item"><a class = "nav-link js-link-true" href="<?= \ishop\Link::getLinkProduct($category, 0, 'category'); ?>" title="Выбрать: <?= $title; ?>"><?= $category['title']; ?></a></li>
    <?php endif; ?>
    <?php if (isset($category['childs'])): ?>
        <a class="nav-link dropdown-toggle" href="#" id="#collapseCatProductTop<?= $category['alias']; ?>" role="button" data-toggle="dropdown" aria-expanded="false">
            <?= $category['title']; ?>
        </a>
        <div class="dropdown-menu"  aria-labelledby="collapseCatProductTop<?= $category['alias']; ?>">    
            <div class="container">                                            
                <div class="menu-dropdown-content float-left">
                    <ul class="item-child">
                        <li class="nav-item">
                            <a class = "nav-link js-link-true" href="<?= \ishop\Link::getLinkProduct($category, 0, 'category'); ?>" title="Выбрать: все <?= $title; ?>">Все <?= $title; ?></a> 
                        </li>
                        <?= $this->getMenuHtml($category['childs']); ?>                    
                    </ul>
                </div>
                <div class="menu-dropdown-img float-right d-none d-sm-block">
                    <a class = "js-link-true" href="<?= \ishop\Link::getLinkProduct($category, 0, 'category'); ?>">
                        <span class="item-img"> 
                            <img class="img-thumbnail" src="<?= $path_img . $category['img']; ?>" alt="<?= $category['alias']; ?>"> 
                        </span>
                        <span class="item-text-wrap"> 
                            <span class="item-text"><?= $title; ?></span>
                        </span>
                    </a>
                </div>
                <div class="clearfix"></div>
            </div><!-- container -->
            <div class="dropdown-menu-bottom"></div>
        </div><!-- dropdown-menu -->
    <?php endif; ?>
    </li>
<?php endif; ?>