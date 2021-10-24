<div class="row heder-dilevery">
    <div class="col-md-4 mb-2">
        <div class="dilevery-pr">
            <div>
            <span class="material-icons">local_shipping</span>
            </div>
            <div>
                <div class="bl-tt">Бесплатная доставка</div>
                <div class="bl-pp">МСК от <?= $symbol_left ?><?= \ishop\App::$app->getProperty('price_null_home_delivery'); ?> <?= $symbol_right ?></div>
                <div class="bl-pp">по России от <?= $symbol_left ?><?= \ishop\App::$app->getProperty('price_null_region_delivery'); ?> <?= $symbol_right ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="dilevery-pr">
            <div>
                <span class="material-icons">wifi_protected_setup</span>
            </div>
            <div>
                <div class="bl-tt">Возврат/обмен</div>
                <div class="bl-pp">14 дней</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="dilevery-pr">
            <div>
            <span class="material-icons">transfer_within_a_station</span>                
            </div>
            <div>
                <div class="bl-tt">Примерка</div>
            </div>
        </div>
    </div>
</div>