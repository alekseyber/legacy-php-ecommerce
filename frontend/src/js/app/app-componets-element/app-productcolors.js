import Data from "@Dom/data";
import EventHandler from "@Dom/event-handler";
import SelectorEngine from "@Dom/selector-engine";
import Manipulator from "@Dom/manipulator";
import BaseComponent from "./base-app-component";
import { APP_NAME_SPACE, NAME_KEY } from "@Constans";
//import { isTouchDevice } from "@AppUtil/util";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.productcolorset`;
const EVENT_KEY = `.${DATA_KEY}`;
const DATA_API_KEY = ".data-api";

const EVENT_CLICK_DATA_API = `click${EVENT_KEY}${DATA_API_KEY}`;
//const EVENT_MOUSENTER_DATA_API = `mouseenter${EVENT_KEY}${DATA_API_KEY}`;

const DATA_ATTR_LIST = [
  {
    name: "src",
    reqired: true,
  },
  {
    name: "alt",
    reqired: false,
  },
  {
    name: "srcset",
    reqired: true,
  },
  {
    name: "badgeon",
    reqired: false,
  },
  {
    name: "badgesale",
    reqired: false,
  },
  {
    name: "badge",
    reqired: false,
  },
  {
    name: "thumb",
    reqired: true,
  },
  {
    name: "price",
    reqired: false,
    int: true,
  },
  {
    name: "oldprice",
    reqired: false,
    int: true,
  },
  {
    name: "href",
    reqired: false,
  },
];

const CLASS_NAME_ACTIVE = "selected";
const CLASS_NAME_DISABLED = "disabled";

const SELECTOR_COLOR_BTN = '[data-selector="product-item-clr-btn"]'; //".js-colors-btn";
const SELECTOR_COLORS_CONTEINER = '[data-selector="product-item-clr-wrap"]'; //".colors-body";
const SELECTOR_ACTIVE = `.${CLASS_NAME_ACTIVE}`;
const SELECTOR_PRODUCT_CONTEINER = '[data-selector="product-item"]'; //.product
const SELECTOR_MEDIA_CONTEINER = '[data-selector="product-itemm-media"]'; //.pr-media
const SELECTOR_PRODUCT_BAGE = '[data-selector="pr-bage"]'; //".pr-bage"
const SELECTOR_PRICE_VALUE = '[data-selector="price-value"]'; //".price > .value";
const SELECTOR_OLD_PRICE_VALUE = '[data-selector="old-price-value"]'; // ".old-price > .value";
const SELECTOR_HREF_EDIT_L = '[data-selector="js-l-edit"]'; //".js-l-edit";
const SELECTOR_HREF_EDIT_B = '[data-selector="js-b-edit"]'; // ".js-b-edit";

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */

class AppProductColors extends BaseComponent {
  // Public

  show() {
    if (
      this._element.classList.contains(CLASS_NAME_ACTIVE) ||
      this._element.classList.contains(CLASS_NAME_DISABLED)
    ) {
      return;
    }
    this._activate();
  }

  // Private

  _getElementData() {
    let error = "";
    const elementData = {};

    const elmentsCandidat = Manipulator.getDataAttributesNoBs(this._element);
    for (let item of DATA_ATTR_LIST) {
      let itemEl = elmentsCandidat[item.name];
      if (!itemEl && DATA_ATTR_LIST[item.reqired]) {
        error += ` ${item.name}`;
        continue;
      }
      if (itemEl === undefined) itemEl === "";
      if (item.int) {
        itemEl = parseInt(itemEl);
        itemEl = isNaN(itemEl) ? 0 : itemEl;
      }
      elementData[item.name] = itemEl;
    }
    if (
      !elementData.href &&
      this._element instanceof HTMLAnchorElement &&
      !error
    ) {
      elementData.href = Manipulator.getAttribute(this._element, "href");
    }
    if (error) {
      this._setError(`Failed to find field data ${error}`);
    }

    return elementData;
  }

