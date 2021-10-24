<?php $id = $name . $id; ?>
<div class="modal fade" id="myModal<?= $id; ?>" tabindex="-1" data-mode-status="divelery-inf">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModal<?= $id; ?>Label"><?= $contData['htitle']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"><?= $contentDelivery; ?></div>
            <div class="modal-footer">        
                <button type="button" class="btn btn-primary btn-raised" data-bs-dismiss="modal">Закрыть</button>
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal --> 