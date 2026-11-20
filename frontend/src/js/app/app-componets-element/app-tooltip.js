//bootstrap component
//import Tooltip from "bootstrap/js/src/tooltip";

import Data from "@Dom/data";
import BaseComponent from "./base-app-component";
import { APP_NAME_SPACE } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const Tooltip = window.bootstrap.Tooltip;

const DATA_KEY = `${APP_NAME_SPACE}.apptooltip`;
// const EVENT_KEY = `.${DATA_KEY}`;

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {
  // trigger: "manual",
  boundary: "window",
};

class AppTooltip extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      this.tooltipTrigger = new Tooltip($element, this.config);
    } catch (e) {
      this._logError(e);
    }
  }
  // Public
  show() {
    this.tooltipTrigger.show();
  }
  hide() {
    this.tooltipTrigger.hide();
  }
  toggle() {
    this.tooltipTrigger.toggle();
  }

  dispose() {
    super.dispose();
    this.tooltipTrigge.dispose();
    this.tooltipTrigge = null;
  }

  // Static

  // static elementInterface($element) {
  //   if (!$element) return null;
  //   const data = Data.getData($element, DATA_KEY) || new AppTooltip($element);
  //   if (typeof config === "string") {
  //     if (typeof data[config] === "undefined") {
  //       throw new TypeError(`No method named "${config}"`);
  //     }

  //     data[config]();
  //   }

  //   return data;
  // }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppTooltip;
