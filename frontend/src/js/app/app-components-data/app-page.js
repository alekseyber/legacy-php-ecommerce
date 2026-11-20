import EventHandler from "@Dom/event-handler";
import Manipulator from "@Dom/manipulator";
import AppBaseData from "./app-base-data";
import SelectorEngine from "@Dom/selector-engine";

import {
  APP_NAME_SPACE,
  APP_PAGE_KEY,
  EVENT_SCROLL_TOP,
  SELECTOR_PAGE_CONTEINER_GLOBAL,
  SELECTOR_FILTER_GLOBAL,
  SELECTOR_IN_CONTENT_GLOBAL,
  MODE_STATUS,
  EVENT_SLIDER_CHANGE,
  EVENT_APP_FILTER_ACTIVE_SET,
  EVENT_JS_L_TOGGLE,
  EVENT_APP_MENU_ACTIVE_SET,
} from "@Constans/index";
import { ltrim } from "@AppUtil/util";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "appPage";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

const EVENT_CLICK = `click${EVENT_KEY}`;

const SELECTOR_NEXT_PAGIN_TOGGLER = ".next-pagination-btn";
const SELECTOR_PAGIN_TOGGLER = ".js-link-pagin";
const NAME_DATA_KEY_TARGET = "href";
// const SELECTOR_IN_CONTENT = '[data-selector="app-in-load-el"]';

const SELECTOR_NEXT_PAGIN_CONTEINER = ".next-pagin-bl";
const SELECTOR_NEXT_PAGIN_BTN = ".next-pagination";
const SELECTOR_PAGIN_CONTEINER = ".pagin-cont";

const SELECTOR_SORT_TOGGLER = ".js-sort";
const SELECTOR_SORT_DROWN_CONTEINER = ".dropdown-menu";
const DISABLED_CLASS = "disabled";
const SELECTOR_SORT_DROWN_ITEM_DISABLED = `.dropdown-item.${DISABLED_CLASS}`;

const SELECTOR_IN_CONTENT_FILTER = '[data-selector="app-in-load-filter"]';

const SELECTOR_JS_LINK_TOGGLER = ".js-link-true";
const SELECTOR_JS_LINK2_TOGGLER = ".js-link-true2";
const SELECTOR_JS_NO_LINK = '[data-selector="js-link-btn"]'; 

const URL_ORDER_DONE = "/cart/orderdone";

