<div class="content-wr" data-selector="app-in-load-el" data-mode-status="jsform">
    <div class="container">
        <nav class="nav-breadcrumb">
            <ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a class="js-link-true2" itemprop="item" href="/">
                        <span itemprop="name" class="material-icons">home</span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>
                <li class="breadcrumb-item active" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a class="disabled" itemprop="item" href="<?= \ishop\Link::getLink('vozvrat'); ?>">
                        <span itemprop="name">Возврат/обмен</span></a>
                    <meta itemprop="position" content="2">
                </li>
            </ol>
            <a href="javascript:history.back();" class="breadcrumbs-back">
                <span class="material-icons me-1">arrow_back</span>Назад
            </a>
        </nav>
        <div class="sec-products sec">
            <header class='sec-products-header'>
                <h1 class="sec-title"><?= $contData['htitle']; ?></h1>
                <div class="btn-group sort-panel">
                    <button class="btn btn-raised btn-secondary me-3 btn-cat-menu" type="button" data-toggle="drawer" data-target="#dw-p1">
                        Меню
                    </button>
                </div>
            </header>
            <div class="sec-content sec card">
                <div class="card-body">
                    <?php if ($form) : ?>                   
                        <h2 class="h4">Отправить заявку на обмен/возврат</h2>
                        <div class="row mb-4">
                            <div class="col-md-6" data-selector="js-form" data-name="vozvrat" data-re="true">
                                <form class="needs-validation" method="post" action="<?= \ishop\Link::getLink('vozvrat'); ?>" novalidate>
                                    <?php if ($msgerror) : ?>
                                        <p class="invalid-phone"><?= $msgerror; ?></p>
                                    <?php endif; ?>
                                    <p><strong>Введите, пожалуйста, номер телефона, указанный в заказе:</strong></p>
                                    <div class="form-group">
                                        <label for="vozvrat-phone" class="bmd-label-floating">Телефон*</label>
                                        <input 
                                            type="tel" 
                                            name="phone"                            
                                            id="vozvrat-phone"
                                            data-selector="textinput"
                                            class="form-control"
                                            value="<?php echo isset($_SESSION['form_data']['phone']) ? h($_SESSION['form_data']['phone']) : ''; ?>"
                                            pattern="^((\+?7|8)[ \-] ?)?((\(\d{3}\))|(\d{3}))?([ \-])?(\d{3}[\- ]?\d{2}[\- ]?\d{2})$"
                                            required
                                            inputmode="tel"
                                            >
                                        <div class="invalid-feedback">
                                            Введите, пожалуйста, телефон!
                                        </div>
                                    </div>
                                    <div class="mt-4 mb-2">
                                        <?php $status = $_SESSION['form_data']['status'] ?? 0; ?>
                                        <?php $arr = [0 => 'Обмен', 1 => 'Возврат']; ?>
                                        <?php
                                        foreach ($arr as $k => $v) :
                                            $checked = ($status == $k) ? ' checked' : '';
                                            ?>
                                            <div class="form-check">                                                
                                                <input class="form-check-input" type="radio" name="status" value="<?= $k; ?>"<?= $checked; ?> id="vozvrat-status<?= $k; ?>">
                                                <label class="form-check-label" for="vozvrat-status<?= $k; ?>">
                                                    <?= $v; ?>
                                                </label>
                                            </div>                                               
                                        <?php endforeach; ?>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-raised mt-3">Отправить</button>
                                    </div>
                                    <?php if (isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>  
                                </form>                                
                            </div>
                        </div>                                              
                        <p>В соответствии с частью 4 статьи 26.1 Федерального закона «О защите прав потребителей» Вы имеете право в течение 14-ти календарных дней после получения заказа вернуть или обменять новую, не ношенную, не утратившую товарный вид, обувь, без объяснения причин.</p>
                        <p>При отказе потребителя от товара продавец должен возвратить ему денежную сумму, уплаченную потребителем по договору, за исключением расходов продавца на доставку от потребителя возвращенного товара, которые по городам Москва и Санкт-Петербург составляют – 500 рублей, в других городах России от 500 рублей в зависимости от удаленности региона по расценкам транспортной компании СДЭК или любой другой транспортной компании на выбор клиента.</p>
                        <h3 class="h5">Возврат денежных средств за товар осуществляется:</h3>
                        <ul>
                            <li>В Москве наличными курьером при заборе заказа;</li>
                            <li>В других городах на карту любого банка физического лица, указанную клиентом, не позднее одного рабочего дня, с момента получения товара обратно в интернет-магазин.</li>
                        </ul>
                        <p>Для оформления заказа, просим заполнить форму, размещенную выше, либо позвонить по телефону: <strong><a href="tel:<?= $shop_tel_link; ?>"><?= $shop_tel; ?></a></strong></p>
                    <?php else : ?>
                        <?= $msg; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>