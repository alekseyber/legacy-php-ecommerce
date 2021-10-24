<div class="tags-l-wr">
    <span class="material-icons">local_offer</span>
    <span class="sm-ttt">Облако тегов:</span>
    <?php foreach ($valTagsAttrs as $item) : ?>
        <a class="js-link-true" href="<?= $item['link']; ?>"><?= $item['ankor']; ?></a>
    <?php endforeach; ?>
</div>
