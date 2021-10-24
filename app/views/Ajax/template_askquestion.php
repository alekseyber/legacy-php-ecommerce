<div class="modal fade" id="askquestion" tabindex="-1" data-mode-status="jsform">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase" data-selector="modaltitle">Задать вопрос</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div data-selector="js-form" data-name="askquestion" data-re="true">
                    <form class="needs-validation" method="post" action="<?= \ishop\Link::getLink('askquestion'); ?>" novalidate>
                        <div class="form-group">
                            <label for="askquestion-name" class="bmd-label-floating">Имя*</label>
                            <input 
                                type="text" 
                                id="askquestion-name"
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
                            <label for="askquestion-phone" class="bmd-label-floating">Телефон*</label>
                            <input 
                                type="tel" 
                                name="phone"                            
                                id="askquestion-phone"
                                data-selector="textinput"
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
                            <label for="askquestion-note" class="bmd-label-floating">Сообщение</label>
                            <textarea
                                name="note"
                                id="askquestion-note"
                                data-selector="textareainput"
                                class="form-control"
                                rows="5"
                                ><?= isset($_SESSION['form_data']['note']) ? h($_SESSION['form_data']['note']) : '' ?></textarea>
                        </div>
                        <div class="rebranding">
                            <span>Этот сайт защищен reCAPTCHA и Google</span>
                            <a target="_blank" href="https://policies.google.com/privacy">Политика конфиденциальности</a> и
                            <a target="_blank" href="https://policies.google.com/terms">Условия использования</a>.
                        </div>
                        <div class="order-action">                        
                            <button type="button" class="btn btn-raised btn-transparent" data-bs-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary btn-raised ms-2">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
