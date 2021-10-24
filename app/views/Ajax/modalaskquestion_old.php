<?php $id = $name . $id; ?>
<?php $reCAPTCHA = \ishop\App::$app->getProperty('reCAPTCHA'); ?>
<div class="modal fade return-phone" id="myModal<?= $id; ?>" tabindex="-1" data-mode-status="qorder">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase"><?= $data['title']; ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="askquestion-message-wrap">
                    <div class="alert alert-danger">
                        <span class="error"></span>
                    </div>
                    <div class="alert alert-success">
                        <span class="success"></span>
                    </div>
                    <div class="alert alert-light">
                        <span class="before">Данные отправляются...</span>
                    </div>
                </div>
                <form class="needs-validation" method="post" id="askquestionform" data-g-recaptcha="true" method="post" action="<?= \ishop\Link::getLink('askquestion'); ?>" novalidate>
                    <div class="input-group form-group">
                        <div class="input-group-prepend mr-2">
                            <span class="input-group-text"><i class="material-icons">person_pin</i></span>
                        </div>
                        <input type="text" name="name" class="form-control" placeholder="Имя*" value="<?= isset($_SESSION['form_data']['name']) ? $_SESSION['form_data']['name'] : '' ?>" required>
                        <div class="invalid-feedback">
                            Введите, пожалуйста, имя!
                        </div>
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend mr-2">
                            <span class="input-group-text"><i class="material-icons">phone_iphone</i></span>
                        </div>
                        <input type="tel" name="phone" class="form-control" placeholder="Телефон*" value="<?= isset($_SESSION['form_data']['phone']) ? $_SESSION['form_data']['phone'] : '' ?>" pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$" required>
                        <div class="invalid-feedback">
                            Введите, пожалуйста, телефон!
                        </div>
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text mr-2"><i class="material-icons">mode_comment</i></span>
                        </div>
                        <textarea name="note" class="form-control" placeholder="Сообщение" rows="5"><?= isset($_SESSION['form_data']['note']) ? h($_SESSION['form_data']['note']) : '' ?></textarea>
                    </div>
                    <div class="order-action">
                        <?php $data = $data['product'] ?? ''; ?>
                        <?= $data ?>
                        <input type="hidden" name="product" value="<?= $data; ?>">
                        <button class="g-recaptcha d-none" data-sitekey="<?= $reCAPTCHA['key']; ?>" data-callback="checedcapt"></button>
                        <button type="button" class="btn btn-raised btn-transparent" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary btn-raised disabled">Отправить</button>
                    </div>
                </form>
            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

<script>
            // setInputMask();
    var forms = document.getElementsByClassName('needs-validation');  
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    function checedcapt(token) {
        $('#askquestionsub').removeClass('disabled');       
    }

    function onloadCallback() {
        grecaptcha.execute();
    }
    $(document).on("submit", '#askquestionform', function(event) {
        var form = $(this);
        event.preventDefault();

        var allertLEl = $('.askquestion-message-wrap .alert-light');
        $.ajax({
            url: '/askquestion',
            dataType: 'json',
            data: form.serialize(),
            type: 'POST',
            beforeSend: function() {
                $('#askquestionform').delay(200).fadeOut('slow', function() {
                    allertLEl.fadeToggle('slow');
                });
            },
            success: function(res) {
                // console.log(res);
                if (res.success === true) {
                    allertLEl.delay(200).fadeToggle('slow', function() {
                        $('.askquestion-message-wrap .alert-success > span').html(res.messege);
                        $('.askquestion-message-wrap .alert-danger').fadeOut('slow');
                        $('.askquestion-message-wrap .alert-success').fadeIn('slow');
                    });
                } else {
                    grecaptcha.reset();
                    allertLEl.delay(200).fadeToggle('slow', function() {
                        $('#askquestionform').fadeIn(100, function() {
                            if (res.messege) {
                                $('.askquestion-message-wrap .alert-danger > span').html(res.messege);
                                $('.askquestion-message-wrap .alert-danger').fadeIn('slow');
                            }
                        });
                    });
                }

            },
            error: function(e) {
                // console.log(e);
                grecaptcha.reset();
                allertLEl.delay(200).fadeToggle('slow', function() {
                    $('#askquestionform').fadeIn(100, function() {
                        $('.askquestion-message-wrap .alert-danger > span').html('Ошибка соединения, попробуйте позже');
                        $('.askquestion-message-wrap .alert-danger').fadeIn('slow');
                    });
                });
            }
        });
        return false
    });
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>