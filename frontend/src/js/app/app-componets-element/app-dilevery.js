//bootstrap component
import Tab from "bootstrap/js/src/tab";

import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import SelectorEngine from "@Dom/selector-engine";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";
import {
  APP_NAME_SPACE,
  EVENT_CHANGE_CITY,
  MODE_STATUS,
  EVENT_AJAX_DATA_LOADED,
  SELECTOR_CONTEINER_DELIV,
  SELECTOR_IN_DELIV_GLOBAL,
  EVENT_RUN_CART_DELINF_UPDATE,
} from "@Constans/index";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
//const Tab = window.bootstrap.Tab;
const NAME = "appdilevery";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

//const EVENT_CLICK = `click${EVENT_KEY}`;

const EVENT_CHANGE = `change${EVENT_KEY}`;

//const CONTEINER_SELECTOR = '[data-selector="app-delivery"]';
const INF_BLOCK_SELECTOR = '[data-selector="app-delivery-inf"]';
const LOADER_SELECTOR = '[data-selector="app-delivery-loader"]';

const PRICE_PVZ_SELECTOR = '[data-selector="pricepvz"]';

const ADRESS_PVZ_SELECTOR = '[data-selector="selectedpvz"]';

const CITY_BTN_TEXT_SELECTOR = '[data-selector="city-btn"]';
const CITY_BTN_TEXT_CART_SELECTOR = '[data-selector="city-btn-cart"]';

const DELIV_TAB_TOGGLE_SELECTOR = '[data-bs-toggle="tab-deliv"]';

const LOADER_SHOW_CLASS = "show";

const URL_START = "/delivery?rezult=1&receiverCityId=";

const URL_SET_PVZ = "/delivery/setpvz";

const CDEK_ID_EL_CANVAS = "forpvz";

const CDEK_METODS_NAME = ["pvz", "cur"];

const CLASS_DISABLED = "disabled";
const CLASS_ACTIVE = "active";

const CDEK_PARAMS_SERVICE_PATH = "/delivery?service";
//const CDEK_PARAMS_SERVICE_PATH = "/js/cdek/scripts/service.php";

const CDEK_SCRIPT_ID_ELEMENT = "ISDEKscript";
const CDEK_SCRIPT_SRC_ELEMENT = "/js/cdek/widjet.min.js";
// path https://widget.cdek.ru/widget/scripts/'
const CDEK_PARAMS_PATH = "/js/cdek/scripts/";
// templatepath: 'https://widget.cdek.ru/widget/scripts/template.php',
const CDEK_PARAMS_TEMPLATE_PATH = "/js/cdek/scripts/template.php";

const getTemplateAdress = (requestData) => {
  if (!requestData)
    return '<div class="h6 fw-bold">Выберите ПВЗ на карте</div>';
  return `
  <div class="h6 fw-bold">Выбран ПВЗ:</div>
  <div>Город: <span class="fw-bold">${requestData.cityName}</span></div>
  <div>Код: <span class="fw-bold">${requestData.id}</span></div>
  <div>Адрес: <span class="fw-bold">${requestData.adress}</span></div>
  <div>Время работы: <span class="fw-bold">${requestData.workTime}</span></div>
  <div>В момент поступления заказа в ПВЗ, поступит SMS уведомление.</div>
  <div>Для изменения выберите на карте новый ПВЗ или доставку курьером.</div>`;
};

//const Y_MAP_API_KEY = "";

