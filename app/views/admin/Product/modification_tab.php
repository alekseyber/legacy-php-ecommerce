<div class="tab-pane" id="modification<?= $new_mod['id']; ?>" role="tabpanel" aria-labelledby="modification<?= $new_mod['id']; ?>-tab">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Основные данные</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div><!-- /.box-header -->
        <form class="js-form-edit-modif" action="<?= ADMIN; ?>/product/edit-modification" method="post" data-toggle="validator">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">                                       
                        <div class="form-group">                                            
                            <label>Цвет</label>                                            
                            <select class="form-control" name="color_id">
                                <?php
                                foreach ($new_mod['color_all'] as $k => $v):
                                    $selected = '';
                                    if (isset($new_mod['color'][$k]) && $new_mod['modification']['color_id'] != $k) {
                                        continue;
                                    }
                                    if ($new_mod['modification']['color_id'] == $k):
                                        $selected = ' selected';
                                        ?>                                                        
                                    <?php endif; ?>
                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>                                            
                        </div>	
                    </div><!-- col-md-2 -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Выводить модификацию</label>
                            <?php $arr = [1 => 'Включен', 0 => 'Выключен']; ?>  
                            <select class="form-control" name="status">
                                <?php
                                foreach ($arr as $k => $v):
                                    $selected = '';
                                    if ($new_mod['modification']['status'] == $k):
                                        $selected = ' selected';
                                        ?>

                                    <?php endif; ?>
                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>                                            
                        </div>	
                    </div>
                    <div class="col-md-1">
                        <div class="form-group has-feedback">
                            <label for="price">Цена</label>
                            <input type="text" name="price" class="form-control" placeholder="Цена" pattern="^[0-9.]{1,}$" value="<?= $new_mod['modification']['price']; ?>" required data-error="Допускаются цифры и десятичная точка">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group has-feedback">
                            <label for="old_price">Старая цена</label>
                            <input type="text" name="old_price" class="form-control" placeholder="Старая цена" pattern="^[0-9.]{1,}$" value="<?= $new_mod['modification']['old_price']; ?>" data-error="Допускаются цифры и десятичная точка">
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-2">                                       
                        <div class="form-group">                                            
                            <label>Бейдж</label>                                            
                            <select class="form-control" name="bagdeproduct_id">
                                <?php if ($new_mod['modification']['bagdeproduct_id'] == 0): ?>                                                            
                                    <option value="0" selected>Не задан</option>
                                <?php endif; ?>
                                <?php
                                foreach ($new_mod['bagdeproduct'] as $k => $v):
                                    $selected = '';
                                    if ($new_mod['modification']['bagdeproduct_id'] == $k):
                                        $selected = ' selected';
                                        ?>                                                        
                                    <?php endif; ?>
                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                <?php endforeach; ?>
                            </select>                                            
                        </div>	
                    </div><!-- col-md-2 -->
                    <?php if ($new_mod['product_model'] == '2' || $new_mod['product_model'] == '3'): ?> 
                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="amount">Количество</label>
                                <input type="text" name="amount[<?= $new_mod['registr']['id']; ?>]" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="<?= $new_mod['registr']['amount']; ?>" data-error="Допускаются цифры">
                                <div class="help-block with-errors"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($new_mod['product_model'] == '1' || $new_mod['product_model'] == '4'): ?>
                    </div><h5>Размеры и количество</h5><div class="row">
                        <?php foreach ($new_mod['modificationregistr'] as $idk => $modreg): ?>
                            <div class="col-md-1">
                                <div class="form-group has-feedback">
                                    <label for="amount[<?= $new_mod['registr'][$idk]['id']; ?>]"><?= $modreg; ?></label>
                                    <input type="text" name="amount[<?= $new_mod['registr'][$idk]['id']; ?>]" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="<?= $new_mod['registr'][$idk]['amount']; ?>" data-error="Допускаются цифры">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="box-footer">
                <input type="hidden" name="id" value="<?= $new_mod['id']; ?>">
                <input type="hidden" name="product_id" value="<?= $new_mod['product_id']; ?>"> 
                <button type="submit" class="btn btn-success">Сохранить</button>
                <button type="button" class="btn btn-danger del-modif" data-id="<?= $new_mod['id']; ?>">Удалить</button>
            </div>
        </form>
        <div class="overlay">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
    </div><!-- /box -->
    <div class="box box-widget">
        <div class="box-header with-border">
            <h3 class="box-title">Фото</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
            <!-- /.box-tools -->
        </div><!-- /.box-header -->
        <div class="box-body">
            <div id="multi<?= $new_mod['id']; ?>" class="row"></div>
        </div>
    </div><!-- /box-widget collapsed-box -->                                
    <div class="box box-widget">
        <div class="box-header with-border">
            <h3 class="box-title">Добавить фото</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group"> 
                <div class="box box-primary box-solid file-upload">
                    <div class="box-header">
                        <h3 class="box-title">Добавить изображение</h3>
                    </div>
                    <div class="box-body">
                        <form class="fileupload" action="<?= ADMIN ?>/product/add-image?id=<?= $new_mod['id']; ?>" method="POST" enctype="multipart/form-data" data-id='<?= $new_mod['id']; ?>'>
                            <span class="btn btn-success fileinput-button">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span>Добавить файлы...</span>                                                                
                                <input class="fileupload-input" type="file" name="files[]" multiple>                                                                
                            </span>
                            <input type="hidden" name="id" value="<?= $new_mod['id']; ?>">
                        </form>
                        <div id="progress<?= $new_mod['id']; ?>">
                            <div class="bar" style="width: 0%;"></div>
                        </div>
                        <p><small>Рекомендуемые размеры: 1500х1500</small></p>
                    </div>
                    <div class="overlay">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!--box box-widget-->
</div><!--modification-1-->  