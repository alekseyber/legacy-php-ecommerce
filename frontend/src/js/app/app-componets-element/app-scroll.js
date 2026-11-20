import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import SelectorEngine from "@Dom/selector-engine";
import BaseComponent from "./base-app-component";
import { APP_NAME_SPACE, EVENT_SCROLL_TOP } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.appscroll`;
const EVENT_KEY = `.${DATA_KEY}`;
const DATA_API_KEY = ".data-api";

const EVENT_SCROLL = `scroll${EVENT_KEY}${DATA_API_KEY}`;
const EVENT_CLICK = `click${EVENT_KEY}${DATA_API_KEY}`;

const CLASS_BTN_SCROLL_ACTIVE = "visiblescr";
const CLASS_FIXED_BLOCK_HIDE = "d-none";
const CLASS_FIXED_TOP = "fixed-top";

const SELECTOR_BTN_SCROLL = ".top-scr-btn";
const SELECTOR_FIXED_BLOCK = ".fixed-bl";
const SELECTOR_HEADER = ".bmd-layout-header";
const SELECTOR_MAIN_NAV = ".main-nav";

const SCROLL_STEP_VALUE = 200;

const SELECTORS = [
  SELECTOR_BTN_SCROLL,
  SELECTOR_FIXED_BLOCK,
  SELECTOR_HEADER,
  SELECTOR_MAIN_NAV,
];

const SELECTORS_FIXED = [
  { name: SELECTOR_FIXED_BLOCK, on: "removeClass", off: "addClass" },
  { name: SELECTOR_HEADER, on: "addClass", off: "removeClass" },
  { name: SELECTOR_MAIN_NAV, on: "addClass", off: "removeClass" },
];

const CLASSES = {
  [SELECTOR_BTN_SCROLL]: [CLASS_BTN_SCROLL_ACTIVE],
  [SELECTOR_FIXED_BLOCK]: [CLASS_FIXED_BLOCK_HIDE],
  [SELECTOR_HEADER]: [CLASS_FIXED_TOP],
  [SELECTOR_MAIN_NAV]: [CLASS_FIXED_TOP],
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */

const Default = {
  fixedOn: 150,
  fixedOff: 140,
  btnScrollOn: 400,
  btnScrollOf: 390,
  fixedRun: true,
  globalFixedOff: "fixedOff",
};

class AppScroll extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    try {
      this._activate();
      this._addListners();
    } catch (e) {
      this._logError(e);
    }
  }

  // Public

  fixedOn() {
    this._setFixed("on");
  }
  fixedOff() {
    this._setFixed("off");
  }

  scrBtnOn() {
    this._setBtn("addClass");
  }
  scrBtnOff() {
    this._setBtn("removeClass");
  }

  dispose() {
    super.dispose();
    EventHandler.off(
      document,
      EVENT_SCROLL,
      null,
      this._scroolHanler.bind(this)
    );
    this.elements = null;
  }

  // Private
  _activate() {
    this.elements = {};
    SELECTORS.forEach((selector) => {
      this.elements[selector] = SelectorEngine.findOne(selector, this._element);
    });
  }
  _setFixed(action = "on") {
    SELECTORS_FIXED.forEach((selector) => {
      Manipulator[selector[action]](
        this.elements[selector.name],
        CLASSES[selector.name]
      );
    });
  }
  _setBtn(action = "addClass") {
    Manipulator[action](
      this.elements[SELECTOR_BTN_SCROLL],
      CLASSES[SELECTOR_BTN_SCROLL]
    );
  }
  _scroolHanler() {
    if (this.config.fixedRun && !window[this.config.globalFixedOff]) {
      if (pageYOffset > this.config.fixedOn) {
        this.fixedOn();
      }
      if (pageYOffset < this.config.fixedOff) {
        this.fixedOff();
      }
    }

    if (pageYOffset > this.config.btnScrollOn) {
      this.scrBtnOn();
    }
    if (pageYOffset < this.config.btnScrollOf) {
      this.scrBtnOff();
    }
  }
  _addListners() {
    EventHandler.on(
      document,
      EVENT_SCROLL,
      null,
      this._scroolHanler.bind(this)
    );
  }

  // Static

  static toTop() {
    this.windowScrol(0);
  }

  static windowScrol(to = 0) {
    let top = window.pageYOffset;
    const stepValue = SCROLL_STEP_VALUE;

    const step = () => {
      if (to < 0) return;
      top -= stepValue;
      if (top < to) top = to;
      window.scrollTo({
        top,
        behavior: "smooth",
      });

      if (!(top <= to)) {
        window.requestAnimationFrame(step);
      }
    };
    step();
  }

  static scroolTopHangler({ detail }) {
    if (detail) {
      if (detail.modal) {
        detail.modal.scrollTop = 0;
        return;
      }
    }
    AppScroll.toTop();
  }

  static init(config) {
    EventHandler.on(document, EVENT_CLICK, SELECTOR_BTN_SCROLL, () => {
      AppScroll.toTop();
    });
    EventHandler.on(
      document,
      EVENT_SCROLL_TOP,
      null,
      AppScroll.scroolTopHangler
    );
    AppScroll.elementInterface(document.body, config);
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppScroll;