const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppDilevery extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      this._activate();
    } catch (e) {
      this._logError(e);
    }
  }

  // Public
  eventSelectCity(event) {
    if (!event.detail.selectedValue) {
      this._setError("Выбранное значение не передано");
    }
    this.selectedValue = event.detail.selectedValue;
    this._request(this._getUrl());
  }
  setLoading(status) {
    const actions = status ? "addClass" : "removeClass";
    Manipulator[actions](this._loader, LOADER_SHOW_CLASS);
  }

  loadData(data) {
    if (!data) return (this._data = null);
    if (!data.chek) return (this._data = null);
    this._data = data;
    this._loadWidjet();
  }

  dispose() {
    super.dispose();
    this._infBlock = null;
    this._loader = null;
    this._topCityBtnText = null;
    this._data = null;
    this._widjet = null;
    this._pvzPriceEl = null;
    if (this._tabs !== null) {
      this._addListners("off");
      this._tabs = null;
    }
  }

  // ------------------------------------------------------------------------
  // private
  // ------------------------------------------------------------------------
  // protected

  async _setPVZ(dataIn, wat) {
    try {
      const priceValue = this._getPriceValue(dataIn, wat.price);
      const requestData = {
        id: wat.id,
        tarif: wat.tarif,
        city: wat.city,
        cityName: wat.cityName,
        price: priceValue.value,
        PVZName: wat.PVZ.Name,
        adress: wat.PVZ.Address,
        Phone: wat.PVZ.Phone,
        workTime: wat.PVZ.WorkTime,
      };

      const dann = JSON.stringify(requestData);

      const data = new FormData();
      data.append("dann", dann);
      const response = await this.fetchApp.post(URL_SET_PVZ, data);

      if (response.succes) {
        EventHandler.trigger(this._element, EVENT_RUN_CART_DELINF_UPDATE);

        if (!this._pvzAdressEl) {
          this._pvzAdressEl = this._getChildElement(
            ADRESS_PVZ_SELECTOR,
            this._element
          );
        }
        this._pvzAdressEl.innerHTML = getTemplateAdress(requestData);
        this._setPriceElement(priceValue);
        this._data.pvzSelected = true;
      }
    } catch (e) {
      this._logError(e);
    }
  }

  async _clearPVZ() {
    try {
      if (!this._data) return;
      if (!this._data.pvzSelected) return;
      const data = new FormData();
      data.append("clearpvz", true);
      const response = await this.fetchApp.post(URL_SET_PVZ, data);
      if (response.succes) {
        EventHandler.trigger(this._element, EVENT_RUN_CART_DELINF_UPDATE);
        this._data.pvzSelected = false;
        if (!this._pvzAdressEl) {
          this._pvzAdressEl = this._getChildElement(
            ADRESS_PVZ_SELECTOR,
            this._element
          );
        }
        this._pvzAdressEl.innerHTML = getTemplateAdress();
      }
    } catch (e) {
      this._logError(e);
    }
  }

  _widjetOnReady(data) {
    // = ['pvz', 'cur'];
    const tabs = {};

    CDEK_METODS_NAME.forEach((key) => {
      const selector = `#dilevery-${key}-tab${data.modal}`;
      const tab = this._getChildElement(selector, this._element);
      tabs[key] = tab;
    });
    this._tabs = tabs;

    this._tabs["pvz"].removeAttribute(CLASS_DISABLED);

    if (this._tabs["pvz"].checked) {
      this._delivTabToggleHangler(null, this._tabs["pvz"]);
    }
    this._addListners();
  }

  _delivTabToggleHangler(event, el) {
    if (this._tabs === null) return;
    const element = event ? event.target : el;

    const value = element.value;
    const checked = element.checked;

    const tab = new Tab(element) || Tab.getInstance(element);

    if (tab) {
      tab.show();
      const key = value === "pvz" ? "cur" : "pvz";
      Manipulator.removeClass(this._tabs[key], CLASS_ACTIVE);
    }

    if (event !== null && checked && value === "cur") {
      this._clearPVZ();
    }
  }

  _addListners(action = "on") {
    EventHandler[action](
      this._element,
      EVENT_CHANGE,
      DELIV_TAB_TOGGLE_SELECTOR,
      this._delivTabToggleHangler.bind(this)
    );
  }

  _widjetOnChoose(data, wat) {
    this._setPVZ(data, wat);
  }

  _getPriceValue(data, priceInValue) {
    const priceValue = data.nullPrice
      ? 0
      : Number(priceInValue) + Number(data.dstValue);

    const symbLeft = window.symbLeft ? window.symbLeft : "";
    const symbRight = window.symbRight ? window.symbRight : "руб.";

    const rez = {
      value: 0,
      text: "",
      err: false,
    };

    if (isNaN(priceValue)) {
      rez.err = true;
      console.log("OnCalculate rezult not number!");
      return rez;
    }

    rez.text = symbLeft + priceValue + " " + symbRight;
    rez.value = priceValue;

    return rez;
  }

  _setPriceElement(priceRez) {
    if (priceRez.err) return;
    if (!this._pvzPriceEl) {
      this._pvzPriceEl = this._getChildElement(
        PRICE_PVZ_SELECTOR,
        this._element,
        false
      );
    }
    if (!this._pvzPriceEl)
      return console.log(`Price element ${PRICE_PVZ_SELECTOR} - не найден`);
    this._pvzPriceEl.innerText = priceRez.text;
  }

  _widjetOnCalculate(data, wat) {
    const priceRez = this._getPriceValue(data, wat.profiles.pickup.price);
    this._setPriceElement(priceRez);
  }

  _activateWidjet() {
    try {
      if (!this._data) return;
      if (!this._data.chek) return;

      const data = this._data;
      data.modal = data.modal ? "1" : "";

      const config = {
        showWarns: false,
        showErrors: false,
        showLogs: false,
        hideMessages: true,
        path: CDEK_PARAMS_PATH,
        servicepath: CDEK_PARAMS_SERVICE_PATH,
        templatepath: CDEK_PARAMS_TEMPLATE_PATH,
        defaultCity: data.receiverCityId,
        cityFrom: data.cityFrom,
        country: data.country,
        choose: true,
        link: CDEK_ID_EL_CANVAS + data.modal,
        hidedress: false,
        hidecash: false,
        hidedelt: true,
        apikey: '', // аудит 2026-11: ключ виджета СДЭК вырезан из публичной версии,
        goods: data.goods,
        onReady: this._widjetOnReady.bind(this, data),
        onChoose: this._widjetOnChoose.bind(this, data),
        onCalculate: this._widjetOnCalculate.bind(this, data),
      };

      this._widjet = new window.ISDEKWidjet(config);
    } catch (e) {
      this._logError(e);
    }
  }
  _loadedWidjet() {
    try {
      if (window.ISDEKWidjet === undefined) {
        setTimeout(() => {
          if (window.ISDEKWidjet === undefined) {
            this._setError("ISDEKWidjet загружен, но не доступен.");
          }
          this._activateWidjet();
        }, 3000);
      } else {
        this._activateWidjet();
      }
    } catch (e) {
      this._logError(e);
    }
  }
  _loadWidjet() {
    const el = document.getElementById(CDEK_SCRIPT_ID_ELEMENT);
    if (!el) {
      const newEl = document.createElement("script");
      newEl.src = CDEK_SCRIPT_SRC_ELEMENT;
      newEl.id = CDEK_SCRIPT_ID_ELEMENT;
      newEl.async = true;
      newEl.onload = this._loadedWidjet.bind(this);
      document.body.insertAdjacentElement("beforeend", newEl);
      return;
    }
    this._loadedWidjet();
  }

  async _request(url) {
    try {
      this.setLoading(true);
      const request = await this.fetchApp.get(url);
      const { content, data } = request;
      if (content) {
        this._infBlock.innerHTML = content;

        const target = this._getChildElement(
          SELECTOR_IN_DELIV_GLOBAL,
          this._infBlock,
          false
        );

        const targetEl = target || this._infBlock;
        this._triggerModeSatatus(targetEl);
        EventHandler.trigger(this._element, EVENT_RUN_CART_DELINF_UPDATE);
        if (this._topCityBtnText) {
          this._topCityBtnText.innerText = this.selectedValue.CityName;
        }
        const cityBtnCartText = this._getChildElement(
          CITY_BTN_TEXT_CART_SELECTOR,
          document.body,
          false
        );
        if (cityBtnCartText) {
          cityBtnCartText.innerText = this.selectedValue.CityName;
        }

        if (data !== undefined) {
          this.loadData(data);
        }
        return true;
      }
      this._setError("request app delivery change city no contetnt");
    } catch (e) {
      this._logError(e);
    } finally {
      this.setLoading();
    }
  }
  _getUrl() {
    return `${URL_START}${this.selectedValue.ID}`;
  }

  _activate() {
    this._data = null;
    this._widjet = null;
    this._infBlock = this._getChildElement(INF_BLOCK_SELECTOR, this._element);
    this._loader = this._getChildElement(LOADER_SELECTOR, this._element);
    this._topCityBtnText = this._getChildElement(
      CITY_BTN_TEXT_SELECTOR,
      document.body,
      false
    );
    this._tabs = null;
  }

  // ------------------------------------------------------------------------
  // Static

  static changeCityHangler(config, event) {
    const data = AppDilevery.elementInterface(event.target, config);

    if (data) {
      data.eventSelectCity(event);
    }
  }

  static ajaxLoadDataHangler(config, event) {
    if (!event.detail) return;

    if (event.detail.modeStatus !== MODE_STATUS.DILVERY_INF) return;

    const element = SelectorEngine.findOne(
      SELECTOR_CONTEINER_DELIV,
      event.target
    );

    if (!element) return;

    const data = AppDilevery.elementInterface(element, config);
    if (data) {
      data.loadData(event.detail.data);
    }
  }

  static init(config) {
    EventHandler.on(
      document,
      EVENT_CHANGE_CITY,
      null,
      AppDilevery.changeCityHangler.bind(null, config)
    );
    EventHandler.on(
      document,
      EVENT_AJAX_DATA_LOADED,
      null,
      AppDilevery.ajaxLoadDataHangler.bind(null, config)
    );
  }

  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppDilevery;
