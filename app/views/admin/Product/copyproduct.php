<!-- Content Header (Page header) -->
<form class="" action="<?= ADMIN; ?>/product/copyproduct" method="post" data-toggle="validator" data-posthangler="false">
    <section class="content-header">
        <h1>
            Копирование товара <?= $form->title; ?>
        </h1>        
        <button type="submit" class="btn btn-success btn-copy-product">Добавить</button>
        <ol class="breadcrumb">
            <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
            <li><a href="<?= ADMIN; ?>/product">Список товаров</a></li>
            <li class="active">Копирование товара</li>
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
                        </ul>
                        <div class="tab-content" id="myTabContentmodification">
                            <?php
                            $i = 0;
                            foreach ($modifications as $key => $modification):
                                $active = $i == 0 ? 'active in' : '';
                                $i++;
                                $color_id = $modification['color_id'];
                                ?>
                                <input type="hidden" name="img" value="">
                                <div class="tab-pane <?= $active; ?>" id="modification<?= $key; ?>" role="tabpanel" aria-labelledby="modification<?= $key; ?>-tab">
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Основные данные</h3>
                                            <div class="box-tools">
                                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div><!-- /.box-header -->                                    
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-2">                                       
                                                    <div class="form-group">                                            
                                                        <label>Цвет</label>                                            
                                                        <select class="form-control" name="modif[<?= $color_id; ?>][color_id]">
                                                            <?php
                                                            foreach ($color_all as $k => $v):
                                                                $selected = '';
                                                                if (isset($color[$k]) && $modification['color_id'] != $k) {
                                                                    continue;
                                                                }
                                                                if ($modification['color_id'] == $k):
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
                                                        <select class="form-control" name="modif[<?= $color_id; ?>][status]">
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
                                                        <input type="text" name="modif[<?= $color_id; ?>][price]" class="form-control" placeholder="Цена" pattern="^[0-9.]{1,}$" value="<?= $modification['price']; ?>" required data-error="Допускаются цифры и десятичная точка">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group has-feedback">
                                                        <label for="old_price">Старая цена</label>
                                                        <input type="text" name="modif[<?= $color_id; ?>][old_price]" class="form-control" placeholder="Старая цена" pattern="^[0-9.]{1,}$" value="<?= $modification['old_price']; ?>" data-error="Допускаются цифры и десятичная точка">
                                                        <div class="help-block with-errors"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">                                       
                                                    <div class="form-group">                                            
                                                        <label>Бейдж</label>                                            
                                                        <select class="form-control" name="modif[<?= $color_id; ?>][bagdeproduct_id]">
                                                            <?php if ($modification['bagdeproduct_id'] == 0): ?>                                                            
                                                                <option value="0" selected>Не задан</option>
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
                                                            <input type="text" name="modif[<?= $color_id; ?>][amount]" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="<?= $registr[$key]['amount']; ?>" data-error="Допускаются цифры">
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($form->product_model == '1' || $form->product_model == '4'): ?>
                                                </div><h5>Размеры и количество</h5><div class="row">
                                                    <?php foreach ($modificationregistr[$key] as $idk => $modreg): ?>                                                        
                                                        <div class="col-md-1">
                                                            <div class="form-group has-feedback">
                                                                <label><?= $modreg['title']; ?></label>                                                                
                                                                <input type="text" name="modif[<?= $color_id; ?>][amount][<?= $modreg['sizes_id']; ?>]" class="form-control" placeholder="Количество" pattern="^[0-9]{1,}$" value="<?= $registr[$key][$idk]['amount']; ?>" data-error="Допускаются цифры">
                                                                <div class="help-block with-errors"></div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>                                    
                                        <div class="overlay">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </div>
                                    </div><!-- /box -->

                                </div><!--modification-1-->                            
                            <?php endforeach; ?>
                        </div><!--tab-content-->
                    </div><!-- modification -->
                </div> <!-- myTabContentMain-->            
            </div><!-- col-md-12 -->
        </div><!-- row -->
    </section>
</form>