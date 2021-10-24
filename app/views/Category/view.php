<div class="content-wr" data-selector="app-in-load-el">
    <div class="container">
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <?= $breadcrumbs; ?>
			</ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
			</a>
		</nav>
        <div class="sec-products sec">
            <header class='sec-products-header'>
                <h1 class="sec-title"><?= $contData['htitle']; ?></h1>
				<?php $discont = getDiscontProc(); ?>
				<?php if ($discont): ?>
				<h5 class="sec-title">Ваша персональная скидка, от указанной минимальной цены, составит - <?= $discont ?> %. Скидку Вы увидите в корзине.</h5>
				<?php endif; ?>
                <div class="btn-group sort-panel">
                    <button class="btn btn-raised btn-secondary me-3 btn-cat-menu" type="button" data-toggle="drawer" data-target="#dw-p1">
                        Меню
					</button>
                    <?= $sort; ?>
				</div>
			</header>
            <div class="pagin-cont"><?= $paginContent ?></div>
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