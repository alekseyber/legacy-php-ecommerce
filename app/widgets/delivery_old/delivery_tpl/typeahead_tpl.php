<div class="input-city-wrap">
    <div class="city-title">
        <div class="h4">Укажите название города</div>
        <p>для того, чтобы подробнее узнать, о стоимости и сроках доставки</p>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="input-group city-search">
                <input type="text" class="form-control typeahead" id="<?= $idcity; ?>" autocomplete="off" value="<?= $res['geo_city']; ?>">
                <button type="button" id="<?= $idcity; ?>close" class="close close-city">
                    <span>×</span>
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="js-inf-divel-wrap inf-divel-wrap" id="<?= $iddiv; ?>">
    <?= $inf_html; ?>
</div>
