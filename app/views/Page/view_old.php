<div class="main">
    <div class="container-fluid">
        <div class="main-title">
            <header>
                <h1><?= $contData['htitle']; ?></h1>
            </header>

            <div class="promo-copy">
                <?=
                $contData['promo'];
                ?>
            </div>
            <nav class="refinement-nav">
                <div>
                    <button class="btn btn-transparent js-panel-btn panel-btn" data-panel="secondary">
                        Перейти к выбору товаров
                    </button> 
                </div>
            </nav> <!-- search-rezult-wrap -->
        </div><!-- main-title -->
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a itemprop="item" class='js-link-true2' href="/"><span itemprop="name">Главная</span></a>
                    <meta itemprop="position" content="1"></li>
                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">                    
                    <a itemprop="item" class="active-breadcrumbs" href="<?= \ishop\Link::getLink($contData['link']); ?>"><span itemprop="name"><?= $contData['htitle']; ?></span></a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">Назад</a>
        </nav><!-- nav-breadcrumb -->
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
                    <div class="content-wrap-page">
                        <?php if ($contData['content']): ?>
                            <article class="seo-cont-slot-article">
                                <?= $contData['content']; ?>
                            </article>
                        <?php endif; ?>
                    </div><!-- seo-cont-slot cont-slot --> 
                </div> <!-- search-result-content view-grid -->                
                <div class="clearfix"></div>
            </div> <!-- col-md-9 -->
        </div> <!-- row primary  -->
    </div> <!-- container-fluid -->
</div> <!-- main -->