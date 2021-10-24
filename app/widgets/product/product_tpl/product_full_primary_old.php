<?php $curr = $this->options['curr']; ?>
<?php $main = $this->options['main']; ?>
<?php $webp_on = $this->options['webp_on']; ?>
<?php $product_tr_on = \ishop\App::$app->getProperty('product_tr_on'); ?>
<?php $product_tr_items = \ishop\App::$app->getProperty('product_tr_items'); ?>
<?php $slider_id = $main ? 'carouselProductModal' : 'carouselProductMain'; ?>
<div class="product-main">
    <div class="row no-gutters">
        <div class="col-lg-6">
            <div class="product-img-container">
                <div id="<?= $slider_id; ?>" class="carousel slide product-carousel" data-ride="carousel" data-interval="false">
                    <div class="carousel-inner pr-image-gallery">
                        <?php if ($product['gallery']) :
                            $i = 1;
                            $countimg = count($product['gallery']);
                            foreach ($product['gallery'] as $v) :
                                $active = ($i === 1) ? ' active' : '';
                        ?>
                                <div class="carousel-item<?= $active; ?>" data-thumb="<?= $v['img_thumb']; ?>" data-src="<?= $v['img_main']; ?>" data-srcset="<?= $v['srcset']; ?>" data-sizes="<?= $v['srcsetsizes']; ?>">
                                    <?php if ($webp_on) : ?>
                                        <picture>
                                            <source srcset="<?= imgSrcToWebP($v['srcset']) ?>" type="image/webp" sizes="<?= $v['srcsetsizes']; ?>">
                                            <source srcset="<?= $v['srcset']; ?>" type="<?= getTypeImg($v['img_main']) ?>" sizes="<?= $v['srcsetsizes']; ?>">
                                            <img class="img-fluid" src="<?= $v['img_main']; ?>" alt="<?= $product['title']; ?> - image <?= $i; ?> of <?= $countimg; ?>" itemprop="image">
                                        </picture>
                                    <?php else : ?>
                                        <img class="img-fluid" src="<?= $v['img_main']; ?>" alt="<?= $product['title']; ?> - image <?= $i; ?> of <?= $countimg; ?>" itemprop="image">
                                    <?php endif; ?>
                                </div>
                            <?php $i++;
                            endforeach;
                            ?>
                        <?php endif; ?>
                    </div>
                    <a class="carousel-control-prev" href="#<?= $slider_id; ?>" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#<?= $slider_id; ?>" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    <div class="carousel-indicators">
                        <?php
                        if ($product['gallery']) :
                            $i = 1;
                            foreach ($product['gallery'] as $v) :
                                $class = ($i === 1) ? ' class="active"' : '';
                        ?>
                                <div data-target="#<?= $slider_id; ?>" data-slide-to="<?= $i - 1; ?>" <?= $class; ?>>
                                    <?php if ($webp_on) : ?>
                                        <picture>
                                            <source srcset="<?= imgSrcToWebP($v['img_thumb']) ?>" type="image/webp">
                                            <source srcset="<?= $v['img_thumb']; ?>" type="<?= getTypeImg($v['img_thumb']) ?>">
                                            <img class="img-thumbnail" src="<?= $v['img_thumb']; ?>" alt="<?= $product['title']; ?> - thumbnail image <?= $i; ?> of <?= $countimg; ?>">
                                        </picture>
                                    <?php else : ?>
                                        <img class="img-thumbnail" src="<?= $v['img_thumb']; ?>" alt="<?= $product['title']; ?> - thumbnail image <?= $i; ?> of <?= $countimg; ?>">
                                    <?php endif; ?>
                                </div>
                            <?php
                                $i++;
                            endforeach;
                            ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div><!-- product-img-container -->
        </div> <!-- product-img-container -->
        <div class="col-lg-6">
            <div class="product-detail-container js-pr-cont card">
                <div class="card-body">
                    <?php $title =  $product['gender'] ? $product['gender'] . ' ' . $product['title'] : $product['title']; ?>
                    <?php if ($main) : ?>
                        <h1 class="title-main-pr card-title" itemprop="name"><?= $title; ?></h1>
                        <meta itemprop="url" content="<?= $product['link']; ?>">
                    <?php else : ?>
                        <a class="js-close-m js-link-true2" href="<?= $product['link']; ?>" itemprop="url">
                            <h1 class="title-main-pr card-title" itemprop="name"><?= $title; ?></h1>
                        </a>
                    <?php endif; ?>
                    <div class="price-product" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                        <meta itemprop="priceCurrency" content="<?= $curr['code']; ?>">
                        <link itemprop="availability" href="http://schema.org/InStock">
                        <link itemprop="itemCondition" href="http://schema.org/NewCondition">
                        <meta itemprop="price" content="<?= $product['price'] * $curr['value']; ?>">
                        <?php if ($product['old_price']) : ?>
                            <div class="old-price"><?= $curr['symbol_left']; ?><span class="js-value"><?= $product['old_price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?></div>
                        <?php endif; ?>
                        <div class="price"><?= $curr['symbol_left']; ?><span class="js-value" itemprop="price"><?= $product['price'] * $curr['value']; ?></span> <?= $curr['symbol_right']; ?></div>
                    </div>
                    <div class="sec-logo sec-pr">
                        <?php if ($product['brand_title'] && $product['brand_img']) : ?>
                            <div class="product-logo">
                                <meta itemprop="brand" content="<?= $product['brand_title']; ?>">
                                <img alt="<?= $product['brand_title']; ?>" src="<?= $product['brand_img']; ?>">
                            </div>
                        <?php endif; ?>
                        <div class="share-button">
                            <div class="btn-group">
                                <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="share-btn<?= $main; ?>" data-toggle="dropdown" title="Поделиться этим продуктом">
                                    <i class="material-icons">share</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-left">
                                    <?php foreach ($product['social_link'] as $name => $link) : ?>
                                        <button class="dropdown-item js-l-open" type="button" title="Поделиться в <?= my_mb_ucfirst($name); ?>" data-href="<?= $link['link']; ?>" data-blank="true"><span class="<?= $link['icon']; ?>_logo"></span></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($product['sku']) : ?>
                        <div class="sec-pr">
                            <span class="label">Model:</span>
                            <span class="value" itemprop="sku"><?= $product['sku']; ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($product['product_model'] != '3' && $product['product_model'] != '4') : ?>
                        <div class="sec-pr">
                            <div class="sec-pr-title">Выберите цвет:</div>
                            <div class="sec-pr-title">Текущий цвет: <?= mb_strtoupper($product['color_title']); ?></div>
                            <?php $dataProductcard = $main ? 'mainproduct' : 'product_modal'; ?>
                            <div class="colors-pr">
                                <?php foreach ($product['modification'] as $k => $v) : ?>
                                    <?php $active = ($product['color_default'] == $k) ? ' active' : ''; ?>
                                    <a class="js-sw-clr-pr itemclr btn bmd-btn-fab<?= $active; ?>" href="<?= $v['linkColor']; ?>" style="background-color: <?= $v['colorkey']; ?>;" title="<?= mb_strtoupper($v['title']); ?>" data-toggleuser="tooltip" data-placement="top" data-productcard="<?= $dataProductcard ?>"></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <form action="<?= \ishop\Link::getLink('cart/add'); ?>" method="post" class="js-add-card-form" novalidate="novalidate">
                        <?php foreach ($product['cardData'] as $k => $v) : ?>
                            <input name="cardData[<?= $k; ?>]" value="<?= $v; ?>" type="hidden">
                        <?php endforeach; ?>
                        <?php if ($product['sizesList']) : ?>
                            <div class="sec-pr">
                                <div class="sec-pr-title">Выберите размер:</div>
                                <div class="sizes-selector">
                                    <?php foreach ($product['sizesList'] as $k => $v) : ?>
                                        <?php $active = $v['selected'] ? ' active' : ''; ?>
                                        <button type="button" class="btn btn-raised<?= $active; ?>" data-pid="<?= $v['pid']; ?>"><?= $v['title']; ?></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="pr-add-to-cart-sec">
                            <button type="submit" class="btn btn-primary btn-raised btn-add-card">
                                <span class="material-icons mr-1">add_shopping_cart</span>
                                <span><?= $product['b_cart_text']; ?></span>
                            </button>
                            <?php if (!$product['edit']) : ?>
                                <button type="submit" class="btn btn-primary btn-raised btn-add-card js-q-order">
                                    Быстрый заказ
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php if (isset($product['ajax_link'])) : ?>
                        <div class="sec-pr btn-inf-gr">
                            <?php foreach ($product['ajax_link'] as $item) : ?>
                                <button type="button" class="btn btn-raised btn-info js-link-modal" data-href="#myModal<?= $item['nameid']; ?>" data-dann="<?= $item['dann']; ?>"><?= $item['link_title']; ?></button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($product_tr_on) : ?>
                        <div class="pr-tr">
                            <div class="row no-gutters">
                                <?php foreach ($product_tr_items as $item_tr) : ?>
                                    <div class="col-3 text-center p-1">
                                        <?php if ($webp_on) : ?>
                                            <picture>
                                                <source srcset="<?= imgSrcToWebP($item_tr['img']) ?>" type="image/webp">
                                                <source srcset=" <?= $item_tr['img']; ?>" type="<?= getTypeImg($item_tr['img']) ?>">
                                                <img class="img-fluid" src="<?= $item_tr['img']; ?>" alt="<?= $item_tr['text']; ?>">
                                            </picture>
                                        <?php else : ?>
                                            <img class="img-fluid" src="<?= $item_tr['img']; ?>" alt="<?= $item_tr['text']; ?>">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div><!-- product-detail-container -->
        </div><!-- col-lg-6-->
    </div><!-- row-->
</div><!-- product-main-->