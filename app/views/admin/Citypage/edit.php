<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Редактирование страницы города <?= $citypage->city_name; ?>        
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= ADMIN; ?>"><i class="fa fa-dashboard"></i> Главная</a></li>       
        <li><a href="<?= ADMIN; ?>/citypage">Список страниц</a></li>        
        <li class="active"><?= $citypage->city_name; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12"> 
            <form class="js-form-edit-product" action="<?= ADMIN; ?>/citypage/edit" method="post" data-toggle="validator">
                <input type="hidden" name="id" value="<?= $citypage->id; ?>">
                <input type="hidden" name="alias" value="<?= $citypage->alias; ?>">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Основные данные</h3>
                        <div class="box-tools pull-right">
                            <a class="btn btn-info" href="/<?= $citypage->seolink; ?>" target="_blank">Открыть на сайте</a>
                            <button type="submit" class="btn btn-success">Сохранить</button>                           
                        </div>              
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group has-feedback">
                                    <label for="title">Заголовок</label>
                                    <input type="text" name="title" class="form-control" id="title" placeholder="Заголовок" value="<?= h($citypage->title); ?>" required>
                                    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="city_name">Город в именительном</label>
                                <input type="text" name="city_name" class="form-control" id="city_name" placeholder="Город" value="<?= h($citypage->city_name); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <?php $arr = ['Выключен', 'Включен']; ?>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Выводить страницу</label>
                                    <select class="form-control" name="status" id="status"> 
                                        <?php
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($citypage->status == $k):
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
                                    <label for="content_on">Выводить контент</label>
                                    <select class="form-control" name="content_on" id="content_on"> 
                                        <?php
                                        foreach ($arr as $k => $v):
                                            $selected = '';
                                            if ($citypage->status == $k):
                                                $selected = ' selected';
                                                ?>
                                            <?php endif; ?>
                                            <option value="<?= $k; ?>"<?= $selected; ?>><?= $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>                                            
                                </div>	
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Seo link</label>
                                    <input type="hidden" name="seolink" value="<?= $citypage->seolink; ?>">
                                    <input type="text" id="input-seolink" class="form-control" data-table="citypage" data-seolink="<?= $citypage->seolink; ?>" data-action="<?= ADMIN; ?>/ajax/editseolink" value="<?= $citypage->seolink; ?>">
                                    <button type="button" class="btn btn-primary edit-seolink">Сохранить Seolink</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city_id_selector">Введите город для расчета</label>
                                    <input type="text" class="form-control typeahead" placeholder="Введите город..." id="city_id_selector" autocomplete="off" value="<?= $citypage->city_name; ?>">
                                    <input type="hidden" name="city_id" id="city_id" value="<?= $citypage->city_id; ?>" >
                                </div>  
                            </div>   
                        </div>
                        <div class="row mt-3 mb-3">
                            <div class="col-md-6">
                                <h4 class="font-bold">Шаблоны для замены в тексте, титле и мета:</h4>
                                <ul>
                                    <li>@shop_name@ - название магазина</li>
                                    <li>@shop_tel@ - телефон магазина</li>
                                    <li>@shop_tel_link@ - телефон магазина для ссылки href</li> 
                                    <li>@shop_adress@ - адрес магазина</li>
                                    <li>@shop_email@ - email магазина</li>
                                    <li>@shop_work@ - время работы</li>
                                    <li>@city_name@ - город в именительном Красноярск</li>
                                    <li>@city_bname@ - город в родительном Красноярска</li>
                                    <li>@city_cname@ - город в дательном Красноярску</li>
                                    <li>@city_dname@ - город в творительном Красноярском</li>
                                    <li>@city_iname@ - город в предложном Красноярске</li>
                                    <li>@deliveryPeriodMin@ - минимальный срок доставки дней, в этот город</li>
                                    <li>@deliveryPeriodMax@ - максимальный срок доставки дней, в этот город</li>
                                    <li>@deliveryPriceValue@ - стоимость доставки только цифра, в этот город</li>
                                    <li>@deliveryPriceCurrency@ - стоимость доставки с валютой, в этот город</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city_b">Город в родительном</label>
                                    <input type="text" name="city_bname" class="form-control" id="city_bname" placeholder="Город" value="<?= h($citypage->city_bname); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="city_cname">Город в дательном</label>
                                    <input type="text" name="city_cname" class="form-control" id="city_cname" placeholder="Город" value="<?= h($citypage->city_cname); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="city_dname">Город в творительном</label>
                                    <input type="text" name="city_dname" class="form-control" id="city_dname" placeholder="Город" value="<?= h($citypage->city_dname); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="city_iname">Город в предложном</label>
                                    <input type="text" name="city_iname" class="form-control" id="city_i" placeholder="Город" value="<?= h($citypage->city_iname); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="content">Контент, особенности</label>
                            <textarea name="content" class="form-control editorfield" id="content" rows="2" placeholder="Enter ..."><?= $citypage->content; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Промо текст</label>
                            <textarea name="promo" class="form-control" rows="2" placeholder="Enter ..."><?= $citypage->promo; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="box box-widget collapsed-box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Мета теги</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body"> 
                        <div class="form-group">
                            <label for="metatitle">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" id="metatitle" placeholder="Meta Title" value="<?= h($citypage->meta_title); ?>">
                        </div>
                        <div class="form-group">
                            <label for="description">Описание</label>
                            <input type="text" name="meta_description" class="form-control" id="description" placeholder="Описание" value="<?= h($citypage->meta_description); ?>">
                        </div>
                        <div class="form-group">
                            <label for="keywords">Ключевые слова</label>
                            <input type="text" name="meta_keywords" class="form-control" id="keywords" placeholder="Ключевые слова" value="<?= h($citypage->meta_keywords); ?>">
                        </div>
                    </div><!-- box-body -->
                </div><!--box box-widget-->
            </form>
        </div>
    </div>
</section>
<!-- /.content -->