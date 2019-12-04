<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Список UTM
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Список UTM</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">                        
                                <input type="text" id="typeaheadutmid" class="form-control typeahead" placeholder="Код UTM" value="" aria-label="Код UTM">
                            </div>
                        </div>         
                        <div class="col-md-1">
                            <div class="btn-panel">
                                <a href="<?= ADMIN; ?>/utm" class="btn btn-info">Все UTM</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <form enctype="multipart/form-data" action="<?= ADMIN; ?>/csv/load" method="post">
                                <div class="btn-panel-input-csv-file">                                    
                                    <input class="inputfile" type="file" name="csv" accept=".csv">
                                    <input type="hidden" name="action" value="utm">
                                    <input type="hidden" name="key_name" value="key_item">
                                    <button type="submit" class="btn btn-primary">Загрузить CSV</button>
                                </div>
                            </form>                            
                        </div>
                        <div class="col-md-2">
                            <form action="<?= ADMIN; ?>/csv/upload" method="post">
                                <div class="btn-panel-input-csv-file">
                                    <input type="hidden" name="action" value="utm">                                    
                                    <button type="submit" class="btn btn-info">Выгрузить CSV</button>
                                </div>
                            </form>                            
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive">
                        <a href="<?= ADMIN; ?>/utm/add" class="btn btn-primary"><i class="fa fa-fw fa-plus"></i> Добавить UTM</a>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>                                                                   
                                    <th>KEY</th>
                                    <th>Title</th>                                    
                                    <th>Статус</th>
                                    <th>Показывать везде</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($utms as $utm): ?>
                                    <tr>
                                        <td><?= $utm->id; ?></td>
                                        <td><a href="<?= ADMIN; ?>/utm/edit?id=<?= $utm->id; ?>"><?= $utm->key_item; ?></a></td>
                                        <td>
                                            <input type="text" name="title" class="form-control js-input-form" value="<?= $utm->title; ?>" data-id="<?= $utm->id; ?>" data-oldval="<?= $utm->title; ?>" data-pattern="false" data-table="utm" data-action="<?= ADMIN; ?>/category/edit-value">
                                        </td> 
                                        <?php $class = $utm->status ? ' success' : ''; ?>
                                        <td class="order-status-bgr<?= $class; ?>">                                           
                                            <?php $utm_status = ['0' => 'OFF', '1' => 'ON']; ?>
                                            <select class="form-control order-status-select" data-oldval="<?= $utm->status; ?>" data-action="<?= ADMIN; ?>/utm/change-status?name=status" data-id="<?= $utm->id; ?>">                                                    
                                                <?php
                                                foreach ($utm_status as $k => $v):
                                                    $selected = '';
                                                    if ($utm->status == $k):
                                                        $selected = ' selected';
                                                        ?>
                                                    <?php endif; ?>
                                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                <?php endforeach; ?>                                    
                                            </select>                                                                                  
                                        </td>
                                        <td>
                                            <?php $all_on = ['0' => 'Нет', '1' => 'Да']; ?>
                                            <select class="form-control order-status-select" data-oldval="<?= $utm->all_on; ?>" data-action="<?= ADMIN; ?>/utm/change-status?name=all_on" data-id="<?= $utm->id; ?>">                                                    
                                                <?php
                                                foreach ($all_on as $k => $v):
                                                    $selected = '';
                                                    if ($utm->all_on == $k):
                                                        $selected = ' selected';
                                                        ?>
                                                    <?php endif; ?>
                                                    <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                                <?php endforeach; ?>                                    
                                            </select>                                                                                  
                                        </td>
                                        <td><a href="<?= ADMIN; ?>/utm/edit?id=<?= $utm->id; ?>"><i class="fa fa-fw fa-eye"></i></a> <a class="delete" href="<?= ADMIN; ?>/utm/delete?id=<?= $utm->id; ?>"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <p>(<?= count($utms); ?> UTM из <?= $count; ?>)</p>
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