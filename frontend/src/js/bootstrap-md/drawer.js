import BaseLayout from "./baseLayout";
import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "drawer";
const DATA_KEY = `bmd.${NAME}`;


const EVENT_KEY = `.${DATA_KEY}`;
const DATA_API_KEY = ".data-api";
const EVENT_CLICK_DATA_API = `click${EVENT_KEY}${DATA_API_KEY}`;

const Keycodes = {
  ESCAPE: 27,
  //ENTER: 13,
  //SPACE: 32
};

const SELECTOR_DRAWER_CLOSE_EL = ".drawer-cl";
const SELECTOR_DRAWER = ".bmd-layout-drawer";

const ClassName = {
  IN: "in",
  DRAWER_IN: `bmd-drawer-in`,
  DRAWER_OUT: `bmd-drawer-out`,
  DRAWER: "bmd-layout-drawer",
  CONTAINER: "bmd-layout-container",
};

const Default = {
  focusSelector: `a, button, input`,
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class Drawer extends BaseLayout {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    const elementId = this._element.id ? this._element.id : "nonId";

    this._selectorToggle = `[data-toggle="drawer"][href="#${elementId}"], [data-toggle="drawer"][data-target="#${elementId}"]`;

    this.$toggles = document.querySelectorAll(this._selectorToggle);

    this._addAria();

    // click or escape on the backdrop closes the drawer
    const backdropKeydownHangler = (ev) => {
      if (ev.isComposing || ev.keyCode === Keycodes.ESCAPE) {
        this.hide();
      }
    };

    const backdropClickHangler = () => {
      this.hide();
    };

    if (this.$backdrop) {
      this.$backdrop.addEventListener(
        "keydown",
        backdropKeydownHangler.bind(this)
      );
      this.$backdrop.addEventListener("click", backdropClickHangler.bind(this));
    }

    this._element.addEventListener(
      "keydown",
      backdropKeydownHangler.bind(this)
    );

    // any toggle button clicks

    EventHandler.on(
      document,
      EVENT_CLICK_DATA_API,
      this._selectorToggle,
      this._togglesClickHangler.bind(this)
    );
  }

  dispose() {
    super.dispose();
    this.$toggles = [];
    EventHandler.off(
      document,
      EVENT_CLICK_DATA_API,
      this._selectorToggle,
      this._togglesClickHangler.bind(this)
    );
    this._selectorToggle = null;
  }
  _togglesClickHangler() {
    this.toggle();
  }

  toggle() {
    if (this._isOpen()) {
      this.hide();
    } else {
      this.show();
    }
  }

  show() {
    if (this._isForcedClosed() || this._isOpen()) {
      return;
    }

    const bmdLayoutDrawerEl = document.querySelector(".bmd-layout-drawer");
    if (bmdLayoutDrawerEl) {
      bmdLayoutDrawerEl.style.display = "none";
    }

    this._element.style.display = "";

    this.$toggles.forEach((toggle) => {
      Manipulator.setAttributeNoBs(toggle, "aria-expanded", true);
    });

    Manipulator.setAttributeNoBs(this._element, "aria-expanded", true);
    Manipulator.setAttributeNoBs(this._element, "aria-hidden", false);

    // focus on the first focusable item   
    let $focusOn = this._element.querySelector(this.config.focusSelector);

    if ($focusOn) {
      //$focusOn.first().focus();
      $focusOn.focus();
    }

    Manipulator.addClass(this.$container, ClassName.DRAWER_IN);
    Manipulator.addClass(this.$backdrop, ClassName.IN);
  }

  hide() {
    if (!this._isOpen()) {
      return;
    }

    this.$toggles.forEach((toggle) => {
      Manipulator.setAttributeNoBs(toggle, "aria-expanded", false);
    });
    Manipulator.setAttributeNoBs(this._element, "aria-expanded", false);
    Manipulator.setAttributeNoBs(this._element, "aria-hidden", true);

    Manipulator.removeClass(this.$container, ClassName.DRAWER_IN);
    Manipulator.removeClass(this.$backdrop, ClassName.IN);
  }

  // ------------------------------------------------------------------------
  // private

  _isOpen() {    
    return Manipulator.hasClass(this.$container, ClassName.DRAWER_IN);
  }

  _isForcedClosed() {    
    return Manipulator.hasClass(this.$container, ClassName.DRAWER_OUT);
  }

  _addAria() {
    let isOpen = this._isOpen();
    Manipulator.setAttributeNoBs(this._element, "aria-expanded", isOpen);
    Manipulator.setAttributeNoBs(this._element, "aria-hidden", isOpen);

    if (this.$toggles.length) {
      this.$toggles.forEach((toggle) => {
        Manipulator.setAttributeNoBs(toggle, "aria-expanded", isOpen);
      });
    }
  }

  // ------------------------------------------------------------------------
  // static

  static elementInterface($element, config) {
    const data =
      Data.getData($element, DATA_KEY) || new Drawer($element, config);
    if (typeof config === "string") {
      if (typeof data[config] === "undefined") {
        throw new TypeError(`No method named "${config}"`);
      }

      data[config]();
    }
    return data;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

EventHandler.on(
  document,
  EVENT_CLICK_DATA_API,
  SELECTOR_DRAWER_CLOSE_EL,
  function () {
    // event.preventDefault();
    const driverEl = this.closest(SELECTOR_DRAWER);
    if (driverEl) {
      const data = Data.getData(driverEl, DATA_KEY) || new Drawer(driverEl);
      data.hide();
    }
  }
);



export default Drawer;
