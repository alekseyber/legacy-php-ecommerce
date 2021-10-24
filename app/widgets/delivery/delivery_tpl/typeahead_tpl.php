<div class="input-city-wrap">
    <div class="city-title">
        <div class="h4">1. Укажите название города</div>
        <p>
            для того, чтобы подробнее узнать, о стоимости и сроках
            доставки
        </p>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div
                class="dropdown"
                data-selector="app-typeahead"
                data-bs-source="city"
                >
                <div class="form-group" data-bs-toggle="dropdown-t">
                    <label for="city<?= $modal; ?>" class="bmd-label-floating"
                           >Город</label
                    >
                    <div class="input-group reset-btn show-reset">
                        <input
                            type="text"
                            class="form-control"
                            id="city<?= $modal; ?>"
                            data-selector="textinput"
                            autocomplete="new-password"
                            value="<?= $res['geo_city']; ?>"
                            />
                        <button
                            class="btn-close"
                            data-selector="app-typeahead-clear"
                            type="button"
                            >
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <span class="bmd-help"></span>
                </div>
                <ul class="dropdown-menu"></ul>
            </div>
        </div>
        <div class="col-md-4"></div>
        <div class="preloader-dilev" <?= $preloaderSelector; ?>>
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-info">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div> 
    </div>
</div>
<div class="inf-divel-wrap" <?= $infSelector ?>><?= $inf_html; ?></div>