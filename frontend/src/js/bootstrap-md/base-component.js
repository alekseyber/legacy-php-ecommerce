/**
 * --------------------------------------------------------------------------
 * Bootstrap (v5.0.0-beta2): base-component.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/main/LICENSE)
 * --------------------------------------------------------------------------
 */

import { isElement } from "@AppUtil/util";
import Data from "@Dom/data";



/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const VERSION = "5.0.0-beta2";

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
   
    Data.setData(element, this.constructor.DATA_KEY, this);
  }

  dispose() {
    Data.removeData(this._element, this.constructor.DATA_KEY);
    this._element = null;
    this.config = null;
  }

  /** Static */

  static getInstance(element) {
    return Data.getData(element, this.DATA_KEY)
  }

  static get VERSION() {
    return VERSION;
  }
}

export default BaseComponent;
