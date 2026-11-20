import Data from "@Dom/data";
import BaseComponent from "./base-app-component";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";

import {
  APP_NAME_SPACE,
  EVENT_AJAX_CONTENT_LOADED,
  MODE_STATUS,
  SELECTOR_IN_PRODUCT_GLOBAL,
  EVENT_SCROLL_TOP,
} from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.sizeselect`;
const EVENT_KEY = `.${DATA_KEY}`;
const DATA_API_KEY = ".data-api";

const EVENT_CLICK_DATA_API = `click${EVENT_KEY}${DATA_API_KEY}`;

const CLASS_NAME_ACTIVE = "active";
const CLASS_NAME_DISABLED = "disabled";

const SELECTOR_PRODUCT_COLOR_BTN = ".js-sw-clr-pr";
const SELECTOR_CONTEINER = ".colors-pr";
const SELECTOR_OUT_CONTEINER = '[data-selector="main-product"]';
const CONTENT_IN_SELECTOR = SELECTOR_IN_PRODUCT_GLOBAL;
const SELECTOR_JS_LINK_OPEN = ".js-l-open";

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {};

class AppMainProduct extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    try {
      this._activate();
    } catch (e) {
      this._logError(e);
    }
  }
  // Public

  setColor() {
    if (
      this._element.classList.contains(CLASS_NAME_ACTIVE) ||
      this._conteiner.classList.contains(CLASS_NAME_DISABLED)
    )
      return;

    this._request();
  }

  dispose() {
    super.dispose();
    this._href = null;
    this._conteiner = null;
    this._data = null;
    this._out_conteiner = null;
  }

  // private

  async _request() {
    try {
      Manipulator.addClass(this._conteiner, CLASS_NAME_DISABLED);

      const { content } = await this.fetchApp.post(this._href, this._data);

      if (content) {
        Manipulator.fadeOut(
          this._out_conteiner,
          this._updateContent.bind(this, content),
          true
        );
      }
    } catch (e) {
      this._logError(e);
    } finally {
      Manipulator.removeClass(this._conteiner, CLASS_NAME_DISABLED);
    }
  }

  _updateContent(content) {
    // this._removeActiveBtn();
    // Manipulator.addClass(this._element, CLASS_NAME_ACTIVE);
    const height = this._out_conteiner.scrollHeight;
    this._out_conteiner.innerHTML = content;
    Manipulator.css(this._out_conteiner, { minHeight: `${height}px` });

    Manipulator.fadeIn(
      this._out_conteiner,
      this._triggerSuccess.bind(this),
      false
    );
    //this._triggerSuccess();

    setTimeout(() => {
      Manipulator.css(this._out_conteiner, { minHeight: "auto" });
    }, 1000);
  }
  _triggerSuccess() {
    EventHandler.trigger(this._out_conteiner, EVENT_SCROLL_TOP, {
      detail: {
        modal: this._getParrentElement(
          ".modal-body",
          this._out_conteiner,
          false
        ),
      },
    });

    const targetEl = this._getChildElement(
      CONTENT_IN_SELECTOR,
      this._out_conteiner
    );

    EventHandler.trigger(targetEl, EVENT_AJAX_CONTENT_LOADED, {
      detail: {
        modeStatus: MODE_STATUS.PRODUCT_MAIN,
      },
    });
  }

  _activate() {
    this._href = Manipulator.getAttribute(this._element, "href");
    const dataProductcard = Manipulator.getDataAttributeNoBs(
      this._element,
      "productcard"
    );

    this._data = {
      productcard: dataProductcard ? dataProductcard : "mainproduct",
    };

    this._conteiner = this._getParrentElement(
      SELECTOR_CONTEINER,
      this._element
    );
    this._out_conteiner = this._getParrentElement(
      SELECTOR_OUT_CONTEINER,
      this._element
    );
  }

  // Static

  static clickHangler(config, event) {
    event.preventDefault();
    const data = AppMainProduct.elementInterface(event.target, config);

    if (data) {
      data.setColor();
    }
  }

  static jsLinkOpenHangler(event) {
    let element = event.target;
    if (!element.matches(SELECTOR_JS_LINK_OPEN)) {
      element = element.closest(SELECTOR_JS_LINK_OPEN);
    }
    const href = Manipulator.getDataAttributeNoBs(element, "href");
    const blank = Manipulator.getDataAttributeNoBs(element, "blank");
    if (href) {
      // decodeURI(href)
      //encodeURI(href)
      window.open(decodeURI(href), blank);
    }
  }

  static init(config) {
    EventHandler.on(
      document,
      EVENT_CLICK_DATA_API,
      SELECTOR_PRODUCT_COLOR_BTN,
      AppMainProduct.clickHangler.bind(null, config)
    );
    EventHandler.on(
      document,
      EVENT_CLICK_DATA_API,
      SELECTOR_JS_LINK_OPEN,
      AppMainProduct.jsLinkOpenHangler
    );
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppMainProduct;
