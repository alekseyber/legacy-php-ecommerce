<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Новый размер
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/sizes/size-list">Размеры</a></li>
        <li class="active">Новый размер</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <form action="<?= ADMIN; ?>/sizes/size-add" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Title" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="sortvalue">Порядок сортировки</label>
                            <input type="text" name="sortvalue" class="form-control" id="sortvalue" placeholder="Введите число" value="<?php echo isset($_SESSION['form_data']['sortvalue']) ? h($_SESSION['form_data']['sortvalue']) : null; ?>">
                        </div>                        
                        <div class="form-group has-feedback">
                            <label for="alias">Alias</label>
                            <input type="text" name="alias" class="form-control" id="alias" placeholder="Alias" value="<?php echo isset($_SESSION['form_data']['alias']) ? h($_SESSION['form_data']['alias']) : null; ?>" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group has-feedback">
                            <label for="alias">Tag</label>
                            <input type="text" name="tags" class="form-control" id="tags" placeholder="tags" value="<?php echo isset($_SESSION['form_data']['tags']) ? h($_SESSION['form_data']['tags']) : null; ?>">
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Добавить</button>
                        <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
                    </div>
                </form>                
            </div>
        </div>
    </div>

</section>
<!-- /.content -->