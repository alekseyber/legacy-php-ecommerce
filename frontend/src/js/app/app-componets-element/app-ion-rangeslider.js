import IonRangeSlider from "@Components/IonRangeSlider"; //
import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";
import { throttle, ltrim } from "@AppUtil/util";
import { EVENT_SLIDER_CHANGE, APP_NAME_SPACE } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "filterPriceSl";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
// const EVENT_KEY = `.${DATA_KEY}`;
//const DATA_API_KEY = ".data-api";


const PARAMS_NAMES = [
  { nameApp: "type", name: "type", default: "double" },
  { nameApp: "grid", name: "grid", default: true },
  { nameApp: "pricefr", name: "min", default: 0 },
  { nameApp: "priceto", name: "max", default: 100 },
  { nameApp: "pricefrlimit", name: "from", default: 0 },
  { nameApp: "pricetolimit", name: "to", default: 100 },
  { nameApp: "prefix", name: "prefix", default: "" },
  { nameApp: "postfix", name: "postfix", default: "" },
  { nameApp: "step", name: "step", default: 100 },
  { nameApp: "postfix", name: "postfix", default: "" },
  {
    nameApp: "input_values_separator",
    name: "input_values_separator",
    default: ",",
  },
  { nameApp: "block", name: "block", default: false },
];

const Default = {
  name: NAME,
  throttleLimit: 2000,
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppIonRangeSlider extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    try {
      this._activate();
    } catch (e) {
      this._logError(e);
    }
  }

  // ------------------------------------------------------------------------
  // protected

  // ------------------------------------------------------------------------
  // private
  _activate() {
    const elData = Manipulator.getDataAttributesNoBs(this._element);

    if (!elData.uri) {
      this._setError("Отсутствует обязательный атрибут uri");
    }
    this.uri = "/" + ltrim(elData.uri);
    const params = {};

    PARAMS_NAMES.forEach((item) => {
      params[item.name] = elData[item.nameApp] ?? item.default;
    });

    params.onChange = throttle(
      this._onChangeHandler.bind(this),
      this.config.throttleLimit
    );
    this.sliderInstance = new IonRangeSlider(this._element, params);

    // window[this.config.name] = {};
    // window[this.config.name].update = this.sliderInstance.update;
    // window[this.config.name].reset = this.sliderInstance.reset;
    this.update = this.sliderInstance.update;
    this.reset = this.sliderInstance.reset;
  }
  _onChangeHandler(data) {
    const { from, to } = data;
    let url = this.uri;
    url = url.replace("@fr@", from);
    url = url.replace("@to@", to);

    data.url = url;

    EventHandler.trigger(document.body, EVENT_SLIDER_CHANGE, {
      detail: {
        data,
        name: this.config.name,
      },
    });
  }

  // ------------------------------------------------------------------------
  dispose() {
    super.dispose();
    this.uri = null;
    this.update = null;
    this.reset = null;
    // delete window[this.config.name];
    // delete window[this.config.name].update;
    // delete window[this.config.name].reset;
    this.sliderInstance.destroy();
    this.sliderInstance = null;
  }


  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
  // static get EVENT_CHANGE() {
  //   return EVENT_SLIDER_CHANGE;
  // }
}

export default AppIonRangeSlider;
