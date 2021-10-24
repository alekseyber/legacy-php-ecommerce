<?php $webp_on = \ishop\App::$app->getProperty('webp_on'); ?>
<?php $p_widgets = new \app\widgets\product\Product(); ?>
<div data-selector="app-in-load-el">
    <div class="content-wr">
        <div class="container-xxl"><?= $content['topslider']; ?></div>
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
                <div class="sec-main-inf">
                    <div class="row py-1">
                        <?php foreach (\ishop\App::$app->getProperty('main_inf_b_items') as $item) : ?>
                            <div class="col-6 col-sm-4 col-xl-2 px-3 py-1 d-flex align-items-center justify-content-center">                                
                                <span class="material-icons me-2"><?= $item['icon'] ?></span>                                
                                <span> <?= $item['text'] ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div> <!-- sec-main-inf -->
            <?php endif; ?>
            <?php if ($content['productsHits']) : ?>
                <div class="sec-products sec">
                    <div class="sec-title h2">Хит продаж</div>
                    <div class="row g-2 pr-row">
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
                    <div class="sec-title h2">Новые поступления</div>
                    <div class="row g-2 pr-row">
                        <?php foreach ($content['productsNew'] as $product) : ?>
                            <div class="col-sm-6 col-lg-3 mt-1 mb-1">
                                <?= $p_widgets->toString($product); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div><!-- sec-productsa-->
            <?php endif; ?>
            <?php if ($content['contentData']['content']) : ?>
                <div class="sec-content sec card">                    
                    <div class="card-body">
                        <h1 class="display-6 text-center mb-3"><?= $content['contentData']['title']; ?></h1>
                        <?= $content['contentData']['content']; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if ($content['comments']) : ?>
                <div class="sec comment-bl">
                    <div class="sec-head">
                        <h2 class="sec-title">Отзывы покупателей</h2>
                    </div>
                    <div class="sec-body">
                        <div id="carouselComment" class="carousel dragjs slide action-car carousel-fade" data-bs-ride="carousel"  data-bs-interval="false">
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
                            <button
                                class="carousel-control-prev"
                                type="button"
                                data-bs-target="#carouselComment"
                                data-bs-slide="prev"
                                >
                                <span class="carousel-control-prev-icon"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button
                                class="carousel-control-next"
                                type="button"
                                data-bs-target="#carouselComment"
                                data-bs-slide="next"
                                >
                                <span class="carousel-control-next-icon"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <div class="comment-btn">
                            <a href="<?= \ishop\Link::getLink('comment'); ?>" class="btn btn-raised btn-secondary js-link-true2">Больше отзывов</a>
                        </div>
                    </div>
                </div><!-- sec comment-bl -->
            <?php endif; ?>
            <?= $content['valTagsHtml'] ?>
        </div>
    </div>
</div>