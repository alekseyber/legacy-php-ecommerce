<div class="modal" id="addedcart" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавлено в корзину</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" data-selector="added-conteiner"></div>
            <div class="row m-2">
                <div class="pe-2 ps-2 pt-1 col-sm-6">
                    <button type="button" class="btn btn-raised btn-secondary w-100" data-bs-dismiss="modal">Продолжить покупки</button>
                </div>
                <div class="pe-2 ps-2 pt-1 col-sm-6">
                    <a class="btn btn-raised btn-primary w-100 js-link-true2" href="<?= \ishop\Link::getLink('cart/view'); ?>" data-selector="modal-close">Оформить заказ</a>  
                </div>
            </div>
        </div>
    </div>
</div>

