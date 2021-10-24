<?php
$vk_key = \ishop\App::$app->getProperty('vk_key');
//$fb_key = \ishop\App::$app->getProperty('fb_key');
$vk_id = $vk_key['id'];
//$fb_id = $fb_key['id'];
?>
<div id="comment-edit" data-mode-status="jsvkform">
    <div data-selector="js-form" data-name="addcomment" data-re="true">
        <div class="card">            
            <form method="post" data-selector="js-vk-form" data-apiid="<?= $vk_id; ?>" action="<?= \ishop\Link::getLink('comment/add'); ?>" class="needs-validation" novalidate="novalidate">
                <div class="card-body">                    
                    <div class="row">
                        <div class="col-sm-4" data-selector="name-wrap">
                            <div class="form-group">
                                <label for="addcomment-name" class="bmd-label-floating">Представьтесь*</label>
                                <input 
                                    type="text" 
                                    id="addcomment-name"
                                    name="name"
                                    class="form-control"
                                    value="" 
                                    required
                                    maxlength="40"
                                    data-selector="textinput"
                                    >
                                <div class="invalid-feedback">
                                    Введите, пожалуйста, имя!
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 mt-3 d-none" data-selector="social-btn-wrap">
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="me-3 fw-bold">или войдите через</div>
                                <div>
                                    <button type="button" class="btn btn-raised vk_auth_btn" data-selector="auth-vk">
                                        <span class="vk_logo me-2"></span>                                                        
                                        <span>ВКонтакте</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="addcomment-commenttext" class="bmd-label-floating">Ваш отзыв</label>
                        <textarea
                            name="commenttext"
                            id="aaddcomment-commenttext"
                            data-selector="textareainput"
                            class="form-control"
                            rows="5"
                            maxlength="3000"
                            required
                            ></textarea>
                        <div class="invalid-feedback">
                            Введите, пожалуйста, отзыв!
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">                        
                        <button class="btn btn-raised" type="reset" data-bs-toggle="collapse" data-bs-target="#collapseAddComment">Отмена</button>                        
                        <button class="btn btn-raised btn-secondary ms-3" type="submit">Отправить</button>                        
                    </div> 
                </div>
            </form>
        </div>
    </div>
</div>