<div class="input-city-wrap" <?= $infInSelector; ?>>
    <div class="dilevery-content"<?= $dataWIdjet ?>>
        <?php if ($res['chek']) : ?>
            <?php
            $pvzSelected = isset($_SESSION['delivery_pvz']);
            $curChecked = $pvzSelected ? '' : ' checked ';
            $pvzChecked = !$pvzSelected ? '' : ' checked ';
            ?>
            <?php if (!$removePvz) : ?>
                <div class="nav flex-column mb-3">
                    <div class="city-title">
                        <div class="h4 mb-3">2. Выерите способ доставки</div>
                    </div>
                    <div class="form-check">
                        <input 
                            class="form-check-input" 
                            type="radio" 
                            name="delivmethod" 
                            id="dilevery-cur-tab<?= $modal; ?>" 
                            value="cur"
                            <?= $curChecked ?>
                            data-bs-toggle="tab-deliv"
                            data-bs-target="#dilevery-cur<?= $modal; ?>"
                            >
                        <label class="form-check-label" for="dilevery-cur-tab<?= $modal; ?>">
                            Доставить курьером
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input"
                               type="radio"
                               name="delivmethod"
                               id="dilevery-pvz-tab<?= $modal; ?>"
                               value="pvz"
                               <?= $pvzChecked ?>
                               data-bs-toggle="tab-deliv"
                               disabled
                               data-bs-target="#dilevery-pvz<?= $modal; ?>"
                               >
                        <label class="form-check-label" for="dilevery-pvz-tab<?= $modal; ?>">
                            Получить в пункте выдачи
                        </label>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="tab-content" id="dileveryTabContent<?= $modal; ?>">
            <div class="tab-pane fade show active" id="dilevery-cur<?= $modal; ?>">
                <div class="divel-home-cont">
                    <?php if ($res['receiverCityId'] == 44) : ?> 
                        <div class="divel-col">
                            <div class="divel-col-heder">Срок доставки</div>
                            <div class="divel-col-cont">
                                <div class="div-bl">
                                    <div class="divel-act"><?= $res['geo_city']; ?></div>
                                    <div class="divel-act">Подтверждение заказа:</div>
                                    <div>до <?= $res['divel_hour_podtv']; ?> часов</div>
                                    <div class="dat-wrap" data-toggleuser="true" data-title="Даты указаны без учета праздничных дней"><?= $res['deliveryDateMin']; ?></div>
                                </div>
                                <div class="div-bl">
                                    <div>после <?= $res['divel_hour_podtv']; ?> часов</div>
                                    <div class="dat-wrap" data-toggleuser="true" data-title="Даты указаны без учета праздничных дней"><?= $res['deliveryDateMax']; ?></div>
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
                                    <div data-toggleuser="true" data-title="При покупке от <?= $symbol_left ?>8000<?= $symbol_right ?>. До <?= $symbol_left ?>8000<?= $symbol_right ?> или отказе - <?= $symbol_left ?>300<?= $symbol_right ?>">В пределах МКАД - 0 <?= $symbol_right ?> <span class="rrr">*</span></div>
                                </div>
                                <div class="div-bl">
                                    <div>До 5 км от МКАД - <?= $symbol_left ?>500 <?= $symbol_right ?>, далее <?= $symbol_left ?>10<?= $symbol_right ?>/км </div>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <?php if ($res['chek']) : ?>
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
                                        <div class="dat-wrap" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $tttinf; ?>"><?= $res['deliveryDateMin']; ?> - <?= $res['deliveryDateMax']; ?><?= $span; ?></div>
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
                                        <div>В будний день - <span class="fw-bold"><?= $symbol_left ?><?= $res['price']; ?> <?= $symbol_right ?></span></div>
                                    </div>
                                    <div class="div-bl">
                                        <div>В выходной день - <span class="fw-bold"><?= $symbol_left ?><?= round($res['price'] * 1.3); ?> <?= $symbol_right ?></span></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?> 
                        <?php if ($res['allert']) : ?>
                            <div class="alert alert-warning"><?= $res['allert']; ?></div>
                        <?php endif; ?>
                    <?php endif; ?> 
                </div>
            </div>
            <?php if (!$removePvz) : ?>
                <div class="tab-pane fade" id="dilevery-pvz<?= $modal; ?>">
                    <?php if ($res['chek']) : ?>
                        <div class="divel-home-cont">
                            <div class="divel-col">
                                <div class="divel-col-heder">Срок доставки</div>
                                <div class="divel-col-cont">
                                    <div class="div-bl">
                                        <div class="divel-act">Интервал даты доставки:</div>
                                    </div>
                                    <div class="div-bl">
                                        <div class="dat-wrap" data-toggleuser="true" data-title="Даты указаны без учета праздничных дней"><?= $res['deliveryDateMin']; ?> - <?= $res['deliveryDateMax']; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="divel-col">
                                <div class="divel-col-heder">Получить в ПВЗ</div>
                                <div class="divel-col-cont">
                                    <div class="div-bl" data-selector="selectedpvz">                                    
                                        <?php if (isset($_SESSION['delivery_pvz'])) : ?>
                                            <?php $delivery_pvz = unserialize($_SESSION['delivery_pvz']); ?>
                                            <div class="h6 fw-bold">Выбран ПВЗ:</div>
                                            <div>Город: <span class="fw-bold"><?= h($delivery_pvz['cityName']); ?></span></div>
                                            <div>Код: <span class="fw-bold"><?= h($delivery_pvz['id']); ?></span></div>
                                            <div>Адрес: <span class="fw-bold"><?= h($delivery_pvz['adress']); ?></span></div>
                                            <div>Время работы: <span class="fw-bold"><?= h($delivery_pvz['workTime']); ?></span></div>
                                            <div>В момент поступления заказа в ПВЗ, поступит SMS уведомление.</div>
                                            <div>Для изменения выберите на карте новый ПВЗ или доставку курьером.</div>
                                        <?php else : ?>
                                            <div class="h6 fw-bold">Выберите ПВЗ на карте</div>                                                                                   
                                        <?php endif; ?>                                     
                                    </div>
                                </div>
                            </div>
                            <div class="divel-col">
                                <div class="divel-col-heder">Стоимость доставки</div>
                                <div class="divel-col-cont">
                                    <div class="div-bl fw-bold" data-selector="pricepvz"></div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?> 
                    <div id="forpvz<?= $modal; ?>" style="height:400px;"></div>
                </div>
            <?php endif; ?> 
        </div>
        <div class="row divel-fo">
            <?php if ($res['receiverCityId'] == 44) : ?>            
                <div class="col-sm-4" data-toggleuser="true" data-title="Примерка при получении.">
                    <span class="divel-fo-i"><span class="material-icons">transfer_within_a_station</span></span>
                    <span class="divel-fo-ttt">Примерка</span>
                </div>
                <div class="col-sm-4" data-toggleuser="true" data-title="Частичный выкуп: При покупке одной пары - дополнительная пара на примерку - бесплатно. Далее 100<?= $symbol_right ?> - пара на примерку, не более 4-х.">
                    <span class="divel-fo-i"><span class="material-icons">remove_shopping_cart</span></span>
                    <span class="divel-fo-ttt">Частичный выкуп<span class="rrr">*</span></span>
                </div>
                <div class="col-sm-4" data-toggleuser="true" data-title="Предоплата в Москве не предусмотрена.">
                    <span class="divel-fo-i no-active"><span class="material-icons">payment</span></span>
                    <span class="divel-fo-ttt">Предоплата</span>
                </div>
            </div>
        <?php else : ?>
            <div class="col-sm-4" data-toggleuser="true" data-title="Примерка при получении.">
                <span class="divel-fo-i"><span class="material-icons">transfer_within_a_station</span></span>
                <span class="divel-fo-ttt">Примерка</span>
            </div>
            <div class="col-sm-4" data-toggleuser="true" data-title="Частичный выкуп: не предусмотрено.">
                <span class="divel-fo-i no-active"><span class="material-icons">remove_shopping_cart</span></span>
                <span class="divel-fo-ttt">Частичный выкуп</span>
            </div>
            <?php if ($res['price'] > 900) : ?>
                <div class="col-sm-4" data-toggleuser="true" data-title="Предоплата в размере стоимости доставки.">
                    <span class="divel-fo-i"><span class="material-icons">payment</span></span>
                    <span class="divel-fo-ttt">Предоплата</span>
                </div>
            <?php else : ?>
                <div class="col-sm-4" data-toggleuser="true" data-title="В случае предоплаты - доставка до ПВЗ - бесплатно.">
                    <span class="divel-fo-i no-active"><span class="material-icons">payment</span></span>
                    <span class="divel-fo-ttt">Предоплата (не обязательно)</span>
                </div>
            <?php endif; ?>
        <?php endif; ?> 
    </div>
</div>
