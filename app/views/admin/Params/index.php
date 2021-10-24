<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Общие настройки
    </h1>    
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Общие настройки</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">   
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <?php foreach ($params as $item): ?>
                                <?php if ($item['typevalue'] === "bool"): ?>
                                    <?php $checked = (bool) $item['value'] ? ' checked' : '' ?>
                                    <div class="wrap-checkbox-on" style="max-width:25rem">
                                        <div class="label-chekbox"><?= $item['title']; ?></div>
                                        <input class="js-select-form" 
                                               id="<?= $item['id']; ?>" 
                                               type="checkbox" 
                                               name="value" 
                                               value="1"<?= $checked ?> 
                                               value="<?= $item['value']; ?>" 
                                               data-id="<?= $item['id']; ?>" 
                                               data-nullval="0"                                           
                                               data-table="params"
                                               data-chekbox="true"
                                               data-action="<?= ADMIN; ?>/category/edit-value"
                                               >
                                        <label class="slider-v2" for="<?= $item['id']; ?>"></label>
                                    </div>                                    
                                <?php else: ?>
                                    <div class="form-group">
                                        <label for="<?= $item['id']; ?>"><?= $item['title']; ?></label>
                                        <input 
                                            type="text" 
                                            name="value"  
                                            class="form-control js-input-form" 
                                            id="<?= $item['id']; ?>" 
                                            placeholder=""                                            
                                            value="<?= $item['value']; ?>" 
                                            data-id="<?= $item['id']; ?>" 
                                            data-oldval="<?= $item['value']; ?>" 
                                            data-pattern="<?= $item['pattern']; ?>"
                                            data-table="params" 
                                            data-action="<?= ADMIN; ?>/category/edit-value"
                                            >
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->