//const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppPage extends AppBaseData {
  // Public

  dispose() {
    super.dispose();
    this._pageConteiner = null;
    this._filterConteiner = null;
  }

  // ------------------------------------------------------------------------
  // private

  async _getContent(url, get = false) {
    try {
      this._appLoading(true);
      let rezult;
      if (get) {
        rezult = await this.fetchApp.get(url);
      } else {
        const data = new FormData();
        data.append("pagin", true);
        rezult = await this.fetchApp.post(url, data);
      }

      const { content } = rezult;

      if (content === undefined) {
        throw new Error("Контент не получен.");
      }
      return content;
    } catch (e) {
      this._logError(e);
      this._setError(`AppPage - ${e.message}`);
    } finally {
      this._appLoading(false);
    }
  }

  // ------------------------------------------------------------------------
  // protected

  _fadeContent(element, content, complite) {
    const fadeIn = () => {
      element.innerHTML = content;
      Manipulator.fadeIn(element, complite, false);
    };
    Manipulator.fadeOut(element, fadeIn, true);
  }

  _getElement(event, selector) {
    let element = event.target;
    let url;
    if (!element.matches(selector)) {
      element = element.closest(selector);
    }
    if (element instanceof HTMLAnchorElement) {
      event.preventDefault();
      url = Manipulator.getAttribute(element, "href");
    } else {
      url = Manipulator.getDataAttributeNoBs(element, NAME_DATA_KEY_TARGET);
    }

    if (!url) {
      this._setError(
        `AppPage - отсутствует обязательный атрибут ${NAME_DATA_KEY_TARGET}`
      );
    }
    url = "/" + ltrim(url);

    const disabled = Manipulator.hasClass(element, DISABLED_CLASS);

    return { url, disabled, element };
  }
  async _nextPaginHangler(event) {
    const { url } = this._getElement(event, SELECTOR_NEXT_PAGIN_TOGGLER);
    const content = await this._getContent(url);
    if (!content) return;

    const conteiner = this._getChildElement(
      SELECTOR_NEXT_PAGIN_CONTEINER,
      this._pageConteiner
    );
    const nextPaginBtn = this._getChildElement(
      SELECTOR_NEXT_PAGIN_BTN,
      conteiner,
      false
    );
    Manipulator.detach(nextPaginBtn);
    conteiner.insertAdjacentHTML("beforeend", content);
  }
  async _paginHangler(event) {
    try {
      const { url } = this._getElement(event, SELECTOR_PAGIN_TOGGLER);
      const content = await this._getContent(url);
      const conteiner = this._getChildElement(
        SELECTOR_PAGIN_CONTEINER,
        this._pageConteiner
      );
      //  conteiner.innerHTML = content;
      const complite = () => {
        const targetEl = this._getChildElement(
          SELECTOR_IN_CONTENT_GLOBAL,
          conteiner,
          false
        );
        this._triggerModeSatatus(targetEl);
        EventHandler.trigger(conteiner, EVENT_SCROLL_TOP);
      };
      this._fadeContent(conteiner, content, complite);
    } catch (e) {
      this._logError(e);
    }
  }

  async _sortHangler(event) {
    try {
      const { url, disabled, element } = this._getElement(
        event,
        SELECTOR_SORT_TOGGLER
      );
      if (disabled) return;
      const content = await this._getContent(url, true);
      const conteiner = this._getChildElement(
        SELECTOR_PAGIN_CONTEINER,
        this._pageConteiner
      );

      const complite = () => {
        const targetEl = this._getChildElement(
          SELECTOR_IN_CONTENT_GLOBAL,
          conteiner,
          false
        );
        this._triggerModeSatatus(targetEl);

        EventHandler.trigger(conteiner, EVENT_SCROLL_TOP);

        const drown = this._getParrentElement(
          SELECTOR_SORT_DROWN_CONTEINER,
          element,
          true
        );

        SelectorEngine.find(SELECTOR_SORT_DROWN_ITEM_DISABLED, drown).forEach(
          (item) => {
            Manipulator.removeClass(item, DISABLED_CLASS);
          }
        );
        Manipulator.addClass(element, DISABLED_CLASS);
      };

      this._fadeContent(conteiner, content, complite);
    } catch (e) {
      this._logError(e);
    }
  }

  _updateFilter(content) {
    try {
      //if (!content) return;
      if (!this._filterConteiner)
        this._setError("Отсуствует filter contenteiner");
      this._filterConteiner.innerHTML = content;
      if (!content) return;
      const targetEl = this._getChildElement(
        SELECTOR_IN_CONTENT_FILTER,
        this._filterConteiner
      );
      this._triggerModeSatatus(targetEl, MODE_STATUS.FILTER);
      EventHandler.trigger(targetEl, EVENT_APP_FILTER_ACTIVE_SET);
    } catch (e) {
      this._logError(e);
    }
  }
  _jsFunc(jsContent) {
    try {
      if (!jsContent) return;
      if (!jsContent.body) return;
      const args = jsContent.args !== undefined ? jsContent.args : [];
      const argsNames =
        jsContent.argsNames !== undefined ? jsContent.argsNames : [];
      const func = new Function(argsNames, jsContent.body);
      func.apply(null, args);
    } catch (e) {
      this._logError(e);
    }
  }

  async _jsLinkRequest(url, onOnpostat = true) {
    try {
      if (!this._pageConteiner) {
        this._setError(`AppPage jsLinkRequest - PageConteiner не найден.`);
      }
      this._appLoading(true);
      const response = await this.fetchApp.post(url);

      const { content, historyset, scrolltop, js, filter, data } = response;

      if (content === undefined) {
        this._setError(`AppPage jsLinkRequest - контент не получен с сервера`);
      }
      const complite = () => {
        const targetEl = this._getChildElement(
          SELECTOR_IN_CONTENT_GLOBAL,
          this._pageConteiner
        );
        this._triggerModeSatatus(targetEl, null, data);
        if (scrolltop) {
          EventHandler.trigger(this._pageConteiner, EVENT_SCROLL_TOP);
        }

        if (historyset && onOnpostat) {
          history.pushState({ note: "main" }, "", url);
          //  EventHandler.trigger(this._pageConteiner, EVENT_MAIN_TOGGLE);
          EventHandler.trigger(this._pageConteiner, EVENT_APP_MENU_ACTIVE_SET);
        }

        if (filter !== undefined) {
          this._updateFilter(filter);
        }

        if (js) {
          this._jsFunc(js);
        }
      };

      this._fadeContent(this._pageConteiner, content, complite);
    } catch (e) {
      this._logError(e);
      // window.location = url;
    } finally {
      this._appLoading(false);
    }
  }

  _jsLinkHangler(selector, event) {
    const { url, disabled } = this._getElement(event, selector);
    if (disabled) return;
    this._jsLinkRequest(url);
  }

  _jsNoLinkHangler(selector, event) {
    const { url, disabled } = this._getElement(event, selector);
    if (disabled) return;

    if (!window.jsLOff) {
      this._jsLinkRequest(url);
    } else {
      window.location = url;
      //  window.open(url);
    }
  }

  _jsEventLinkHangler({ detail }) {
    this._jsLinkRequest(detail.url, detail.onOnpostat);
  }

  _priceSliderHandler({ detail }) {
    //event
    if (!detail.data) return;

    const { url } = detail.data;

    if (!window.jsLOff2) {
      return this._jsLinkRequest(url, true);
    }
    window.location = url;
  }

  async _init() {
    this._addListners();
    this._pageConteiner = this._getChildElement(SELECTOR_PAGE_CONTEINER_GLOBAL);
    this._filterConteiner = this._getChildElement(SELECTOR_FILTER_GLOBAL);
    this._addOnPopstat();
  }

  _addOnPopstat() {
    window.onpopstate = () => {
      if (document.location.pathname === URL_ORDER_DONE) {
        return;
      }     
      //  event.state
      if (!window.jsLOff) {
        return this._jsLinkRequest(document.location.pathname, false);
      }
      document.location.reload();
    };
  }

  _addListners(action = "on") {
    EventHandler[action](
      document,
      EVENT_CLICK,
      SELECTOR_NEXT_PAGIN_TOGGLER,
      this._nextPaginHangler.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_CLICK,
      SELECTOR_SORT_TOGGLER,
      this._sortHangler.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_SLIDER_CHANGE,
      null,
      this._priceSliderHandler.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_CLICK,
      SELECTOR_JS_NO_LINK,
      this._jsNoLinkHangler.bind(this, SELECTOR_JS_NO_LINK)
    );
    EventHandler[action](
      document,
      EVENT_JS_L_TOGGLE,
      null,
      this._jsEventLinkHangler.bind(this)
    );

    if (!window.jsLOff) {
      EventHandler[action](
        document,
        EVENT_CLICK,
        SELECTOR_PAGIN_TOGGLER,
        this._paginHangler.bind(this)
      );

      EventHandler[action](
        document,
        EVENT_CLICK,
        SELECTOR_JS_LINK_TOGGLER,
        this._jsLinkHangler.bind(this, SELECTOR_JS_LINK_TOGGLER)
      );
    }
    if (!window.jsLOff2) {
      EventHandler[action](
        document,
        EVENT_CLICK,
        SELECTOR_JS_LINK2_TOGGLER,
        this._jsLinkHangler.bind(this, SELECTOR_JS_LINK2_TOGGLER)
      );
    }
  }

  static get DATA_KEY() {
    return APP_PAGE_KEY;
  }
}

export default AppPage;
