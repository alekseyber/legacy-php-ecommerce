<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый товар
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/product">Список товаров</a></li>
        <li class="active">Новый товар</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN; ?>/product/add" method="post" data-toggle="validator" id="add">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Наименование товара</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Наименование товара" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Родительская категория</label>
                                    <?php
                                    $category = new \app\widgets\menu\Menu([
                                        'tpl' => WWW . '/menu/select.php',
                                        'container' => 'select',
                                        'cache' => 0,
                                        'cacheKey' => 'admin_select',
                                        'class' => 'form-control',
                                        'attrs' => [
                                            'name' => 'category_id',
                                            'id' => 'category_id',
                                        ],
                                    ]);
                                    echo $category;
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dopcategory">Дополнительные каталоги</label>
                                    <?php
                                    $category = new \app\widgets\menu\Menu([
                                        'tpl' => WWW . '/menu/select2ToTree.php',
                                        'container' => 'select multiple',
                                        'cache' => 0,
                                        'cacheKey' => 'admin_select_multiple',
                                        'class' => 'form-control select2ToTree',
                                        'attrs' => [
                                            'name' => 'dopcategory[]',
                                            'id' => 'dopcategory',
                                        ],
                                    ]);
                                    echo $category;
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Выводить продукт</label>
                                    <?php $arr = ['1' => 'Включен', '2' => 'Выключен']; ?>  
                                    <select class="form-control" name="status" id="status">
                                        <?php
                                        $status = $_SESSION['form_data']['status'] ?? 2;
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($status == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Хит</label>
                                    <?php $arr = ['1' => 'Да', '2' => 'Нет']; ?>  
                                    <select class="form-control" name="hit" id="hit">
                                        <?php
                                        $hit = $_SESSION['form_data']['hit'] ?? 2;
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($hit == $k):
                                                $selected = ' selected';
                                                ?>

                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Предлагать в корзине</label>
                                    <?php $arr = ['1' => 'Нет', '2' => 'Да']; ?>  
                                    <select class="form-control" name="cart_on" id="cart_on">
                                        <?php
                                        $cart_on = $_SESSION['form_data']['cart_on'] ?? 1;
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($cart_on == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="price">Цена</label>
                                    <input type="text" name="price" class="form-control" id="price" placeholder="Цена" pattern="^[0-9.]{1,}$" value="<?php echo isset($_SESSION['form_data']['price']) ? h($_SESSION['form_data']['price']) : null; ?>" required data-error="Допускаются цифры и десятичная точка">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group has-feedback">
                                    <label for="old_price">Старая цена</label>
                                    <input type="text" name="old_price" class="form-control" id="old_price" placeholder="Старая цена" pattern="^[0-9.]{1,}$" value="<?php echo isset($_SESSION['form_data']['old_price']) ? h($_SESSION['form_data']['old_price']) : null; ?>" data-error="Допускаются цифры и десятичная точка">
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">                                        
                                    <label for="related">Связанный товар</label>
                                    <select name="related" class="form-control select2" id="related">
                                        <?php $related = $_SESSION['form_data']['related'] ?? null; ?>
                                        <?php if ($related): ?>
                                            <option value="<?= $related; ?>" selected><?= $related; ?></option>                                            
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sku">SKU</label>
                                    <input type="text" name="sku" class="form-control" id="sku" placeholder="SKU" value="<?php echo isset($_SESSION['form_data']['sku']) ? h($_SESSION['form_data']['sku']) : null; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Продукт модель*</label>
                                    <?php $arr = ['1' => 'цвет, размер', '2' => 'цвет', '3' => 'цвет один', '4' => 'цвет один с разм']; ?>                                    
                                    <select name="product_model" class="form-control" id="product_model">
                                        <?php
                                        $product_model = $_SESSION['form_data']['product_model'] ?? '1';
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($product_model == $k):
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
                                    <label>Пол префикс*</label>
                                    <?php $arr = ['Женские', 'Мужские', 'Детские']; ?>
                                    <input type="hidden" name="gender" value="<?= $product->gender; ?>">
                                    <select name="gender" class="form-control" id="gender">
                                        <?php
                                        $gender = $_SESSION['form_data']['gender'] ?? 'Женские';
                                        foreach ($arr as $v):
                                            $selected = '';
                                            if ($gender == $v):
                                                $selected = ' selected';
                                                ?>

                                            <?php endif; ?>
                                            <option value="<?= $v; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div><!-- col-md-2 -->
                            <div class="col-md-2">                                       
                                <div class="form-group">
                                    <?php //debug ($brand);  ?>
                                    <label>Бренд</label>                                            
                                    <select class="form-control" name="brand_id" id="brand_id">
                                        <?php
                                        $brand_id = $_SESSION['form_data']['brand_id'] ?? 1;
                                        foreach ($brand as $k => $v):
                                            $selected = '';
                                            if ($brand_id == $k):
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
                                    <label>Группа размеров*</label>                                    
                                    <select name="sizesgroup_id" class="form-control" id="sizesgroup_id">
                                        <?php
                                        $sizesgroup_id = $_SESSION['form_data']['sizesgroup_id'] ?? 2;
                                        foreach ($sizesgroup as $k => $v):
                                            $selected = '';
                                            if ($sizesgroup_id == $k):
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
                                    <label>Промо</label>                                            
                                    <select class="form-control" name="promo_id" id="promo_id">
                                        <?php
                                        $promo_id = $_SESSION['form_data']['promo_id'] ?? 1;
                                        foreach ($promo as $k => $v):
                                            $selected = '';
                                            if ($promo_id == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>
                            </div>
                        </div><!-- row -->
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                    </div>
                </form>
                <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->