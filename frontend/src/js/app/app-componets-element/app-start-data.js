import Data from "@Dom/data";
import BaseComponent from "./base-app-component";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import { APP_NAME_SPACE, EVENT_AJAX_DATA_LOADED } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const NAME = "appstartdata";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;

const APP_START_PARAMS_NAME_KEY = "start";

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {};

class AppStartData extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      this._activate();
    } catch (e) {
      this._logError(e);
    }
  }
  // Public

  // private

  _activate() {
    const paramsData = Manipulator.getDataAttributeNoBs(
      this._element,
      APP_START_PARAMS_NAME_KEY
    );

    if (paramsData) {
      const params = JSON.parse(paramsData);
      if (params.data && params.mode) {       
        EventHandler.trigger(document.body, EVENT_AJAX_DATA_LOADED, {
          detail: {
            modeStatus: params.mode,
            data: params.data,
          },
        });
      }
    }
  }

  // Static

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppStartData;
