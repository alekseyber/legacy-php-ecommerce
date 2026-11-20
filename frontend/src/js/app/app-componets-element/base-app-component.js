import Data from "@Dom/data";
import AppBase from "@App/app-base";

import { NAME_KEY } from "@Constans";
import { isElement } from "@AppUtil/util"; //, describe

const VERSION = "2.0";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const Default = {};

class BaseComponent extends AppBase {
  constructor(element, config, properties = {}) {
    super(Data.extendConfig(Default, config));

    if (!element || !isElement(element)) return;
    this._element = element;

    // set properties for use in the constructor initialization
    for (let key in properties) {
      this[key] = properties[key];
    }

    Data.setData(element, this.constructor.DATA_KEY, this, NAME_KEY);
  }

  dispose() {
    super.dispose();
    Data.removeData(this._element, this.constructor.DATA_KEY);
    this._element = null;
  }

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
