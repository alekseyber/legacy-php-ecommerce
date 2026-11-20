import Data from "@Dom/data";
import DataApp from "@AppUtil/data-app";
import EventHandler from "@Dom/event-handler";

//INIT
import AppDilevery from "./app-componets-element/app-dilevery";
import AppAddPromocod from "./app-componets-element/app-add-promocod";

import AppProductColors from "./app-componets-element/app-productcolors";
//import AppMainClassToggler from "./app-componets-element/app-mainclasstoggler";
import AppAlert from "./app-componets-element/app-alert";
import AppLoader from "./app-componets-element/app-loader";
import AppScroll from "./app-componets-element/app-scroll";
import AppTypeahead from "./app-componets-element/app-typeahead";
import AppTabLeftMenu from "./app-componets-element/app-tableftmenu";
import AppSizeSelect from "./app-componets-element/app-size-select";
import AppMainProduct from "./app-componets-element/app-main-product";

//INSTANCE
import AppCart from "./app-components-data/app-cart";
import AppQuickviewProduct from "./app-components-data/app-quickview-product";
import AppModalAction from "./app-components-data/app-modal-action";
import AppAskquestion from "./app-components-data/app-modal-askquestion";
import AppPage from "./app-components-data/app-page";

import {
  APP_NAME_SPACE,
  APP_DATA_KEY,
  EVENT_APP_ALERT,
  ALERT_SUCCESS,
  ALERT_DANGER,
  EVENT_APP_LOADER,
  EVENT_SCROLL_TOP,
} from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.appmain`;
const EVENT_KEY = `.${DATA_KEY}`;

const EVENT_SUBMIT = `submit${EVENT_KEY}`;

const SELECTOR_FORM_VALIDATE = "form.needs-validation";

const FORM_VALDATED_CLASS = "was-validated";

const INIT_CLASSES = [
  {
    name: "appDilevery",
    Class: AppDilevery,
  },
  {
    name: "appAddPromocod",
    Class: AppAddPromocod,
  },
  {
    Class: AppProductColors,
    name: "appProductColors",
  },
  // {
  //   Class: AppMainClassToggler,
  //   name: "appMainClassToggler",
  // },
  {
    Class: AppAlert,
    name: "appAlert",
  },
  {
    Class: AppLoader,
    name: "appLoader",
  },
  {
    Class: AppScroll,
    name: "appScroll",
  },
  {
    Class: AppTypeahead,
    name: "appTypeahead",
  },
  {
    Class: AppTabLeftMenu,
    name: "appTabLeftMenu",
  },
  {
    Class: AppSizeSelect,
    name: "appSizeSelect",
  },
  {
    Class: AppMainProduct,
    name: "AppMainProduct",
  },
  {
    name: "appAskquestion",
    Class: AppAskquestion,
  },
];

const INSTANCE_CLASSES = [
  {
    name: "appCart",
    Class: AppCart,
  },
  {
    name: "appQuickviewProduct",
    Class: AppQuickviewProduct,
  },
  {
    name: "appModalAction",
    Class: AppModalAction,
  },
  {
    name: "AppPage",
    Class: AppPage,
  },
];

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {};

class App {
  constructor(config) {
    this.config = Data.extendConfig(Default, config);
    DataApp.setData(APP_DATA_KEY, this);

    this._run();
  }

  // Public
  getState(key) {
    if (this.state[key]) {
      return this.state[key];
    }
    return null;
  }
  setState(key, value) {
    if (!key || !value) return;
    this.state[key] = value;

    return true;
  }

  alertDanger(message = "") {
    EventHandler.trigger(document, EVENT_APP_ALERT, {
      detail: {
        type: ALERT_DANGER,
        message,
      },
    });
  }
  alertSuccess(message = "") {
    EventHandler.trigger(document, EVENT_APP_ALERT, {
      detail: {
        type: ALERT_SUCCESS,
        message,
      },
    });
  }
  loader(on) {
    const name = on ? "show" : "hide";
    EventHandler.trigger(document, EVENT_APP_LOADER, {
      detail: { name },
    });
  }
  scrollTop() {
    EventHandler.trigger(document, EVENT_SCROLL_TOP);
  }

  logError(e) {
    let message = "";
    if (e.message) {
      message = e.message;
    }
    if (e.fileName) {
      message = message + " | " + e.fileName;
    }
    if (e.lineNumber) {
      message = message + " | " + e.lineNumber;
    }
    // if (message) {
    //   console.error(message);
    //   return true;
    // }
    // return console.error(e.message);

    console.error(e);
  }

  dispose() {
    this.state = {};
    this._addListners("off");
    INSTANCE_CLASSES.forEach((item) => {
      this[item.name].dispose();
      this[item.name] = null;
    });
    this.exportConfig = null;
  }

  // Private

  _run() {
    this.state = {};
    this.exportConfig = {
      logError: this.logError,
      alertDanger: this.alertDanger,
      alertSuccess: this.alertSuccess,
      loader: this.loader,
    };
    this._init();
    this._greatInstance();
    this._addListners();
  }

  _formValidatSubmitHangler(event) {
    const form = this;

    if (form.checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    }
    form.classList.add(FORM_VALDATED_CLASS);
  }
  _addListners(action = "on") {
    EventHandler[action](
      document,
      EVENT_SUBMIT,
      SELECTOR_FORM_VALIDATE,
      this._formValidatSubmitHangler
    );
  }
  _greatInstance() {
    INSTANCE_CLASSES.forEach((item) => {
      this[item.name] = new item.Class(this.exportConfig);
    });
  }
  _init() {
    INIT_CLASSES.forEach((item) => {
      item.Class.init(this.exportConfig);
    });
  }

  // Static

  static interface(config, value = null) {
    if (!document.body) return null;
    const data = DataApp.getData(APP_DATA_KEY) || new App(config);

    if (typeof config === "string" && config) {
      if (data[config]) {
        return data[config];
      }
      if (value) {
        data.setState(config, value);
      } else {
        return data.getState(config);
      }
    }
    return data;
  }

  static init(config) {
    App.interface(config);
    if (!window[APP_NAME_SPACE]) {
      window[APP_NAME_SPACE] = {};
    }
    window[APP_NAME_SPACE].App = App;

    // window[APP_NAME_SPACE].AppTabLeftMenu = AppTabLeftMenu;
    // window[APP_NAME_SPACE].AppAlert = AppAlert;
    // window[APP_NAME_SPACE].AppLoader = AppLoader;
    // window[APP_NAME_SPACE].AppScroll = AppScroll;
    // window[APP_NAME_SPACE].AppIonRangeSlider = AppIonRangeSlider;
    // window[APP_NAME_SPACE].AppTypeahead = AppTypeahead;
  }

  // getters
}

export default App;
