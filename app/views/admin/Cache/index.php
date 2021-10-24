<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Очистка кэша
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li class="active">Очистка кэша</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">    
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="mb-1 mt-1">
                        <a href="<?= ADMIN; ?>/cache/clearimgs" class="btn btn-danger delete">Удалить не исп. фото товаров</a>
                    </div>
                    <!-- <div class="mb-1 mt-1">
                        <a href="<?= ADMIN; ?>/cache/updatewebp" class="btn btn-warning delete">Обновить webp изображения</a>
                    </div> -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Описание</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- <tr>
                                    <td>Кэш категорий</td>
                                    <td>Меню категорий на сайте.</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=category"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Кэш фильтров</td>
                                    <td>Кэш фильтров и групп фильтров.</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=filter"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Ids всех продуктов</td>
                                    <td>Все ид продуктов</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=product_ids"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Таблица getparams</td>
                                    <td>REQUEST параметры с используемые на сайте с паттернами</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=getparams"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>sortValue сортировка</td>
                                    <td>class Sort sort_value Результат SQL</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=sort"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>top_slider</td>
                                    <td>КЭШ слайдера виджет</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=top_slider"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>maincontent</td>
                                    <td>Контент на главной странице</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=maincontent"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Таблица brand</td>
                                    <td>Таблица brand данные для карточки товара</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=brand"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Таблица sizegroup</td>
                                    <td>Таблица sizegroup данные для карточки товара</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=sizegroup"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>Таблица promo</td>
                                    <td>Таблица promo</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=promo"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>recomacces</td>
                                    <td>Контент виджета recomacces</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=recomacces"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                                <tr>
                                    <td>product_content</td>
                                    <td>Контент дескрипшен продукта</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=product_content"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr> -->
                                <tr>
                                    <td>Весь КЭШ</td>
                                    <td>Оистить cache</td>
                                    <td><a class="delete-cache" href="<?= ADMIN; ?>/cache/delete?key=all_cache"><i class="fa fa-fw fa-close text-danger"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php debug ($keys) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- /.content -->