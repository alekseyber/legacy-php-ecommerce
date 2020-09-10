<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Значение к опции:  <?= $titleGr; ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="<?= ADMIN; ?>/sizes/optionsgrupp-list">Опции</a></li>
        <li class="active"><?= $titleGr; ?> новое значение</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <form action="<?= ADMIN; ?>/sizes/options-add?id=<?= $id; ?>" method="post" data-toggle="validator">
                    <div class="box-body">
                        <div class="form-group has-feedback">
                            <label for="title">Значение</label>
                            <input type="text" name="value" class="form-control" id="title" placeholder="Значение" value="<?php echo isset($_SESSION['form_data']['title']) ? h($_SESSION['form_data']['title']) : null; ?>" required>
                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                        </div>
                        <div class="form-group">
                            <label for="sortvalue">Добавить к цене</label>
                            <input type="text" name="price_add" class="form-control" id="sortvalue" placeholder="Введите число" value="<?php echo isset($_SESSION['form_data']['sortvalue']) ? h($_SESSION['form_data']['sortvalue']) : null; ?>">
                        </div>
                        <div class="form-group">
                            <label for="sortvalue">Сортировка</label>
                            <input type="text" name="sortvalue" class="form-control" id="sortvalue" placeholder="Введите число" value="<?php echo isset($_SESSION['form_data']['sortvalue']) ? h($_SESSION['form_data']['sortvalue']) : null; ?>">
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