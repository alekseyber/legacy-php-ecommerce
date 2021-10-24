<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Код между тегами header и в футере
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Header</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <form class="js-form-edit-lastmodified" action="<?= ADMIN; ?>/headerstr/edit" method="post">
        <input name="id" type="hidden" value="<?= $headerstr->id; ?>>
               <div class="row">
               <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Код для вставки</h3>
                    <div class="box-tools pull-right">
                        <button type="submit" class="btn btn-success btn-save-product">Сохранить</button>
                    </div>              
                </div>
                <div class="box-body">
                    <div class="card card-primary card-outline card-outline-tabs">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                <li class="nav-item active">
                                    <a class="nav-link " id="custom-tabs-four-header-tab" data-toggle="tab" href="#custom-tabs-four-header" role="tab" aria-controls="custom-tabs-four-header" aria-selected="true">Header</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-footer-tab" data-toggle="tab" href="#custom-tabs-four-footer" role="tab" aria-controls="custom-tabs-four-footer" aria-selected="false">Bottom</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-four-orderdone-tab" data-toggle="tab" href="#custom-tabs-four-orderdone" role="tab" aria-controls="custom-tabs-four-orderdone" aria-selected="false">OrderDone</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                <div class="tab-pane active" id="custom-tabs-four-header" role="tabpanel" aria-labelledby="custom-tabs-four-header-tab">
                                    <div class="form-group">                           
                                        <textarea class="form-control" rows="27" name="header" placeholder="Enter ..."><?= $headerstr->header; ?></textarea>
                                    </div>
                                </div>                                
                                <div class="tab-pane fade" id="custom-tabs-four-footer" role="tabpanel" aria-labelledby="custom-tabs-four-footer-tab">
                                    <div class="form-group">  
                                        <textarea class="form-control" rows="27" name="footer" placeholder="Enter ..."><?= $headerstr->footer; ?></textarea>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="custom-tabs-four-orderdone" role="tabpanel" aria-labelledby="custom-tabs-four-orderdone-tab">
                                    <div class="callout callout-info">
                                        <h4>Информация</h4>
                                        <p>Помещать без тегов "script"</p>
                                        <p>Доступен массив arguments: [order_num, cart_sum, cart_currency, cart_data].</p>
                                    </div>
                                    <div class="form-group">  
                                        <textarea class="form-control" rows="27" name="orderdone" placeholder="Enter ..."><?= $headerstr->orderdone; ?></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>  

                </div>
            </div>
        </div>

    </form>
</section>
<!-- /.content -->