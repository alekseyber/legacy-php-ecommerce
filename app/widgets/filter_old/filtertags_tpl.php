<div class="sec tags-sec">
    <div class="sec-head">
        <h2 class="sec-title">Популярные темы</h2>
    </div>
    <div class="tags-l-wr">
        <span class="material-icons">local_offer</span>
        <span class="sm-ttt">Теги:</span>
        <?php foreach ($valTagsAttrs as $item) : ?>
            <a class="tags-l js-link-true" href="<?= $item['link']; ?>"><?= $item['ankor']; ?></a>
        <?php endforeach; ?>
    </div>
</div><!-- sec tags-sec -->