<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<?php $p_widgets = new \app\widgets\product\Product(); ?>
<div class="top-main-wrap">
    <div class="cover">
        <div class="cover-carrier" style="background-image: url(/images/crosbgr.jpg)">
        </div>
        <div class="cover-filter"></div>
    </div>
    <div class="top-main-container">
        <div class="container top-main-content">
            <div class="top-main-content-wrap">
                <div class="top-main-name"><?= \ishop\App::$app->getProperty('shop_name'); ?></div>
                <h1 class="top-main-title"><?= $content['contentData']['title']; ?></h1>
                <div class="promo">
                    <?= $content['contentData']['promo']; ?>
                </div>
                <div class="to-bottom"></div>
            </div>
        </div>
    </div>
</div> <!-- top-main-wrap -->
<div class="content-wr">
    <div class="container">
        <div class="sec-main-cat sec" id="sec_first">
            <div class="row no-gutters">
                <?php foreach ($content['catsTop'] as $cat) : ?>
                    <div class="col-4">
                        <a class="js-link-true2 img-cat-l" href="<?= \ishop\Link::getLinkProduct($cat, 0, 'category') ?>">
                            <div class="media-cat">
                                <?php if ($webp_on) : ?>
                                    <picture>
                                        <source srcset="<?= imgSrcToWebP($content['catPathImg'] . $cat['img']) ?>" type="image/webp">
                                        <source srcset="<?= $content['catPathImg'] . $cat['img'] ?>" type="<?= getTypeImg($cat['img']) ?>">
                                        <img class="card-img" src="<?= $content['catPathImg'] . $cat['img'] ?>" alt="<?= $cat['title'] ?>">
                                    </picture>
                                <?php else : ?>
                                    <img class="card-img" src="<?= $content['catPathImg'] . $cat['img'] ?>" alt="<?= $cat['title'] ?>">
                                <?php endif; ?>
                            </div>
                            <div class="img-cat-title"><?= $cat['title'] ?></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div> <!-- sec-main-cat -->
        <?php if (\ishop\App::$app->getProperty('main_inf_b_on')) : ?>
            <div class="sec-main-inf sec">
                <div class="row">
                    <?php foreach (\ishop\App::$app->getProperty('main_inf_b_items') as $item) : ?>
                        <div class="col-6 col-sm-4 col-xl-2 d-flex">
                            <div class="icon text-center">
                                <span class="material-icons"><?= $item['icon'] ?></span>
                            </div>
                            <div class="text"><?= $item['text'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div> <!-- sec-main-inf -->
        <?php endif; ?>
        <?php if ($content['productsHits']) : ?>
            <div class="sec-products sec">
                <h2 class="sec-title">Хит продаж</h2>
                <div class="row pr-row">
                    <?php foreach ($content['productsHits'] as $product) : ?>
                        <div class="col-sm-6 col-lg-3 mt-1 mb-1">
                            <?= $p_widgets->toString($product); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div><!-- sec-products-->
        <?php endif; ?>
        <?php if (\ishop\App::$app->getProperty('main_sale_b_on')) : ?>
            <?php $main_sale_b_data = \ishop\App::$app->getProperty('main_sale_b_data') ?>
            <div class="sec sec-bann" style="background-color:<?= $main_sale_b_data['background-color']; ?>;color:<?= $main_sale_b_data['color']; ?>">
                <?php foreach ($main_sale_b_data['lines'] as $line) : ?>
                    <div><?= $line; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($content['productsNew']) : ?>
            <div class="sec-products sec">
                <h2 class="sec-title">Новые поступления</h2>
                <div class="row pr-row">
                    <?php foreach ($content['productsNew'] as $product) : ?>
                        <div class="col-sm-6 col-lg-3 mt-1 mb-1">
                            <?= $p_widgets->toString($product); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div><!-- sec-productsa-->
        <?php endif; ?>
        <?php if ($content['comments']) : ?>
            <div class="sec comment-bl">
                <div class="sec-head">
                    <h2 class="sec-title">Отзывы покупателей</h2>
                </div>
                <div class="sec-body">
                    <div id="carouselComment" class="carousel dragjs slide action-car carousel-fade" data-bs-ride="carousel"  data-bs-interval="false">

                        data-bs-interval="false"

                        <div class="carousel-indicators">
                             <?php foreach ($content['comments'] as $i => $comment) : ?>
                            <button
                                type="button"
                                data-bs-target="#carouselComment"
                                data-bs-slide-to="<?= $i; ?>"
                                <?= $i === 0 ? ' class="active"' : ''; ?>
                                aria-current="true"                                
                                ></button>
                            <?php endforeach; ?>
                        </div>

                        <ol class="carousel-indicators">
                            <?php foreach ($content['comments'] as $i => $comment) : ?>
                                <li data-target="#carouselComment" data-slide-to="<?= $i; ?>" <?= $i === 0 ? 'class="active"' : ''; ?>></li>
                            <?php endforeach; ?>
                        </ol>
                        <div class="carousel-inner">
                            <?php foreach ($content['comments'] as $i => $comment) : ?>
                                <div class="carousel-item<?= $i === 0 ? ' active' : ''; ?>">
                                    <div class="comment-body">
                                        <div class="avatar">
                                            <?php if ($comment['social']) : ?>
                                                <img src="<?= $comment['author_avatar']; ?>" alt="<?= $comment['author_name']; ?>" class="rounded-circle av-img">
                                            <?php else : ?>
                                                <div class="avatar-comment-icon">
                                                    <span class="material-icons">person</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="comment-text"><?= $comment['commen_text']; ?></div>
                                        <div class="comment-avtor"><?= $comment['author_name']; ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselComment" data-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselComment" data-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <div class="comment-btn">
                        <a href="<?= \ishop\Link::getLink('comment'); ?>" class="btn btn-raised btn-secondary js-link-true2">Больше отзывов</a>
                    </div>
                </div>
            </div><!-- sec comment-bl -->
        <?php endif; ?>
        <?= $content['valTagsHtml'] ?>
    </div><!--  container-->
</div><!--  content-wr-->