<?php
$vk_key = \ishop\App::$app->getProperty('vk_key');
$fb_key = \ishop\App::$app->getProperty('fb_key');
$vk_id = $vk_key['id'];
$fb_id = $fb_key['id'];
$reCAPTCHA = \ishop\App::$app->getProperty('reCAPTCHA');
?>
<div class="askquestion-message-wrap">
    <div class="alert alert-danger" role="alert">
        <span class="error"></span>
    </div>
    <div class="alert alert-success">
        <span class="success"></span>
    </div>
    <div class="alert alert-light">
        <span class="before">Данные отправляются...</span>
    </div>                    
</div>
<div id="comment-edit">
    <div class="card card-body">
        <form id="comment_form" method="post" action="<?= \ishop\Link::getLink('comment/add'); ?>" class="comment-form new-item" novalidate="novalidate">
            <div>
                <div class="comment-form__header">
                    <div class="d-flex-comment-form">
                        <div class="form-group">
                            <input class="comment-form__field comment-form__field_name form-control" type="text" name="name" id="name" value="" placeholder="Представьтесь" maxlength="40" data-charcounter="no" required>
                            <div class="invalid-feedback">
                                Введите, пожалуйста, имя!
                            </div>
                        </div>
                        <div class="d-flex-comment-form">
                            <div id="auth_block" class="comment-form-social js-comment-social">
                                <div class="d-flex-comment-form">
                                    <div class="comment-form-social__text">
                                        или войдите через
                                    </div>
                                    <div class="d-flex-comment-form">
                                        <div class="comment-form-social__item">
                                            <div class="comment-form__social-btn-block">
                                                <script>
                                                    (function () {
                                                        function vkInit() {
                                                            try {
                                                                window.VK.init({apiId: <?= $vk_id; ?>});
                                                                $('.js-auth-vk').removeClass('social-auth_hidden');
                                                                $('.js-comment-social').removeClass('social-auth_hidden');
                                                            } catch (e) {
                                                                console.log(e.message);
                                                            }
                                                            $('.vk_auth_btn').on("click", function (e) {
                                                                var button = $(e.target),
                                                                        form = button.parents('#comment_form');

                                                                VK.Auth.login(function (data) {
                                                                    var user = {};
                                                                    VK.Api.call('users.get', {user_ids: data.session.user.id, fields: 'photo_50', v: '5.0'}, function (r) {
                                                                        var name, avatar, uid, provider;
                                                                        user = r.response[0];

                                                                        form.find('#captcha-element').remove();
                                                                        name = document.createElement('input');
                                                                        name.type = 'hidden';
                                                                        name.value = user.first_name + ' ' + user.last_name;
                                                                        name.name = 'author[name]';

                                                                        avatar = document.createElement('input');
                                                                        avatar.type = 'hidden';
                                                                        avatar.value = protocolCutter(user.photo_50);
                                                                        avatar.name = 'author[avatar]';

                                                                        uid = document.createElement('input');
                                                                        uid.type = 'hidden';
                                                                        uid.value = user.id;
                                                                        uid.name = 'author[uid]';

                                                                        provider = document.createElement('input');
                                                                        provider.type = 'hidden';
                                                                        provider.value = 'vk';
                                                                        provider.name = 'author[provider]';

                                                                        form.append(name)
                                                                                .append(avatar)
                                                                                .append(uid)
                                                                                .append(provider);
                                                                        form.find('#name').val(name.value).attr('type', 'hidden').parent().hide();
                                                                        form.find('#auth_block').replaceWith('<div class="comment-item__avatar">' +
                                                                                '<img class="comment-item-avatar" src="' + avatar.value + '"></div>' +
                                                                                '<div class="comment-item__header"><div class="comment-item__author">' + name.value
                                                                                + '</div></div>');
                                                                    });
                                                                });
                                                            });
                                                        }

                                                        function protocolCutter(photo) {
                                                            if ('http:' === photo.substr(0, 5)) {
                                                                photo = photo.substr(5);
                                                            } else if ('https:' === photo.substr(0, 6)) {
                                                                photo = photo.substr(6);
                                                            }
                                                            return photo;
                                                        }

                                                        if (!window.VK) {
                                                            document.addEventListener("DOMContentLoaded", function () {
                                                                $(document).on('vk-loaded', function () {
                                                                    vkInit();
                                                                });
                                                            });
                                                        } else {
                                                            vkInit();
                                                        }
                                                    }());

                                                </script>
                                                <a class="btn button_for_vk vk_auth_btn js-auth-vk">
                                                    <i class='fa fa-vk' aria-hidden='true'></i>
                                                    <span class="inline-block">ВКонтакте</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="comment-form-social__item">
                                            <div class="comment-form__social-btn-block">
                                                <script>
                                                    (function () {
                                                        if (!window.FB) {
                                                            document.addEventListener("DOMContentLoaded", function () {
                                                                $(document).on('fb-loaded', function () {
                                                                    fbInit();
                                                                });
                                                            });
                                                        } else {
                                                            fbInit();
                                                        }

                                                        function fbInit() {
                                                            try {
                                                                window.FB.init({
                                                                    appId: <?= $fb_id; ?>,
                                                                    cookie: true,
                                                                    xfbml: true,
                                                                    version: 'v3.2'
                                                                });
                                                                $('.js-auth-fb').removeClass('social-auth_hidden');
                                                                $('.js-comment-social').removeClass('social-auth_hidden');
                                                            } catch (e) {
                                                                console.log(e.message);
                                                            }
                                                            $('.fb_auth_btn').on("click", function (e) {
                                                                var button = $(e.target),
                                                                        form = button.parents('#comment_form');
                                                                window.FB.login(function () {
                                                                    var user = {};
                                                                    FB.api('/me', function (response) {
                                                                        if ('error' in response) {

                                                                            return;
                                                                        }

                                                                        var name, avatar, uid, provider;
                                                                        user = response;

                                                                        form.find('#captcha-element').remove();
                                                                        name = document.createElement('input');
                                                                        name.type = 'hidden';
                                                                        name.value = user.name;
                                                                        name.name = 'author[name]';

                                                                        avatar = document.createElement('input');
                                                                        avatar.type = 'hidden';
                                                                        avatar.value = '//graph.facebook.com/' + user.id + '/picture?type=square';
                                                                        avatar.name = 'author[avatar]';

                                                                        uid = document.createElement('input');
                                                                        uid.type = 'hidden';
                                                                        uid.value = user.id;
                                                                        uid.name = 'author[uid]';

                                                                        provider = document.createElement('input');
                                                                        provider.type = 'hidden';
                                                                        provider.value = 'fb';
                                                                        provider.name = 'author[provider]';

                                                                        form.append(name)
                                                                                .append(avatar)
                                                                                .append(uid)
                                                                                .append(provider);
                                                                        form.find('#name, #nameComment').val(name.value).attr('type', 'hidden').parent().hide();
                                                                        form.find('#auth_block').replaceWith('<div class="comment-item__avatar">' +
                                                                                '<img class="comment-item-avatar" src="' + avatar.value + '"></div>' +
                                                                                '<div class="comment-item__header"><div class="comment-item__author">' + name.value
                                                                                + '</div></div>');
                                                                    });
                                                                })
                                                            });
                                                        }
                                                    }());
                                                </script>
                                                <a class="btn button_for_fb fb_auth_btn js-auth-fb">
                                                    <i class='fa fa-facebook' aria-hidden='true'></i>
                                                    <span class="inline-block -mg-l-10">Facebook</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <textarea class="comment-form__field comment-form__field_text form-control" name="commenttext" id="commenttext" placeholder="Комментарий" rows="5" maxlength="3000" data-charcounter="no" cols="80" required></textarea>
                    <div class="invalid-feedback">
                        Введите, пожалуйста, отзыв!
                    </div>
                </div>
            </div>	
            <div class="d-flex justify-content-end">
                <div class="comment-form__bottom-buttons-block d-flex">
                    <div class="comment-form__bottom-button-box_cancel">
                        <button class="btn btn-raised button_for_comment-form-cancel" type="reset" data-toggle="collapse" data-target="#collapseAddComment">
                            Отмена
                        </button>
                    </div>
                    <div class="comment-form__bottom-button-box_submit">
                        <button class="btn btn-raised btn-secondary ms-2 button_for_comment-form-submit disabled" type="submit" id="commentsub">Отправить</button>
                        <button class="g-recaptcha d-none" data-sitekey="<?= $reCAPTCHA['key']; ?>" data-callback="checedcapt"></button>
                    </div>
                </div>        
            </div>
        </form>
    </div>
