import Data from "@Dom/data";
import SelectorEngine from "@Dom/selector-engine";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-component";

//import Checkbox from "./checkbox";
import Drawer from "./drawer";
import Ripples from "./ripples";
import Text from "./text";
import Textarea from "./textarea";
import { isIosDevice, isTouchDevice } from "@AppUtil/util";

import {
  APP_NAME_SPACE,
  MODE_STATUS,
  EVENT_AJAX_CONTENT_LOADED,
} from "@Constans";

//app

import AppIonRangeSlider from "@App/app-componets-element/app-ion-rangeslider";
import AppLightgallery from "@App/app-componets-element/app-lightgallery";
import AppImask from "@App/app-componets-element/app-imask";
// import AppTooltip from "@App/app-componets-element/app-tooltip";

import AppJsForm from "@App/app-componets-element/app-js-form";
import AppJsVkAuthForm from "@App/app-componets-element/app-js-vk-auth-form";

import AppStartData from "@App/app-componets-element/app-start-data";

//MODE_STATUS.JS_FORM

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "bootstrapMaterialDesign";
const DATA_KEY = `bmd.${NAME}`;

const JsClasses = {
  // checkbox: {
  //   jsClass: Checkbox,
  //   on: true,
  //   modeStatus: [MODE_STATUS.ALL],
  // },
  drawer: {
    jsClass: Drawer,
    on: true,
    modeStatus: [MODE_STATUS.START],
  },
  text: {
    jsClass: Text,
    on: true,
    modeStatus: [
      MODE_STATUS.Q_ORDER,
      MODE_STATUS.JS_FORM,
      MODE_STATUS.JS_VK_FORM,
    ],
  },
  textarea: {
    jsClass: Textarea,
    on: true,
    modeStatus: [
      MODE_STATUS.Q_ORDER,
      MODE_STATUS.JS_FORM,
      MODE_STATUS.JS_VK_FORM,
    ],
  },
  ripples: {
    jsClass: Ripples,
    on: false,
    modeStatus: [MODE_STATUS.ALL],
  },
  appionrangeslider: {
    jsClass: AppIonRangeSlider,
    on: true,
    modeStatus: [MODE_STATUS.FILTER], //MODE_STATUS.FILTER
  },
  // apptooltip: {
  //   jsClass: AppTooltip,
  //   on: false, //отключено по соображениям производительности
  //   modeStatus: [MODE_STATUS.PAGE, MODE_STATUS.DILVERY_INF],
  // },
  applightgallery: {
    jsClass: AppLightgallery,
    on: true,
    modeStatus: [MODE_STATUS.PRODUCT_MAIN],
  },
  appimask: {
    jsClass: AppImask,
    on: true,
    modeStatus: [MODE_STATUS.Q_ORDER, MODE_STATUS.JS_FORM],
  },
  appjsform: {
    jsClass: AppJsForm,
    on: true,
    modeStatus: [MODE_STATUS.JS_FORM, MODE_STATUS.JS_VK_FORM],
  },
  appjsvkauthform: {
    jsClass: AppJsVkAuthForm,
    on: true,
    modeStatus: [MODE_STATUS.JS_VK_FORM],
  },
  appstartdata: {
    jsClass: AppStartData,
    on: true,
    modeStatus: [MODE_STATUS.START],
  },
};

// const APP_NAME_SPACE = "bmd";

