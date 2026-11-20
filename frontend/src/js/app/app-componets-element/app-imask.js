import IMask from "imask";
import Data from "@Dom/data";
import BaseComponent from "./base-app-component";
import { APP_NAME_SPACE } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const NAME = "imask";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {
  dinamic: true,
  mask: [
    {
      mask: "+{7}-000-000-00-00",
      startsWith: "7",
      lazy: true,
      country: "Russia_+7",
    },
    {
      mask: "{8}-000-000-00-00",
      startsWith: "8",
      lazy: true,
      country: "Russia_8",
    },
  ],
};
//{ mask: "9-999-999-99-99" }

const IMASK_PARAMS_FIELDS = ["mask"];

class AppImask extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      const params = {};
      IMASK_PARAMS_FIELDS.forEach((key) => {
        if (this.config[key]) {
          params[key] = this.config[key];
        }
      });
      if (this.config.dinamic) {
        params.dispatch = (appended, dynamicMasked) => {
          var number = (dynamicMasked.value + appended).replace(/\D/g, "");

          return dynamicMasked.compiledMasks.find((m) => {
            return number.indexOf(m.startsWith) === 0;
          });
        };
      }

      this.elMask = IMask(this._element, params);
    } catch (e) {
      this._logError(e);
    }
  }
  // Public

  dispose() {
    super.dispose();
    this.elMask.destroy();
    this.elMask = null;
  }
  // private

  // Static

  // static elementInterface($element, config) {
  //   if (!$element) return null;
  //   const data =
  //     Data.getData($element, DATA_KEY) || new AppImask($element, config);

  //   return data;
  // }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppImask;