</div>
<script>
    var forms = document.getElementsByClassName('comment-form new-item');
    var validation = Array.prototype.filter.call(forms, function (form) {
        form.addEventListener('submit', function (event) {
            if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    function checedcapt(token) {
        $('#commentsub').removeClass('disabled');
        //console.log('INvice test passed');
    }

    function onloadCallback() {
        grecaptcha.execute();
    }
    $(document).on("submit", forms, function (event) {
        var form = $(event.target);
        $.ajax({
            url: path + '/comment/add',
            dataType: 'json',
            data: form.serialize(),
            type: 'POST',
             beforeSend: function () {
                $('#comment-edit').delay(200).fadeOut('slow', function () {
                    $('.askquestion-message-wrap .alert-light').fadeIn('slow');
                });
            },
            success: function (res) {
                // console.log(res);
                if (res.success === true) {
                    $('.askquestion-message-wrap .alert-light').delay(200).fadeOut('slow', function () {
                        $('.askquestion-message-wrap .alert-success > span').html(res.messege);
                        $('.askquestion-message-wrap .alert-success').fadeIn('slow');
                    });
                } else {
                    grecaptcha.reset();
                    $('.askquestion-message-wrap .alert-light').fadeOut('slow');
                    $('#comment-edit').fadeIn(100, function () {
                        if (res.messege !== 'undefined') {
                            $('.askquestion-message-wrap .alert-danger > span').html(res.messege);
                            $('.askquestion-message-wrap .alert-danger').fadeIn('slow');
                        }
                    });
                }

            },
            error: function (e) {
                // console.log(e);
                grecaptcha.reset();
                $('.askquestion-message-wrap .alert-light').fadeOut('slow');
                $('#comment-edit').fadeIn(100, function () {
                    if (res.messege !== 'undefined') {
                        $('.askquestion-message-wrap .alert-danger > span').html('Ошибка соединения, попробуйте позже');
                        $('.askquestion-message-wrap .alert-danger').fadeIn('slow');
                    }
                });
            }
        });
        return false
    });

</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>