  _getProductMedia({ badgeon, badgesale, badge, thumb }) {
    let bageContent = "";
    const { src, alt, srcset } = JSON.parse(thumb);

    if (badgeon) {
      const className = badgesale ? " primary" : "";
      bageContent = `<div class="product-item_bage${className}">${badge}</div>`;
    }

    if (window.webp) {
      const type = /\.jpg/i.test(src) ? "image/jpeg" : "image/png";
      const re = new RegExp(".(png|jpg)", "i");
      const srcsetWebP = srcset.replace(re, ".webp");

      const mediaWebPContent = `<picture>
              <source srcset='${srcsetWebP}' type='image/webp'>
              <source srcset='${srcset}' type='${type}'>
              <img class="card-img-top" src='${src}' alt='${alt}'>
          </picture>`;

      return {
        media: mediaWebPContent,
        bageContent,
      };
    }
    return {
      media: `<img class="card-img-top" src='${src}' srcset='${srcset}' alt='${alt}'>`,
      bageContent,
    };
  }

  _activate() {
    const elementData = this._getElementData();

    const elementPr = this._getParrentElement(
      SELECTOR_PRODUCT_CONTEINER,
      this._element
    );
    const elPrMedia = this._getChildElement(
      SELECTOR_MEDIA_CONTEINER,
      elementPr
    );
    const elColorsBody = this._getParrentElement(
      SELECTOR_COLORS_CONTEINER,
      this._element
    );
    const elBage = this._getChildElement(
      SELECTOR_PRODUCT_BAGE,
      elementPr,
      false
    );

    const complete = () => {
      SelectorEngine.find(SELECTOR_ACTIVE, elColorsBody).forEach((itemSelect) =>
        itemSelect.classList.remove(CLASS_NAME_ACTIVE)
      );

      this._element.classList.add(CLASS_NAME_ACTIVE);
    };
    const productMedia = this._getProductMedia(elementData);

    this._transitionComplete(
      elementPr,
      elPrMedia,
      elBage,
      productMedia,
      elementData,
      complete
    );
  }

  _transitionComplete(
    elementPr,
    elPrMedia,
    elBage,
    productMedia,
    elementData,
    complete
  ) {
    const height = elPrMedia.scrollHeight;

    Manipulator.css(elPrMedia, { minHeight: `${height}px` });

    if (elBage) {
      elBage.innerHTML = productMedia.bageContent;
    }

    const completeFadeOut = () => {
      elPrMedia.innerHTML = productMedia.media;
      if (elementData.price) {
        const elPrice = SelectorEngine.findOne(SELECTOR_PRICE_VALUE, elementPr);
        if (elPrice) {
          elPrice.textContent = elementData.price;
        }
      }
      if (elementData.oldpPice) {
        const elOldPrice = SelectorEngine.findOne(
          SELECTOR_OLD_PRICE_VALUE,
          elementPr
        );
        if (elOldPrice) {
          elOldPrice.textContent = elementData.price;
        }
      }

      const elLedit = SelectorEngine.findOne(SELECTOR_HREF_EDIT_L, elementPr);
      Manipulator.setAttributeNoBs(elLedit, "href", elementData.href);
      const elBedit = SelectorEngine.findOne(SELECTOR_HREF_EDIT_B, elementPr);

      Manipulator.setDataAttributeNoBs(elBedit, "href", elementData.href);
      Manipulator.fadeIn(elPrMedia, complete, null);
    };
    Manipulator.fadeOut(elPrMedia, completeFadeOut, true);
  }

  // Static

  static clickHangler(event) {
    event.preventDefault();
    const data =
      Data.getData(this, DATA_KEY, NAME_KEY) ||
      new AppProductColors(event.target);
    if (data) {
      data.show();
    } else {
      this._setError(`clickHangler Failed to find element from event.target`);
    }
  }

  static init() {
    EventHandler.on(
      document,
      EVENT_CLICK_DATA_API,
      SELECTOR_COLOR_BTN,
      AppProductColors.clickHangler
    );
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppProductColors;
