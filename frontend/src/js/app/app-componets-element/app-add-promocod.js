import Data from "@Dom/data";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";
import { APP_NAME_SPACE, EVENT_RUN_CART_UPDATE } from "@Constans";
import fetchApp from "@AppFetch/app-fetch";




/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "appdilevery";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

const EVENT_SUBMIT = `submit${EVENT_KEY}`;


const CUPON_FORM_SELECTOR = 'form[data-selector="app-cupon-form"]';

const URL = "/cart/cupon";

const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppAddPromocod extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    // this._activate();
  }

  // Public
  submitForm(event) {
    if (this._element.checkValidity() === false) return;
    event.preventDefault();
    const data = new FormData(this._element);   

    this._request(data);
  }
  alertDanger(msg) {
    if (this.config.alertDanger && msg) {
      this.config.alertDanger(msg);
    }
  }
  alertSuccess(msg) {
    if (this.config.alertSuccess && msg) {
      this.config.alertSuccess(msg);
    }
  }

  // ------------------------------------------------------------------------
  // private
  // ------------------------------------------------------------------------
  // protected

  _cartUpdateTriger() {
    EventHandler.trigger(document, EVENT_RUN_CART_UPDATE);
  }

  async _request(data) {
    try {
      const rezult = await fetchApp.post(URL, data);

      if (rezult.succes && rezult.message) {
        this.alertSuccess(rezult.message);
        this._cartUpdateTriger();
      } else {
        this.alertDanger(rezult.message);
      }
    } catch (e) {
      // this.alertDanger(e.message);
      if (this.config.logError) {
        this.config.logError(e);
      } else {
        console.log(e.message);
      }
    }
  }

  // ------------------------------------------------------------------------
  // Static

  // static elementInterface($element, config) {
  //   const data =
  //     Data.getData($element, DATA_KEY, NAME_KEY) ||
  //     new AppAddPromocod($element, config);

  //   return data;
  // }

  static init(config) {
    EventHandler.on(document, EVENT_SUBMIT, CUPON_FORM_SELECTOR, function (event) {
      const data = AppAddPromocod.elementInterface(this, config);
      if (data) {
        data.submitForm(event);
      }
    });
  }

  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppAddPromocod;
