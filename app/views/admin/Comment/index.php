<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список Отзывов
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Список Отзывов</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-body">

                        <?php foreach ($comments as $comment): ?>
                            <div class="card" style="margin-bottom: 15px; border: 1px solid #f7f7f7">
                                <div class="card-body p-2">
                                    <div class="clearfix h4" style="background-color:#f7f7f7; font-size: 18px; padding: 7px 10px; margin-top: 0;">
                                        <div class="col-xs-11">
                                            <span><strong>ID: </strong><?= $comment->id; ?></span>
                                            <span><strong> Имя: </strong><?= $comment->author_name; ?></span>
                                            <span><strong> Дата: </strong><?= dattimereturnexpl($comment->date); ?></span>
                                            <span><strong> IP: </strong><?= long2ip($comment->ip) ?></span>
                                        </div>
                                        <div class="col-xs-1 text-right">
                                            <a class="delete" href="<?= ADMIN; ?>/comment/delete?id=<?= $comment->id; ?>"><i class="fa fa-trash-o text-danger"></i></a>
                                        </div>
                                    </div>
                                    <div class="media">
                                        <div class="media-left" style="min-width: 7%; max-width: 70px; padding:10px">
                                            <?php if ($comment->social): ?>
                                                <?php $soclink = uid_social_link($comment->author_uid, $comment->author_provider) ?>
                                                <div class="avatar">
                                                    <a href="<?= $soclink; ?>" target="_blank" rel="nofollow">
                                                        <img class="comment-item-avatar rounded-circle" src="<?= $comment->author_avatar; ?>" alt="<?= $comment->author_name; ?>">
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div><i class="fa fa-user" aria-hidden="true"></i><?= $comment->author_name; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="media-body ms-3">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Имя</label>
                                                        <input type="text" name="author_name" class="form-control js-input-form" value="<?= $comment->author_name; ?>" data-id="<?= $comment->id; ?>" data-oldval="<?= $comment->author_name; ?>" data-pattern="false" data-table="comment" data-action="<?= ADMIN; ?>/category/edit-value">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group">
                                                        <label>Дата</label>
                                                        <input type="text" name="date" class="form-control js-input-form" value="<?= $comment->date; ?>" data-id="<?= $comment->id; ?>" data-oldval="<?= $comment->date; ?>" data-pattern="false" data-table="comment" data-action="<?= ADMIN; ?>/category/edit-value">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group text-center">
                                                        <?php $class = $comment->status ? ' success' : ''; ?>
                                                        <div class="order-status-bgr<?= $class; ?>">
                                                            <?php $comment_status = [0 => 'OFF', 1 => 'ON']; ?>
                                                            <label>Включен</label>
                                                            <select class="form-control order-status-select" data-oldval="<?= $comment->status; ?>" data-action="<?= ADMIN; ?>/comment/change-status?name=status" data-id="<?= $comment->id; ?>">                                                    
                                                                <?php
                                                                foreach ($comment_status as $k => $v):
                                                                    $selected = '';
                                                                    if ($comment->status == $k):
                                                                        $selected = ' selected';
                                                                        ?>
                                                                    <?php endif; ?>
                                                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                                <?php endforeach; ?>                                    
                                                            </select>                                                                                  
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="form-group text-center">                                                        
                                                        <?php $all_on = [0 => 'Нет', 1 => 'Да']; ?>
                                                        <label>Авторизация</label>
                                                        <select class="form-control order-status-select" data-oldval="<?= $comment->social; ?>" data-action="<?= ADMIN; ?>/comment/change-status?name=social" data-id="<?= $comment->id; ?>">                                                    
                                                            <?php
                                                            foreach ($all_on as $k => $v):
                                                                $selected = '';
                                                                if ($comment->social == $k):
                                                                    $selected = ' selected';
                                                                    ?>
                                                                <?php endif; ?>
                                                                <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                            <?php endforeach; ?>                                    
                                                        </select>                                                                                  
                                                    </div>                                                    
                                                </div>
                                                <div class="col-sm-2">
                                                    <p>Соцсеть: <strong><?= $comment->author_provider; ?></strong></p>
                                                    <p>Author UID: <strong><?= $comment->author_uid; ?></strong></p>
                                                </div>
                                            </div>  
                                            <div class="form-group">
                                                <label>Отзыв</label>
                                                <textarea name="commen_text" class="form-control js-input-form" rows="3" placeholder="Enter ..." data-id="<?= $comment->id; ?>" data-oldval="<?= $comment->commen_text; ?>" data-pattern="false" data-table="comment" data-action="<?= ADMIN; ?>/category/edit-value"><?= $comment->commen_text; ?></textarea>
                                            </div>                                                
                                            <p>Соцсеть IMG Avatar: <strong><?= $comment->author_avatar; ?></strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="text-center">
                        <p>(<?= count($comments); ?> Comment из <?= $count; ?>)</p>
                        <?php if ($pagination->countPages > 1): ?>
                            <?= $pagination; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->