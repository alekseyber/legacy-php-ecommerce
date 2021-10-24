<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<div id="carousetopslides" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <?php foreach ($slider as $k => $v): ?>
            <?php echo $k; ?>
            <?php $active = $k == 1 ? ' active' : ''; ?>
            <div class="carousel-item<?= $active; ?>">
                <?php if ($v['href']): ?>
                    <a href="<?= $v['href']; ?>" class="js-link-true2 carousel-item_link">
                    <?php endif; ?>
                    <div class="carousetopslides__item_filter">
                        <div class="carousetopslides__item_content">
                            <div class="display-5"><?= $v['name']; ?></div>
                            <?= $v['content']; ?>                                
                        </div>                      
                    </div>
                    <?php if ($webp_on) : ?>
                        <picture>
                            <source srcset="<?= imgSrcToWebP($v['img']) ?>" type="image/webp">
                            <source srcset="<?= $v['img']; ?>" type="<?= getTypeImg($v['img']) ?>">
                            <img src="<?= $v['img']; ?>" class="d-block w-100" alt="<?= $v['name']; ?>">
                        </picture>
                    <?php else : ?>
                        <img src="<?= $v['img']; ?>" class="d-block w-100" alt="<?= $v['name']; ?>">
                    <?php endif; ?>  
                    <?php if ($v['href']): ?>
                    </a>
                <?php endif; ?>
            </div>   
        <?php endforeach; ?>
    </div>
</div>