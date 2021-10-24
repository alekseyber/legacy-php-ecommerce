<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый промокод
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/cupons">Промокоды</a></li>
        <li class="active">Новый промокод</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <form action="<?= ADMIN; ?>/cupons/add" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="code">Промокод</label>
                            <input type="text" name="code" class="form-control" id="code" placeholder="Промокод" value="" required>
                            <span class="glyphicon form-control-feedback"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="value">Скидка, %</label>
                            <input 
                                class="form-control" 
                                name="value"
                                type="number"
                                value="5"
                                max="100"
                                required
                                > 
                            <span class="glyphicon form-control-feedback"></span>
                        </div>
                        <div class="form-group">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input 
                                    class="form-control pull-right datepicker datepickerinput" 
                                    name="date_end"
                                    type="text" 
                                    value=""
                                    required
                                    >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Примечание</label>
                            <input type="text" name="description" class="form-control" id="description" placeholder="Примечание" value="">                            
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>                       
                    </div>
                </form>                
            </div>
        </div>
    </div>
</section>
<!-- /.content -->