<div class="content-wr" data-selector="app-in-load-el" data-mode-status="divelery-inf">
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
                    <a class="disabled" itemprop="item" href="<?= \ishop\Link::getLink('delivery'); ?>">
                        <span itemprop="name">Доставка</span></a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-page sec">
            <header class='sec-page-header'>
                <h1 class="sec-title"><?= $contData['htitle']; ?></h1>
            </header>
            <?= $contentDelivery; ?>
        </div><!-- sec-page sec-->
    </div><!--  container-->
</div><!--  content-wr-->