<div class="content-wr">
    <div class="container">
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <?= $breadcrumbs; ?>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons mr-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-products sec">
            <header class='sec-products-header'>
                <h1 class="sec-title"><?= $contData['htitle']; ?></h1>
                <div class="btn-group sort-panel">
                    <button class="btn btn-raised btn-secondary mr-2 btn-cat-menu" type="button" data-toggle="drawer" data-target="#dw-p1">
                        Меню
                    </button>
                    <?= $sort; ?>
                </div>
            </header>
            <div class="pagin-cont"><?= $paginContent ?>
                <?php if ($products) : ?>
                    <?php $p_widgets = new \app\widgets\product\Product(); ?>
                    <div class="row pr-row">
                        <?php foreach ($products as $product) : ?>
                            <div class="col-sm-6 col-lg-3 mt-1 mb-1">
                                <?= $p_widgets->toString($product); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?= $paginationstr; ?>
                <?php endif; ?>
            </div><!-- pagin-cont-->
        </div><!-- sec-products sec-->
        <?php if ($contData['content']) : ?>
            <div class="sec-content sec card">
                <div class="card-body">
                    <?= $contData['content']; ?>
                </div>
            </div>
        <?php endif; ?>
    </div><!--  container-->
</div><!--  content-wr-->