const Default = {
  global: {
    validate: false,
    label: {
      className: "bmd-label-static", // default style of label to be used if not specified in the html markup
    },
  },
  autofill: {
    selector: "body",
  },
  checkbox: {
    selector: ".checkbox > label > input[type=checkbox]",
    label: { required: false },
  },
  checkboxInline: {
    selector: "label.checkbox-inline > input[type=checkbox]",
  },
  collapseInline: {
    selector: '.bmd-collapse-inline [data-toggle="collapse"]',
  },
  drawer: {
    selector: ".bmd-layout-drawer",
  },
  file: {
    selector: "input[type=file]",
  },
  radio: {
    selector: ".radio > label > input[type=radio]",
  },
  radioInline: {
    selector: "label.radio-inline > input[type=radio]",
  },
  ripples: {
    //selector: ['.btn:not(.btn-link):not(.ripple-none)'] // testing only
    selector: [
      ".btn:not(.btn-link):not(.ripple-none)",
      ".card-image:not(.ripple-none)",
      ".navbar a:not(.ripple-none)",
      ".dropdown-menu a:not(.ripple-none)",
      ".nav-tabs a:not(.ripple-none)",
      ".pagination li:not(.active):not(.disabled) a:not(.ripple-none)",
      ".ripple", // generic marker class to add ripple to elements
    ],
  },
  select: {
    selector: ["select"],
  },
  switch: {
    selector: ".switch > label > input[type=checkbox]",
  },
  text: {
    selector: '[data-selector="textinput"]',
    // omit inputs we have specialized components to handle - we need to match text, email, etc.  The easiest way to do this appears to be just omit the ones we don't want to match and let the rest fall through to this.
    // selector: [
    //   `input:not([type=hidden]):not([type=checkbox]):not([type=radio]):not([type=file]):not([type=button]):not([type=submit]):not([type=reset])`,
    // ],
    // label: {
    //   required: false ,
    //   //selector: ".bmd-label-floating",
    // },
  },
  textarea: {
    // selector: ["textarea"],
    selector: '[data-selector="textareainput"]',
  },
  // apptableftmenu: {
  //   selector: '[data-bs-toggle="tableftmenu"]',
  // },
  apptooltip: {
    selector: '[data-bs-toggle="tooltip"]',
  },
  appionrangeslider: {
    selector: "#pricefilterslider",
  },
  applightgallery: {
    selector: '[data-selector="pr-im-gal"]', //lightgallery //.pr-image-gallery
  },
  appimask: {
    selector: "input[type='tel']",
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
  },
  appjsform: {
    selector: '[data-selector="js-form"]',
  },
  appjsvkauthform: {
    selector: '[data-selector="js-vk-form"]',
  },
  appstartdata: {
    selector: "[data-start]",
  },
  arrive: true,
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class BootstrapMaterialDesign extends BaseComponent {
  constructor($element, config, modeStatus = MODE_STATUS.ALL) {
    super($element, Data.extendConfig(Default, config));

    const JsClassesFiltred = modeStatus === MODE_STATUS.ALL ? JsClasses : {};
    if (modeStatus !== MODE_STATUS.ALL) {
      const JsClassesKeys = Object.keys(JsClasses).filter((el) => {
        if (!JsClasses[el].on) return;
        return (
          JsClasses[el].modeStatus.indexOf(MODE_STATUS.ALL) > -1 ||
          JsClasses[el].modeStatus.indexOf(modeStatus) > -1
        );
      });
      JsClassesKeys.forEach((item) => {
        JsClassesFiltred[item] = JsClasses[item];
      });
    }
    
    for (let component of Object.keys(JsClasses)) {
      if (!JsClassesFiltred[component]) continue;
      if (!JsClassesFiltred[component].on) continue;

      let componentConfig = this.config[component];

      // check to make sure component config is enabled (not `false`)
      if (componentConfig) {
        // assemble the selector as it may be an array
        let selector = this._resolveSelector(componentConfig);

        componentConfig = Data.extendConfig(
          this.config.global,
          componentConfig
        );

        // create the jquery fn name e.g. 'bmdText' for 'text'
        let componentName = `${
          component.charAt(0).toUpperCase() + component.slice(1)
        }`;

        let jqueryFn = `bmd${componentName}`;
        let carrentEl = null;

        try {
          const ItemClass = JsClasses[component].jsClass;

          const elements = SelectorEngine.find(selector, this._element);

          elements.forEach((itemElement) => {
            carrentEl = itemElement;
            ItemClass.elementInterface(itemElement, componentConfig);
          });
        } catch (e) {
          let message = `Failed to instantiate component: '${selector}' - [${jqueryFn}](${componentConfig})`;
          console.error(message, e, `\nSelected elements: `, carrentEl); // eslint-disable-line no-console , $(selector)
          throw e;
        }
      }
    }
  }

  // ------------------------------------------------------------------------
  // private

  _resolveSelector(componentConfig) {
    let selector = componentConfig.selector;
    if (Array.isArray(selector)) {
      selector = selector.join(", ");
    }

    return selector;
  }

  // ------------------------------------------------------------------------
  // static

  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }

  static init() {
    EventHandler.on(
      document,
      EVENT_AJAX_CONTENT_LOADED,
      null,
      function (event) {
        if (!event.detail.modeStatus) return;
        BootstrapMaterialDesign.elementInterface(
          event.target,
          null,
          event.detail.modeStatus
        );
      }
    );

    if (isIosDevice()) {
      document.body.classList.add("device-ios");
    }
    if (isTouchDevice()) {
      document.body.classList.add("device-touch");
    }

    if (!window[APP_NAME_SPACE]) {
      window[APP_NAME_SPACE] = {};
    }
    window[APP_NAME_SPACE].Drawer = Drawer;
  }

  static elementInterface($element, config, modeStatus = MODE_STATUS.ALL) {
    const data =
      Data.getData($element, DATA_KEY) ||
      new BootstrapMaterialDesign($element, config, modeStatus);
    return data;
  }
}

export default BootstrapMaterialDesign;
