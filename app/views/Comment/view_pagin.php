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
<?php endif; ?>