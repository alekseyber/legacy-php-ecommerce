<div class="preloader-dilev" id='dilevery-preloader<?= $modal; ?>'>
    <div class="d-flex justify-content-center">
        <div class="spinner-border text-info">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
</div>
<div class="dilevery-conten">
    <?php if ($res['chek']) : ?>
        <ul class="nav nav-pills nav-fill nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="dilevery-cur-tab<?= $modal; ?>" data-toggle="tab" href="#dilevery-cur<?= $modal; ?>">Доставка курьером</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" id="dilevery-pvz-tab<?= $modal; ?>" data-toggle="tab" href="#dilevery-pvz<?= $modal; ?>">Получение в ПВЗ</a>
            </li>
        </ul>
        <div class="tab-content" id="dileveryTabContent<?= $modal; ?>">
            <div class="tab-pane fade show active" id="dilevery-cur<?= $modal; ?>">
                <div class="divel-home-cont">
                    <div class="divel-col">
                        <div class="divel-col-heder">Срок доставки</div>
                        <div class="divel-col-cont">
                            <div class="div-bl">
                                <div class="divel-act">Интервал даты доставки:</div>
                            </div>
                            <?php
                            if ($res['deliveryPeriodMax'] > 9) {
                                $tttinf = 'Для ускорения доставки можем предложить более дорогой тариф';
                                $span = '<span class="rrr">*</span>';
                            } else {
                                $tttinf = 'Даты указаны без учета праздничных дней';
                                $span = '';
                            }
                            ?>
                            <div class="div-bl">
                                <div class="dat-wrap" data-toggle="tooltip" data-placement="top" title="<?= $tttinf; ?>"><?= $res['deliveryDateMin']; ?> - <?= $res['deliveryDateMax']; ?><?= $span; ?></div>
                            </div>
                            <div class="div-bl">
                                <div>Курьер позвонит перед доставкой.</div>
                            </div>
                        </div>
                    </div>
                    <div class="divel-col">
                        <div class="divel-col-heder">Доставка осуществляется</div>
                        <div class="divel-col-cont">
                            <div class="div-bl">
                                <div class="divel-act">Доставку в <?= $res['geo_city']; ?> осуществляет транспортная компания СДЭК</div>
                                <div>Будни с 10 до 20</div>
                                <div>Суббота с 11 до 18</div>
                            </div>
                        </div>
                    </div>
                    <div class="divel-col">
                        <div class="divel-col-heder">Стоимость доставки</div>
                        <div class="divel-col-cont">
                            <div class="div-bl">
                                <div>В будний день - <?= $symbol_left ?><?= $res['price']; ?> <?= $symbol_right ?></div>
                            </div>
                            <div class="div-bl">
                                <div>В выходной день - <?= $symbol_left ?><?= round($res['price'] * 1.3); ?> <?= $symbol_right ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($res['allert']) : ?>
                    <div class="alert alert-warning"><?= $res['allert']; ?></div>
                <?php endif; ?>
            </div>
            <div class="tab-pane fade" id="dilevery-pvz<?= $modal; ?>">
                <div class="divel-home-cont">
                    <div class="divel-col">
                        <div class="divel-col-heder">Срок доставки</div>
                        <div class="divel-col-cont">
                            <div class="div-bl">
                                <div class="divel-act">Интервал даты доставки:</div>
                            </div>
                            <div class="div-bl">
                                <div class="dat-wrap" data-toggle="tooltip" data-placement="top" title="Даты указаны без учета праздничных дней"><?= $res['deliveryDateMin']; ?> - <?= $res['deliveryDateMax']; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="divel-col">
                        <div class="divel-col-heder">Получение в ПВЗ</div>
                        <div class="divel-col-cont">
                            <div class="div-bl">
                                <?php if (isset($_SESSION['delivery_pvz'])) : ?>
                                    <div class="divel-act actionselectpvz">Ранее выбран ПВЗ:</div>
                                    <?php $delivery_pvz = unserialize($_SESSION['delivery_pvz']); ?>
                                    <div class="pvzadressselected">
                                        <div>Город: <?= h($delivery_pvz['cityName']); ?></div>
                                        <div>Код: <?= h($delivery_pvz['id']); ?></div>
                                        <div>Адрес: <?= h($delivery_pvz['adress']); ?></div>
                                        <div>Время работы: <?= h($delivery_pvz['workTime']); ?></div>
                                        <div>В момент поступления заказа в ПВЗ, Вам поступит SMS уведомление.</div>
                                        <div>Для изменения выберите на карте новый ПВЗ.</div>
                                    </div>

                                <?php else : ?>
                                    <div class="divel-act actionselectpvz">Выберите на карте ПВЗ</div>
                                    <div class="pvzadressselected"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="divel-col">
                        <div class="divel-col-heder">Стоимость доставки</div>
                        <div class="divel-col-cont">
                            <div class="div-bl">
                                <div class="pricepvz"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <script id="ISDEKscript" src="/js/cdek/widjet.js"></script>
                <script>
                    var widjet1 = new ISDEKWidjet({

                        showWarns: false,
                        showErrors: false,
                        showLogs: false,
                        hideMessages: true,
                        defaultCity: '<?= $res['geo_city']; ?>',
                        cityFrom: 'Москва',
                        country: 'Россия',
                        choose: true,
                        link: 'forpvz1',
                        hidedress: false,
                        hidecash: false,
                        hidedelt: true,
                        goods: [{
                            length: 38,
                            width: 32,
                            height: 14,
                            weight: 2
                        }],
                        onReady: function() {

                            widjet1.city.set(<?= $res['receiverCityId']; ?>);
                            ipjq('#dilevery-pvz-tab<?= $modal; ?>').removeClass('disabled');
                        },
                        onChoose: onChoose,
                        onCalculate: onCalculate
                    });

                    function onChoose(wat) {
                        //  console.log('chosen', wat);
                        if (wat.price > 0) {
                            pr = Number(wat.price) + Number(<?= $res['divel_add']; ?>);
                            ipjq('.actionselectpvz').html("Выбран ПВЗ:");
                            ipjq('.pvzadressselected').html("<div>Город: " + wat.cityName + "</div><div>Код: " + wat.id + "</div><div>Адрес: " + wat.PVZ.Address + "</div><div>Время работы: " + wat.PVZ.WorkTime + "</div><div>В момент поступления заказа в ПВЗ, Вам поступит SMS уведомление.</div>");
                            ipjq('.pricepvz').html(pr + " <?= $symbol_right ?>");
                            serviceMess(
                                'Выбран пункт выдачи заказа ' + wat.id + "\n<br/>" +
                                'город ' + wat.cityName
                            );

                            var respvz = {
                                id: wat.id,
                                tarif: wat.tarif,
                                city: wat.city,
                                cityName: wat.cityName,
                                price: pr,
                                PVZName: wat.PVZ.Name,
                                adress: wat.PVZ.Address,
                                Phone: wat.PVZ.Phone,
                                workTime: wat.PVZ.WorkTime,
                            }
                            var respvzStr = JSON.stringify(respvz);
                            if (respvzStr) {
                                ipjq.ajax({
                                    url: path + '/delivery/setpvz',
                                    data: {
                                        dann: respvzStr
                                    },
                                    type: 'POST',
                                    success: function() {
                                        if (window.delifinfUpdate) {
                                            window.delifinfUpdate();
                                        }
                                        //console.log('chosen', res);
                                    }
                                });
                            }
                        }
                    }

                    var nullPrice = <?= $res['null_price']; ?>;

                    function onCalculate(wat) {
                        //console.log('calculated', wat);

                        if (nullPrice) {
                            pr = 0;
                        } else {
                            pr = Number(wat.profiles.pickup.price) + Number(<?= $res['divel_add']; ?>);
                        }

                        ipjq('.pricepvz').html(pr + " <?= $symbol_right ?>");
                    }

                    window.servmTimeout = false;
                    serviceMess = function(text) {
                        clearTimeout(window.servmTimeout);
                        ipjq('#service_message').show().html(text);
                        window.servmTimeout = setTimeout(function() {
                            ipjq('#service_message').fadeOut(1000);
                        }, 4000);
                    }
                </script>
                <div id="forpvz1" style="height:400px;"></div>
                <div id="service_message1"></div>
            </div> <!-- dilevery-pilevery-pvz-->
        </div>
        <div class="row divel-fo">
            <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="Примерка при получении.">
                <span class="divel-fo-i"><span class="material-icons">transfer_within_a_station</span></span>
                <span class="divel-fo-ttt">Примерка</span>
            </div>
            <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="Частичный выкуп: не предусмотрено.">
                <span class="divel-fo-i no-active"><span class="material-icons">remove_shopping_cart</span></span>
                <span class="divel-fo-ttt">Частичный выкуп</span>
            </div>
            <?php if ($res['price'] > 900) : ?>
                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="Требуется предоплата в размере стоимости доставки.">
                    <span class="divel-fo-i"><span class="material-icons">payment</span></span>
                    <span class="divel-fo-ttt">Предоплата</span>
                </div>
            <?php else : ?>
                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="По желанию клиента допускается предоплата. После предоплаты - доставка до ПВЗ - бесплатно.">
                    <span class="divel-fo-i no-active"><span class="material-icons">payment</span></span>
                    <span class="divel-fo-ttt">Предоплата (не обязательно)</span>
                </div>
            <?php endif; ?>
        </div>
    <?php else : ?>
        <?php if ($res['receiverCityId'] == 44) : ?>
            <ul class="nav nav-pills nav-fill nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="dilevery-cur-tab" data-toggle="tab" href="#dilevery-cur">Доставка курьером</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" id="dilevery-pvz-tab" data-toggle="tab" href="#dilevery-pvz">Получение в ПВЗ</a>
                </li>
            </ul>
            <div class="tab-content" id="dileveryTabContent<?= $modal; ?>">
                <div class="tab-pane fade show active" id="dilevery-cur<?= $modal; ?>">
                    <div class="divel-home-cont">
                        <div class="divel-col">
                            <div class="divel-col-heder">Срок доставки</div>
                            <div class="divel-col-cont">
                                <div class="div-bl">
                                    <div class="divel-act"><?= $res['geo_city']; ?></div>
                                    <div class="divel-act">Подтверждение заказа:</div>
                                    <div>до <?= $res['divel_hour_podtv']; ?> часов</div>
                                    <div class="dat-wrap" data-toggle="tooltip" data-placement="top" title="Даты указаны без учета праздничных дней"><?= $res['deliveryDateMin']; ?></div>
                                </div>
                                <div class="div-bl">
                                    <div>после <?= $res['divel_hour_podtv']; ?> часов</div>
                                    <div class="dat-wrap" data-toggle="tooltip" data-placement="top" title="Даты указаны без учета праздничных дней"><?= $res['deliveryDateMax']; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="divel-col">
                            <div class="divel-col-heder">Доставка осуществляется</div>
                            <div class="divel-col-cont">
                                <div class="div-bl">
                                    <div class="divel-act">Доставка в пределах МКАД:</div>
                                    <div>Будни с 10 до 21</div>
                                    <div>Выходные с 11 до 19</div>
                                </div>
                                <div class="div-bl">
                                    <div class="divel-act">Доставка за пределы МКАД до 50 км:</div>
                                    <div>Будни и выходные с 11 до 20</div>
                                </div>
                            </div>
                        </div>
                        <div class="divel-col">
                            <div class="divel-col-heder">Стоимость доставки</div>
                            <div class="divel-col-cont">
                                <div class="div-bl">
                                    <div data-toggle="tooltip" data-placement="top" title="При покупке от <?= $symbol_left ?>8000<?= $symbol_right ?>. До <?= $symbol_left ?>8000<?= $symbol_right ?> или отказе - <?= $symbol_left ?>300<?= $symbol_right ?>">В пределах МКАД - 0 <?= $symbol_right ?> <span class="rrr">*</span></div>
                                </div>
                                <div class="div-bl">
                                    <div>До 5 км от МКАД - <?= $symbol_left ?>500 <?= $symbol_right ?>, далее <?= $symbol_left ?>10<?= $symbol_right ?>/км </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="dilevery-pvz<?= $modal; ?>"></div>
            </div>
            <div class="row divel-fo">
                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="Примерка при получении.">
                    <span class="divel-fo-i"><span class="material-icons">transfer_within_a_station</span></span>
                    <span class="divel-fo-ttt">Примерка</span>
                </div>
                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="Частичный выкуп: При покупке одной пары - дополнительная пара на примерку - бесплатно. Далее 100<?= $symbol_right ?> - пара на примерку, не более 4-х.">
                    <span class="divel-fo-i"><span class="material-icons">remove_shopping_cart</span></span>
                    <span class="divel-fo-ttt">Частичный выкуп<span class="rrr">*</span></span>
                </div>
                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="Предоплата в Москве не предусмотрена.">
                    <span class="divel-fo-i no-active"><span class="material-icons">payment</span></span>
                    <span class="divel-fo-ttt">Предоплата</span>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>