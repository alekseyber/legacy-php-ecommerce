<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Внести IP в блок
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN ?>"><i class="fa fa-dashboard"></i> Главная</a></li>        
        <li class="active">Внести IP в блок</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form method="post" action="<?= ADMIN; ?>/user/add-block" role="form" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="ip">Введите IP</label>
                            <input class="form-control ip-adress" name="ip" id="ip" placeholder="Введите IP" type="text" pattern="^(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[0-9]{2}|[0-9])){3}$" value="" required>
                        </div>
                    </div>  
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Внести</button>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</section>
<!-- /.content -->