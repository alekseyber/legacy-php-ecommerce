<div class="content-wr" data-selector="app-in-load-el">
    <div class="container">
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a class="js-link-true2" itemprop="item" href="/">
                        <span itemprop="name" class="material-icons">home</span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a class="disabled" itemprop="item" href="<?= \ishop\Link::getLink($contData['link']); ?>">
                        <span itemprop="name"><?= $contData['htitle']; ?></span></a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-products sec">
            <header class='sec-products-header'>
                <h1 class="sec-title"><?= $contData['htitle']; ?></h1>
                <div class="btn-group sort-panel">
                    <button class="btn btn-raised btn-secondary me-3 btn-cat-menu" type="button" data-toggle="drawer" data-target="#dw-p1">
                        Меню
                    </button>
                </div>
            </header>
            <?php if ($contData['content']) : ?>
                <div class="sec-content sec card">
                    <div class="card-body">
                        <?= $contData['content']; ?>
                    </div>
                </div>
        </div><!-- sec-products sec-->
    <?php endif; ?>
    </div><!--  container-->
</div><!--  content-wr-->