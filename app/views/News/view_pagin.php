<?php if ($news) : ?>
<?php $img_news_path = \ishop\App::$app->getProperty('img_news_path'); ?>
<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
    <div data-selector="app-in-load-el">
        <div class="row news-row">
            <?php foreach ($news as $item) : ?>
                <?php $img = $img_news_path . $item['img']; ?>
                <div class="col-sm-6 col-lg-3">
                    <div class="card news-item">
                        <a class="js-link-true2" href="<?= \ishop\Link::getLink($item['link']); ?>">
                            <?php if ($webp_on) : ?>
                                <picture>
                                    <source srcset="<?= imgSrcToWebP($img) ?>" type="image/webp">
                                    <source srcset="<?= $img ?>" type="<?= getTypeImg($img) ?>">
                                    <img class="card-img-top" src="<?= $img ?>" alt="<?= $item['title'] ?>">
                                </picture>
                            <?php else : ?>
                                <img src="<?= $img ?>" class="card-img-top" alt="<?= $item['title'] ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="news-title"><?= $item['title']; ?></div>
                            </div>
                        </a>
                    </div>
                </div><!-- col-->
            <?php endforeach; ?>
        </div><!-- row news-row-->
        <?= $paginationstr; ?>
    </div>
<?php endif; ?>