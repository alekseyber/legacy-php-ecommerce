<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование товара <?= $form->title; ?>
    </h1>
    <form action="<?= ADMIN; ?>/product/add-imageparse" method="post">
        <input type="hidden" name="id" value="<?= $form->id; ?>">
        <input type="hidden" name="redirect" value="true">
        <label><input type="checkbox" name="add" value="true"> Добавить к товару</label>        
        <button type="submit" class="btn btn-success btn-copy-product">СПФСОФС</button>
    </form>   
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/product?id=<?= $form->category_id; ?>">Список товаров</a></li>
        <li class="active">Редактирование</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="myTabMain" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="product-tab" data-toggle="tab" href="#product" role="tab" aria-controls="product" aria-selected="true">Продукт</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="modification-tab" data-toggle="tab" href="#modification" role="tab" aria-controls="modification" aria-selected="false">Модификации</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContentMain">
                <div class="tab-pane active in" id="product" role="tabpanel" aria-labelledby="product-tab">                    
                    <?= $form ?>
                </div> 
                <div class="tab-pane" id="modification" role="tabpanel" aria-labelledby="modification-tab">
                    <ul class="nav nav-tabs" id="myTabmodification" role="tablist">
                        <?php
                        $i = 0;
                        foreach ($modifications as $key => $modification):
                            $active = $i == 0 ? 'active' : '';
                            $aria_selected = $i == 0 ? 'true' : 'false';
                            $i++;
                            ?>  
                            <li class="nav-item <?= $active; ?>">
                                <a class="nav-link <?= $active; ?>" id="modification<?= $key; ?>-tab" data-toggle="tab" href="#modification<?= $key; ?>" role="tab" aria-controls="modification<?= $key; ?>" aria-selected="<?= $aria_selected ?>"><?= $color_all[$modification['color_id']]; ?></a>
                            </li>
                        <?php endforeach; ?>
                        <li class="nav-item">
                            <a class="nav-link" id="modification-new-tab" data-toggle="tab" href="#modification-new" role="tab" aria-controls="modification-new" aria-selected="false">Добавить</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContentmodification">
                        <?php
                        $i = 0;
                        foreach ($modifications as $key => $modification):
                            $active = $i == 0 ? 'active in' : '';
                            $i++;
                            ?> 
                            <div class="tab-pane <?= $active; ?>" id="modification<?= $key; ?>" role="tabpanel" aria-labelledby="modification<?= $key; ?>-tab">
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
                                                            foreach ($color_all as $k => $v):
                                                                $selected = '';

                                                                if ($modification['color_id'] == $k):
                                                                    $selected = ' selected';
                                                                    ?>                                                        
                                                                <?php endif; ?>
                                                                <?php
                                                                if (isset($color[$k]) && $modification['color_id'] != $k) {
                                                                    continue;
                                                                }
                                                                ?>
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
                                                                if ($modification['status'] == $k):
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
                                                        <input type="text" name="price" class="form-control" placeholder="Цена" pattern="^[0-9.]{1,}$" value="<?= $modification['price']; ?>" required data-error="Допускаются цифры и десятичная точка">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group has-feedback">
                                                        <label for="old_price">Старая цена</label>
                                                        <input type="text" name="old_price" class="form-control" placeholder="Старая цена" pattern="^[0-9.]{1,}$" value="<?= $modification['old_price']; ?>" data-error="Допускаются цифры и десятичная точка">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">                                       
                                                    <div class="form-group">                                            
                                                        <label>Бейдж</label>                                            
                                                        <select class="form-control" name="bagdeproduct_id">
                                                            <?php if ($modification['bagdeproduct_id'] == 0): ?>                                                            
                                                                <option value="0" selected>Не задан</option>
                                                            <?php else: ?>
                                                                <option value="0">Не задан</option>
                                                            <?php endif; ?>
                                                            <?php
                                                            foreach ($bagdeproduct as $k => $v):
                                                                $selected = '';
                                                                if ($modification['bagdeproduct_id'] == $k):
                                                                    $selected = ' selected';
                                                                    ?>                                                        
                                                                <?php endif; ?>
                                                                <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>                                            
                                                    </div>	
                                                </div><!-- col-md-2 -->
                                                <?php if ($form->product_model == '2' || $form->product_model == '3'): ?> 
                                                    <div class="col-md-4">
                                                        <div class="form-group has-feedback">
                                                            <label for="amount">Количество</label>
                                                            <input type="text" name="amount[<?= $registr[$key]['id']; ?>]" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="<?= $registr[$key]['amount']; ?>" data-error="Допускаются цифры">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($form->product_model == '1' || $form->product_model == '4'): ?>
                                                </div><h5>Размеры и количество</h5><div class="row">
                                                    <?php foreach ($modificationregistr[$key] as $idk => $modreg): ?>
                                                        <div class="col-md-1">
                                                            <div class="form-group has-feedback">
                                                                <label for="amount[<?= $registr[$key][$idk]['id']; ?>]"><?= $modreg; ?></label>
                                                                <input type="text" name="amount[<?= $registr[$key][$idk]['id']; ?>]" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="<?= $registr[$key][$idk]['amount']; ?>" data-error="Допускаются цифры">
                                                                <div class="help-block with-errors"></div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="box-footer">
                                            <input type="hidden" name="id" value="<?= $key; ?>">
                                            <input type="hidden" name="product_id" value="<?= $form->id; ?>">
                                            <button type="submit" class="btn btn-success">Сохранить</button>
                                            <button type="button" class="btn btn-danger del-modif" data-id="<?= $key; ?>">Удалить</button>                                            
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
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <div id="multi<?= $key; ?>" class="row">
                                            <?php if (!empty($gallery[$key])): ?>
                                                <?php
                                                foreach ($gallery[$key] as $item):
                                                    $main = $item["main"] ? 'selected' : '';
                                                    ?>                                    
                                                    <div class="col-sm-6 col-md-3 col-lg-2">          
                                                        <div class="box box-success product-img-box">
                                                            <div class="box-header with-border">
                                                                <h5 class="box-title"></h5>
                                                                <div class="box-tools pull-left">                                      
                                                                    <button type="button" class="btn btn-box-tool btn-default-img-pr <?= $main; ?>" title="Сделать главным фото" data-original-title="Главное превью товара" data-toggle="tooltip" data-placement="top" data-id="<?= $key; ?>" data-src="<?= $item['img']; ?>">
                                                                        <i class="fa fa-heart"></i></button>
                                                                    <button type="button" class="btn btn-box-tool del-item" title="Удалить" data-original-title="Удалить" data-id="<?= $key; ?>" data-src="<?= $item['img']; ?>"><i class="fa fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                            <div class="box-body">
                                                                <img src="/images/product/160/<?= $item["img"]; ?>" alt="" class="img-product img-fluid">
                                                            </div>
                                                            <div class="box-footer">
                                                                <p><?= $item["img"]; ?></p>
                                                                <select class="form-control img-sortvalue" data-id="<?= $key; ?>" data-src="<?= $item['img']; ?>">
                                                                    <?php
                                                                    for ($i = 0; $i <= 10; $i++):
                                                                        $sel = $item["sortvalue"] == $i ? ' selected' : '';
                                                                        ?>
                                                                        <option value="<?= $i; ?>"<?= $sel; ?>><?= $i; ?></option>
                                                                    <?php endfor; ?>                                                            
                                                                </select>
                                                            </div>
                                                            <div class="overlay">
                                                                <i class="fa fa-refresh fa-spin"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                        <form class="fileupload" action="<?= ADMIN ?>/product/add-image?id=<?= $key; ?>" method="POST" enctype="multipart/form-data" data-id='<?= $key; ?>'>
                                            <span class="btn btn-success fileinput-button">
                                                <i class="glyphicon glyphicon-plus"></i>
                                                <span>Добавить файлы...</span>                                                                
                                                <input class="fileupload-input" type="file" name="files[]" multiple>                                                                
                                            </span>
                                            <input type="hidden" name="id" value="<?= $key; ?>">
                                        </form>
                                        <div id="progress<?= $key; ?>">
                                            <div class="bar" style="width: 0%;"></div>
                                        </div>
                                        <p><small>Рекомендуемые размеры: 1500х1500</small></p>
                                    </div><!-- /.box-body -->
                                    <div class="overlay">
                                        <i class="fa fa-refresh fa-spin"></i>
                                    </div>
                                </div><!--box box-widget-->
                            </div><!--modification-1-->                            
                        <?php endforeach; ?>
                        <div class="tab-pane" id="modification-new" role="tabpanel" aria-labelledby="modification-new-tab">
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Новая модификация</h3>
                                </div><!-- /.box-header -->
                                <form class="js-form-new-modif" action="<?= ADMIN; ?>/product/add-modification" method="post" data-toggle="validator" data-posthangler="true" data-act="addmodif">
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-2">                                       
                                                <div class="form-group">                                            
                                                    <label>Цвет</label>                                            
                                                    <select class="form-control" name="color_id">
                                                        <option selected >не выбрано</option>
                                                        <?php
                                                        foreach ($color_all as $k => $v):
                                                            if (isset($color[$k])) {
                                                                continue;
                                                            }
                                                            ?> 
                                                            <option value="<?= $k; ?>"><?= $v; ?></option>
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
                                                            if ($k == 1):
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
                                                    <input type="text" name="price" class="form-control" placeholder="Цена" pattern="^[0-9.]{1,}$" value="0" required data-error="Допускаются цифры и десятичная точка">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <div class="form-group has-feedback">
                                                    <label for="old_price">Старая цена</label>
                                                    <input type="text" name="old_price" class="form-control" placeholder="Старая цена" pattern="^[0-9.]{1,}$" value="0" data-error="Допускаются цифры и десятичная точка">
                                                    <div class="help-block with-errors"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-2">                                       
                                                <div class="form-group">                                            
                                                    <label>Бейдж</label>                                            
                                                    <select class="form-control" name="bagdeproduct_id">
                                                        <option value="0" selected>Не задан</option>

                                                        <?php foreach ($bagdeproduct as $k => $v): ?>                                                            
                                                            <option value="<?= $k; ?>"><?= $v; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>                                            
                                                </div>	
                                            </div><!-- col-md-2 -->
                                            <?php if ($form->product_model == '2' || $form->product_model == '3'): ?> 
                                                <div class="col-md-4">
                                                    <div class="form-group has-feedback">
                                                        <label for="amount">Количество</label>
                                                        <input type="text" name="amount" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="100" data-error="Допускаются цифры">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($form->product_model == '1' || $form->product_model == '4'): ?>
                                            </div><h5>Размеры и количество</h5><div class="row">
                                                <?php foreach ($sizes_all as $idk => $item_size): ?>
                                                    <div class="col-md-1">
                                                        <div class="form-group has-feedback">
                                                            <label for="amount[<?= $idk; ?>]"><?= $item_size; ?></label>
                                                            <input type="text" name="amount[<?= $idk; ?>]" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="100" data-error="Допускаются цифры">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="box-footer">                                        
                                        <input type="hidden" name="product_id" value="<?= $form->id; ?>">
                                        <input type="hidden" name="product_model" value="<?= $form->product_model; ?>"> 
                                        <button type="submit" class="btn btn-success">Сохранить</button>
                                    </div>
                                </form>
                            </div><!-- /box -->

                        </div>
                    </div><!--tab-content-->
                </div><!-- modification -->
            </div> <!-- myTabContentMain-->
        </div><!-- col-md-12 -->
    </div><!-- row -->
</section>
<!-- /.content -->