import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
//import SelectorEngine from "@Dom/selector-engine";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";

import {
  EVENT_APP_ALERT,
  APP_NAME_SPACE,
  ALERT_SUCCESS,
  NAME_KEY,
} from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.appalert`;
// const EVENT_KEY = `.${DATA_KEY}`;

const DATA_KEY_BS = "bs.alert";
const EVENT_KEY_BS = `.${DATA_KEY_BS}`;
const EVENT_CLOSE = `close${EVENT_KEY_BS}`;
//const DATA_API_KEY = ".data-api";

const CLASS_NAME_ACTIVE = "show";

const SELECTOR_ALERT = '[data-selector="alert-';

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {
  timeoutClose: 3000,
};

class AppAlert extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
  }

  // Public

  show(message) {
    if (!message) {
      this._setError(`Empty message`);
    }

    Manipulator.setDataAttributeNoBs(this._element, "message", message);

    Manipulator.addClass(this._element, CLASS_NAME_ACTIVE);

    if (this.config.timeoutClose) {
      setTimeout(() => {
        this.close();
      }, this.config.timeoutClose);
    }
  }

  close() {
    Manipulator.removeClass(this._element, CLASS_NAME_ACTIVE);
    EventHandler.trigger(this._element, EVENT_CLOSE);
  }
  // Private

  // Static

  static elementInterface(event) {
    const { detail } = event;
    const { type, message } = detail;

    // ALERT_SUCCESS, ALERT_DANGER

    if (!type || !message) return;
    const action = type === ALERT_SUCCESS ? "success" : "danger";
    const selector = `${SELECTOR_ALERT}${action}"]`;
    const $element = document.querySelector(selector);
    
    if (!$element) return null;
    
    const data =
      Data.getData($element, DATA_KEY, NAME_KEY) ||
      new AppAlert($element, null, NAME_KEY);
    if (!data) return;

    data.show(message);
    return data;
  }

  static init() {
    EventHandler.on(document, EVENT_APP_ALERT, null, AppAlert.elementInterface);
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }



  static get success() {
    const type = ALERT_SUCCESS;
    return (message) => {
      const detail = { type, message };
      AppAlert.elementInterface({
        detail,
      });
    };
  }

  static get danger() {
    const type = ALERT_DANGER;
    return (message) => {
      const detail = { type, message };
      AppAlert.elementInterface({
        detail,
      });
    };
  }
}

export default AppAlert;
