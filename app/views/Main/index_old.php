<div class="main-page-top-wrap">
    <?= $topslider; ?>
    <div class="razdpolvozr d-none d-sm-block">
        <div class="razdpolvozr-cont container">
            <nav>
                <div class="row">
                    <div class="col-sm-4">
                        <a class="select-sex-item js-link-true2" href="<?= \ishop\Link::getLink('women'); ?>">
                            <div>Для</div>
                            <div>женщин</div>                           
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="select-sex-item js-link-true2" href="<?= \ishop\Link::getLink('men'); ?>">
                            <div>Для</div>
                            <div>мужчин</div>
                        </a>
                    </div>
                    <div class="col-sm-4">
                        <a class="select-sex-item js-link-true2" href="<?= \ishop\Link::getLink('kids'); ?>">
                            <div>Для</div>
                            <div>детей</div>
                        </a>
                    </div>
                </div> <!-- row -->
            </nav>
        </div> <!-- razdpolvozr-cont container -->
    </div> <!-- razdpolvozr -->
</div><!-- main-page-top-wrap -->
<div class="main">
    <div class="container-fluid">
        <div class="main-title">
            <header>
                <h1><?= $content['title']; ?></h1>
            </header>

            <div class="promo-copy">
                <?= $content['promo']; ?>
            </div>
            <nav class="refinement-nav">
                <div>
                    <button class="btn btn-transparent js-panel-btn panel-btn" data-panel="secondary">
                        Уточнить фильтр
                    </button> 
                </div>
            </nav> <!-- search-rezult-wrap -->
        </div><!-- main-title -->
        <div class="row primary">
            <div class="col-lg-3">
                <div class="js-off-canvas secondary">
                    <aside class="js-panel form-r">
                        <header>
                            <h2 class="refinement-header">Уточните выбор:</h2>
                            <button class="js-panel-close-btn">Close</button>
                        </header>
                        <div class="refinements">
                            <section class="catalog-nav">
                                <div class="refinement">
                                    <h3 class="panel-title">
                                        <a data-toggle="collapse" href="#collapseCatProduct" aria-expanded="true" aria-controls="collapseCatProduct">
                                            Категория продукта
                                        </a>
                                    </h3>
                                    <nav id="collapseCatProduct" class="panel-collapse collapse in show">
                                        <?= $menuCatalog; ?>
                                    </nav>
                                </div> <!-- refinement -->
                            </section><!-- catalog-nav -->                            
                            <?= $filter; ?>                            
                        </div><!-- refinements -->   
                    </aside> <!-- js-panel form-r -->
                </div>   <!-- js-off-canvas -->
            </div>   <!--  col-md-3 -->
            <div class="col-lg-9 right-block">
                <div class="search-result-content view-grid">
                    <?php if ($products): ?>
                        <?php $p_widgets = new \app\widgets\product\Product(); ?>
                        <div class="products-row row">
                            <?php foreach ($products as $product): ?>
                                <div class="col-6 col-md-4 col-lg-3 products-col-9">
                                    <?= $p_widgets->toString($product); ?>
                                </div> <!-- col-6 col-md-4 col-lg-3 products-col -->
                            <?php endforeach; ?>
                        </div> <!-- products-row row -->
                    <?php endif; ?>
                </div> <!-- search-result-content view-grid -->
                <div class="clearfix"></div>
            </div> <!-- col-md-9 -->
        </div> <!-- row primary  -->
        <div class="seo-cont-slot cont-slot">
            <article class="seo-cont-slot-article">
                <?= $content['content']; ?>
            </article>
        </div><!-- seo-cont-slot cont-slot --> 
    </div> <!-- container-fluid -->
</div> <!-- main -->