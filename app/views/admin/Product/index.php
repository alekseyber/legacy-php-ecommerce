<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список товаров<?= $msg; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/category">Список категорий</a></li>
        <li class="active">Список товаров</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <div class="row"> 
                <div class="col-md-2">
                    <div class="form-group">                        
                        <input type="text" id="typeaheadproductsku" class="form-control typeahead" placeholder="SKU" value="<?= $sku; ?>" aria-label="SKU">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">                        
                        <input type="text" id="typeaheadproductid" class="form-control typeahead" placeholder="Наименование" value="" aria-label="Наименование">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <?php $genders = [0 => 'Все', 1 => 'Женские', 2 => 'Мужские', 3 => 'Детские', 4 => 'Унисекс']; ?>                        
                        <select class="form-control order-status-filter" data-oldval="<?= $gender; ?>" data-action="<?= ADMIN; ?>/product?gender=" data-id="">                                                    
                            <?php foreach ($genders as $k => $v):
                                $selected = '';
                                if ($gender == $k):
                                    $selected = ' selected';
                                    $sel = true;
                                    ?>
                                <?php endif; ?>
                                <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                            <?php endforeach; ?>                                    
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="btn-panel">
                        <a href="<?= ADMIN; ?>/product" class="btn btn-info">Все продукты</a>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="btn-panel">
                        <a href="<?= ADMIN; ?>/product/add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить продукт</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <form enctype="multipart/form-data" action="<?= ADMIN; ?>/csv/load" method="post">
                        <div class="btn-panel-input-csv-file">                                    
                            <input class="inputfile" type="file" name="csv" accept=".csv">
                            <input type="hidden" name="action" value="product">
                            <input type="hidden" name="key_name" value="id">
                            <button type="submit" class="btn btn-primary">Загрузить CSV</button>
                        </div>
                    </form>                            
                </div>
                <div class="col-md-2">
                    <form action="<?= ADMIN; ?>/csv/upload" method="post">
                        <div class="btn-panel-input-csv-file">
                            <input type="hidden" name="action" value="product">                                    
                            <button type="submit" class="btn btn-info">Выгрузить CSV</button>
                        </div>
                    </form>                            
                </div>
                <div class="col-md-3">
                    <form action="<?= ADMIN; ?>/excell/upload" method="post">
                        <div class="btn-panel-input-csv-file">
                            <div class="checkbox" style="margin-top:1px;margin-bottom:1px;">
                                <label>
                                    <input type="checkbox" name="onlyugg" value="true" checked>Только Демо-бренд
                                </label>
                                <label>
                                    <input type="checkbox" name="price" value="true">Вывести цену
                                </label>
                            </div>
                            <div>                                  
                                <button type="submit" class="btn btn-info">Выгрузить в Excell</button>
                            </div>
                        </div>
                    </form>                            
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box-body">
                        <div class="table-responsive">                        
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Категория</th>                                
                                        <th>Наименование</th>
                                        <th>Цена</th>
                                        <th>SKU</th>
                                        <th>Гендер</th>
                                        <th>Модель</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?= $product['id']; ?></td>
                                            <td><?= $product['cat']; ?></td>
                                            <td><a href="<?= ADMIN; ?>/product/edit?id=<?= $product['id']; ?>"><?= $product['title']; ?></a></td>
                                            <td>
                                                <input type="text" name="price" class="form-control js-input-form" style="max-width: 100px; margin: 0;" pattern="^[0-9]{1,}$" value="<?= $product['price']; ?>" data-id="<?= $product['id']; ?>" data-oldval="<?= $product['price']; ?>" data-pattern="true" data-table="product" data-action="<?= ADMIN; ?>/category/edit-value">
                                            </td>
                                            <td><?= $product['sku']; ?></td>
                                            <td><?= $product['gender']; ?></td>
                                            <td><?= $product['product_model']; ?></td>
                                            <?php $class = $product['status'] == '1' ? ' success' : ''; ?>
                                            <td class="order-status-bgr<?= $class; ?>">                                            
                                                <?php $pr_status = ['1' => 'On', '2' => 'Off']; ?>
                                                <select class="form-control order-status-select" data-oldval="<?= $product['status']; ?>" data-action="<?= ADMIN; ?>/product/change-status" data-id="<?= $product['id']; ?>">                                                    
                                                    <?php foreach ($pr_status as $k => $v):
                                                        $selected = '';
                                                        if ($product['status'] == $k):
                                                            $selected = ' selected';
                                                            ?>
                                                        <?php endif; ?>
                                                        <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                    <?php endforeach; ?>                                    
                                                </select>                                                                                  
                                            </td>
                                            <td>
        <!--                                            <a href="<?= ADMIN; ?>/product/edit?id=<?= $product['id']; ?>"><i class="fa fa-fw fa-eye"></i></a> -->
                                                <a class="delete" href="<?= ADMIN; ?>/product/delete?id=<?= $product['id']; ?>"><i class="fa fa-fw fa-close text-danger"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <p>(<?= count($products); ?> товаров из <?= $count; ?>)</p>
                            <?php if ($pagination->countPages > 1): ?>
                                <?= $pagination; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->