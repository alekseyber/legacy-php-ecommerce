<?php $curr = $this->options['curr']; ?>
<?php $main = $this->options['main']; ?>
<?php $webp_on = $this->options['webp_on']; ?>
<?php $product_tr_on = \ishop\App::$app->getProperty('product_tr_on'); ?>
<?php $product_tr_items = \ishop\App::$app->getProperty('product_tr_items'); ?>
<?php $slider_id = $main ? 'carouselProductModal' : 'carouselProductMain'; ?>
<div class="row" data-selector="app-in-product-m"  data-mode-status="productmain">
    <div class="col-lg-6">
        <div class="h-100 card">
            <div id="<?= $slider_id; ?>" class="carousel slide product-carousel" data-bs-ride="carousel" data-bs-interval="false">
                <div class="carousel-inner" data-selector="pr-im-gal">
                    <?php
                    if ($product['gallery']) :
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
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    <?php endif; ?>
                </div>
                <button class="carousel-control-prev" data-bs-target="#<?= $slider_id; ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" data-bs-target="#<?= $slider_id; ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                    <span class="visually-hidden">Next</span>
                </button>
                <div class="carousel-indicators">
                    <?php
                    if ($product['gallery']) :
                        $i = 1;
                        foreach ($product['gallery'] as $v) :
                            $class = ($i === 1) ? ' class="active"' : '';
                            ?>
                            <div data-bs-target="#<?= $slider_id; ?>" data-bs-slide-to="<?= $i - 1; ?>" <?= $class; ?>>
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
    <div class="col-lg-6 mt-lg-0 mt-3">
        <div class="card h-100">
            <div class="card-body">
                <?php $title = $product['gender'] ? $product['gender'] . ' ' . $product['title'] : $product['title']; ?>
                <?php if ($main) : ?>
                    <h1 class="fw-bold card-title mb-2" itemprop="name"><?= $title; ?></h1>
                    <meta itemprop="url" content="<?= $product['link']; ?>">
                <?php else : ?>
                    <a class="js-link-true2" data-selector="qpr-close" href="<?= $product['link']; ?>" itemprop="url">
                        <h1 class="fw-bold card-title mb-2" itemprop="name"><?= $title; ?></h1>
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
                <div class="sec-logo">
                    <?php if ($product['brand_title'] && $product['brand_img']) : ?>
                        <div class="product-logo">
                            <meta itemprop="brand" content="<?= $product['brand_title']; ?>">
                            <img alt="<?= $product['brand_title']; ?>" src="<?= $product['brand_img']; ?>">
                        </div>
                    <?php endif; ?>
                    <div class="share-button">
                        <div class="btn-group">
                            <button class="btn bmd-btn-icon dropdown-toggle" type="button" id="share-btn<?= $main; ?>" data-bs-toggle="dropdown" title="Поделиться этим продуктом">
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
                    <div class="sec-pr">Модель: <span class="fw-bold" itemprop="sku"><?= $product['sku']; ?></span></div>
                <?php endif; ?>
                <?php if ($product['product_model'] != '3' && $product['product_model'] != '4') : ?>
                    <div class="sec-pr">
                        <div class="fw-bold">Выберите цвет:</div>
                        <div>Текущий цвет: <span class="fw-bold"><?= mb_strtoupper($product['color_title']); ?></span></div>
                        <?php $dataProductcard = $main ? 'mainproduct' : 'product_modal'; ?>
                        <div class="colors-pr mt-1">
                            <?php foreach ($product['modification'] as $k => $v) : ?>
                                <?php $active = ($product['color_default'] == $k) ? ' active' : ''; ?>
                                <div data-title="<?= mb_strtoupper($v['title']); ?>" data-toggleuser="tooltip">
                                    <a class="js-sw-clr-pr itemclr btn bmd-btn-fab<?= $active; ?>" href="<?= $v['linkColor']; ?>" style="background-color: <?= $v['colorkey']; ?>;" data-productcard="<?= $dataProductcard ?>"></a>
                                </div>
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
                            <div class="fw-bold">Выберите размер:</div>
                            <div class="sizes-selector mt-1">
                                <?php foreach ($product['sizesList'] as $k => $v) : ?>
                                    <?php $active = $v['selected'] ? ' active' : ''; ?>
                                    <button 
                                        type="button" 
                                        class="btn btn-raised<?= $active; ?>" 
                                        data-pid="<?= $v['pid']; ?>"
                                        data-selector="sizes-sel-btn"
                                        >
                                            <?= $v['title']; ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="pr-btn-actions-sec">
                        <button type="submit" class="btn btn-primary btn-raised">
                            <span class="material-icons me-1">add_shopping_cart</span>
                            <span><?= $product['b_cart_text']; ?></span>
                        </button>
                        <?php if (!$product['edit']) : ?>
                            <button type="button" data-selector="qorder-btn" class="btn btn-success btn-raised">
                                Быстрый заказ
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
                <?php if (isset($product['ajax_link'])) : ?>
                    <div class="pr-btn-actions-sec">
                        <?php foreach ($product['ajax_link'] as $item) : ?>
						<?php if($item): ?>
						<button type="button" class="btn btn-raised btn-info js-link-modal" data-href="#myModal<?= $item['nameid']; ?>" data-dann="<?= $item['dann']; ?>"><?= $item['link_title']; ?></button>
						<?php endif; ?>						
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($product_tr_on) : ?>                        
                    <div class="row no-gutters mt-3">
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>