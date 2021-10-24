<?php $id = $name . $id; ?>
<div class="modal fade" id="myModal<?= $id; ?>" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= $data['title']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $data['content']; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-raised" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

