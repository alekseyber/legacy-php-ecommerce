<div class="modal" id="qorder" tabindex="-1" data-mode-status="jsform">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Быстрый заказ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" data-selector="js-form" data-name="qorder">
                <form 
                    class="needs-validation"
                    method="post"
                    action="<?= \ishop\Link::getLink('cart/checkout'); ?>"
                    novalidate
                    data-qorder="true"
                    >
                    <input type="hidden" name="qorder" value="true">
                    <div class="form-group">
                        <label for="qorder-name" class="bmd-label-floating">Имя*</label>
                        <input 
                            type="text" 
                            id="qorder-name"
                            name="name"
                            class="form-control"
                            value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '' ?>" 
                            required
                            data-selector="textinput"
                            >
                        <div class="invalid-feedback">
                            Введите, пожалуйста, имя!
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="qorder-phone" class="bmd-label-floating">Телефон*</label>
                        <input 
                            type="tel" 
                            name="phone"
                            data-selector="textinput"
                            id="qorder-phone"                            
                            class="form-control"
                            value="<?= isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : '' ?>"
                            pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$"
                            required
                            inputmode="tel"
                            >
                        <div class="invalid-feedback">
                            Введите, пожалуйста, телефон!
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="qorder-note" class="bmd-label-floating">Комментарии к заказу</label>
                        <textarea
                            name="note"
                            id="qorder-note"
                            data-selector="textareainput"
                            class="form-control"
                            rows="5"
                            ><?= isset($_SESSION['form_data']['note']) ? h($_SESSION['form_data']['note']) : '' ?></textarea>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" name="i-agree" type="checkbox" checked value="1" id="q-order-i-agree" required>
                        <label class="form-check-label small" for="q-order-i-agree">
                            <span> Я согласен с условиями </span>
                            <span>
                                <a href="<?= \ishop\Link::getLink('polzovatelskoe-soglashenie'); ?>" target="_blank">публичной оферты и обработкой моих персональных данных в
                                    порядке, предусмотренном публичной офертой</a>
                            </span>
                        </label>
                        <div class="invalid-feedback">
                            Поставьте, пожалуйста, галочку!
                        </div>
                    </div>
                    <div class="order-action">
                        <button type="submit" class="btn btn-primary btn-raised">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 

