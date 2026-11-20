import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import SelectorEngine from "@Dom/selector-engine";
import EventHandler from "@Dom/event-handler";
import fetchApp from "@AppFetch/app-fetch";
import { isElement } from "@AppUtil/util";

import {
  MODE_STATUS_ELEMENT_DATA_KEY,
  EVENT_AJAX_CONTENT_LOADED,
  MODE_STATUS,
  EVENT_AJAX_DATA_LOADED,
} from "@Constans/index";

const Default = {};

class AppBase {
  constructor(config) {
    try {
      this.config = Data.extendConfig(Default, config);
      if (!this.constructor.DATA_KEY) {
        this._setError(`Failed to find DATA_KEY`);
      }
      this.fetchApp = fetchApp;
      this.loadingStatus = false;
      this.appLoadingStatus = false;
      this.awaitLoadingFunctions = [];
    } catch (e) {
      this._logError(e);
    }
  }

  // Public

  dispose() {
    this.fetchApp = null;
    this.loadingStatus = null;
    this.awaitLoadingFunctions = null;
    this.appLoadingStatus = null;
    this.config = null;
  }

  // Protected

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
      modeStatus = this._checKeyByValueModeStatus(modeStatus);
    }

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

  _getChildElement(selector, elementIn = document.body, required = true) {
    if (!elementIn) return null;
    const _element = SelectorEngine.findOne(selector, elementIn);

    if (!_element && required) {
      this._setError(`Failed to find field ${selector}`);
    }
    return _element;
  }

  _getParrentElement(selector, elementIn, required = true) {
    if (!elementIn)
      this._setError(`ParrentElement - Failed elementIn ${selector}`);
    const _element = elementIn.closest(selector);
    if (!_element && required) {
      this._setError(`Failed to find field ${selector}`);
    }
    return _element;
  }

  _setError(message = "") {
    const className = this.constructor.name;
    throw new Error(`${message} for ${className}`);
  }
  _logError(e) {
    if (this.config.logError) {
      this.config.logError(e);
    } else {
      console.log(e.message);
    }
  }
}

export default AppBase;
