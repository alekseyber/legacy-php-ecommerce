import Data from "@Dom/data";
import {
  NAME_KEY,
  EVENT_AJAX_CONTENT_LOADED,
  MODE_STATUS,
  MODE_STATUS_ELEMENT_DATA_KEY,
  EVENT_AJAX_DATA_LOADED,
} from "@Constans";
import { isElement } from "@AppUtil/util"; //, describe
import fetchApp from "@AppFetch/app-fetch";
import EventHandler from "@Dom/event-handler";
import SelectorEngine from "@Dom/selector-engine";
import Manipulator from "@Dom/manipulator";

const VERSION = "2.0";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const Default = {};

class BaseComponent {
  constructor(element, config, properties = {}) {
    if (!element || !isElement(element)) return;

    this._element = element;

    this.config = Data.extendConfig(Default, config);

    // set properties for use in the constructor initialization
    for (let key in properties) {
      this[key] = properties[key];
    }

    Data.setData(element, this.constructor.DATA_KEY, this, NAME_KEY);

    this.fetchApp = fetchApp;
    this.loadingStatus = false;
    this.appLoadingStatus = false;
    this.awaitLoadingFunctions = [];
  }

  dispose() {
    Data.removeData(this._element, this.constructor.DATA_KEY);
    this._element = null;
    this.config = null;
    this.fetchApp = null;
    this.loadingStatus = null;
    this.appLoadingStatus = null;
    this.awaitLoadingFunctions = null;
  }
  _alertDanger(message) {
    if (this.config.alertDanger && message) {
      this.config.alertDanger(message);
    } else {
      console.log(message);
    }
  }
  _alertSuccess(message) {
    if (this.config.alertSuccess && message) {
      this.config.alertSuccess(message);
    } else {
      console.log(message);
    }
  }
  _appLoading(on = false) {
    if (this.config.loader) {
      this.config.loader(on);
    }
  }

  _setError(message = "") {
    // throw new Error(`${msg} for ${describe(this._element)}`);
    throw new Error(`${message} for BaseComponent`);
  }

  _logError(e) {
    if (this.config.logError) {
      this.config.logError(e);
    } else {
      console.log(e.message);
    }
  }

  _getChildElement(selector, elementIn = this._element, required = true) {
    const _element = SelectorEngine.findOne(selector, elementIn);

    if (!_element && required) {
      this._setError(`Failed to find field |${selector} |`);
    }
    return _element;
  }

  _getParrentElement(selector, elementIn = this._element, required = true) {
    const _element = elementIn.closest(selector);
    if (!_element && required) {
      this._setError(`Failed to find field | ${selector} |`);
    }
    return _element;
  }

  _checKeyByValueModeStatus(value) {
    const key = Object.keys(MODE_STATUS).find(
      (key) => MODE_STATUS[key] === value
    );
    if (key) return MODE_STATUS[key];
    return null;
  }

  _triggerModeSatatus(element, modeStatus, data) {
    if (!isElement(element)) return;
    if (!modeStatus) {
      modeStatus = Manipulator.getDataAttributeNoBs(
        element,
        MODE_STATUS_ELEMENT_DATA_KEY
      );
    }
    modeStatus = this._checKeyByValueModeStatus(modeStatus);

    if (modeStatus) {
      EventHandler.trigger(element, EVENT_AJAX_CONTENT_LOADED, {
        detail: {
          modeStatus,
        },
      });
    }
    if (data) {
      EventHandler.trigger(element, EVENT_AJAX_DATA_LOADED, {
        detail: {
          modeStatus,
          data,
        },
      });
    }
  }

  _setLoadingOn() {
    this.loadingStatus = true;
  }
  _setLoadingOff() {
    this.loadingStatus = false;
    if (this.appLoadingStatus) {
      this._appLoading(false);
    }

    this.awaitLoadingFunctions.forEach((item) => item());
    this.awaitLoadingFunctions = [];
  }
  _awaitLoadingFunc() {
    const argsArr = arguments.length === 1 ? [] : Array.apply(null, arguments);
    const args = [].slice.call(argsArr, 1);
    const func = arguments[0];
    if (!func || typeof func !== "function") return;
    if (this.loadingStatus) {
      this.appLoadingStatus = true;
      this._appLoading(true);
      return this.awaitLoadingFunctions.push(func.bind(this, [...args]));
    }
    func.apply(this, args);
  }

  // _formSetHiddenInput(form, key, value, valid = true) {
  //   if (valid) {
  //     if (form.checkValidity() === false) return;
  //   }

  //   let input;
  //   const selector = `[name="${key}"]`;
  //   input = this._getChildElement(selector, form, false);
  //   if (input) {
  //     input.value = value;
  //     return;
  //   }
  //   input = document.createElement("input");
  //   input.setAttribute("name", key);
  //   input.setAttribute("value", value);
  //   // input.value = value;
  //   input.hidden = true;
  //   form.insertAdjacentElement("afterbegin", input);
  // }

  /** Static */

  static elementInterface(element, config) {
    if (!isElement(element)) {
      console.error(`${this} elementInterface - не передан элемент`);
      return null;
    }
    const data =
      Data.getData(element, this.DATA_KEY, NAME_KEY) ||
      new this(element, config);

    if (typeof config === "string") {
      if (typeof data[config] === "undefined") {
        throw new TypeError(`No method named "${config}"`);
      }

      data[config]();
    }

    return data;
  }

  static getInstance(element) {
    return Data.getData(element, this.DATA_KEY, NAME_KEY);
  }

  static get VERSION() {
    return VERSION;
  }
}

export default BaseComponent;
