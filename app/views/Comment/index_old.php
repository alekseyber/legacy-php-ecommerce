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
                    <a class="disabled" itemprop="item" href="<?= \ishop\Link::getLink('comment'); ?>">
                        <span itemprop="name">Отзывы</span></a>
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
            <div class="comment-wrap">
                <div class="text-center add-comment-block">
                    <button class="btn btn-raised btn-secondary js-link-ajblock" type="button" data-toggle="collapse" data-target="#collapseAddComment" data-dann="<?= $contData['data_link_add_comment']; ?>" aria-expanded="false" aria-controls="collapseAddComment">
                        Оставить отзыв
                    </button>
                </div>
                <div class="collapse comment-edit-wrap" id="collapseAddComment">
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-info">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="comment-row next-pagin-bl">
                    <?php if ($comment) : ?>
                        <?php foreach ($comment as $item) : ?>
                            <div class="card item-comment">
                                <div class="card-body">
                                    <div class="card-title item-comment-title">
                                        <?php if ($item['social']) : ?>
                                            <?php $soclink = uid_social_link($item['author_uid'], $item['author_provider']) ?>
                                            <div class="avatar">
                                                <a href="<?= $soclink; ?>" target="_blank" rel="nofollow">
                                                    <img class="comment-item-avatar rounded-circle" src="<?= $item['author_avatar']; ?>" alt="<?= $item['author_name']; ?>">
                                                </a>
                                            </div>
                                            <div class="name-author">
                                                <a href="<?= $soclink; ?>" target="_blank" rel="nofollow"><?= $item['author_name']; ?></a>
                                            </div>
                                        <?php else : ?>
                                            <div class="avatar">
                                                <div class="avatar-comment-icon">
                                                    <span class="material-icons">person</span>
                                                </div>
                                            </div>
                                            <div class="name-author"><?= $item['author_name']; ?></div>
                                        <?php endif; ?>
                                        <div class="data-comment">
                                            <div class="infoDigits comment-item__time"><?= dattimereturnexpl($item['date']); ?></div>
                                        </div>
                                    </div>
                                    <p class="card-text comment-text"><?= $item['commen_text']; ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?= $paginationstr; ?>
                    <?php else : ?>
                        <p class="text-center h4">Отзывы отсутствуют</p>
                    <?php endif; ?>
                </div>
                <!--comment-row   -->
            </div>
            <!--comment-wrap   -->
        </div><!-- sec-page sec-->
    </div><!--  container-->
</div><!--  content-wr-->
<div id="vk_api_transport"></div>