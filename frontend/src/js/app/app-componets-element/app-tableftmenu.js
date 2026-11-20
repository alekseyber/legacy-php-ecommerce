import Url from "url-parse";

import Data from "@Dom/data";
import EventHandler from "@Dom/event-handler";
import SelectorEngine from "@Dom/selector-engine";
import Manipulator from "@Dom/manipulator";
import BaseComponent from "./base-app-component";
import DataApp from "@AppUtil/data-app";
import {
  APP_NAME_SPACE,
  APP_FILTER_DATA_KEY,
  EVENT_APP_FILTER_ACTIVE_SET,
  EVENT_APP_MENU_ACTIVE_SET,
  NAME_KEY,
} from "@Constans";
import { getElementFromSelector, reflow } from "@AppUtil/util";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const NAME = "tableftmenu";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;
const DATA_API_KEY = ".data-api";

const EVENT_HIDE = `hide${EVENT_KEY}`;
const EVENT_HIDDEN = `hidden${EVENT_KEY}`;
const EVENT_SHOW = `show${EVENT_KEY}`;
const EVENT_SHOWN = `shown${EVENT_KEY}`;
const EVENT_CLICK_DATA_API = `click${EVENT_KEY}${DATA_API_KEY}`;

const CLASS_NAME_ACTIVE = "active";
const CLASS_NAME_DISABLED = "disabled";
// const CLASS_NAME_FADE = "fade";
// const CLASS_NAME_SHOW = "show";

const SELECTOR_NAV_LIST_GROUP = '[data-selector="tableftmenugrupp"]'; //".nav, .list-group"
// const SELECTOR_ACTIVE = ".active";
const SELECTOR_ACTIVE_CONTENT = ".active:not([data-bs-toggle='tableftmenu'])";
const SELECTOR_ACTIVE_BTN = ".active[data-bs-toggle='tableftmenu']";
const SELECTOR_DATA_TOGGLE = '[data-bs-toggle="tableftmenu"]';
const SELECTOR_LEFT_CATEGORY = '[data-bs-leftcatalog="true"]';
const SELECTOR_ACTIVE_LINK = `a.${CLASS_NAME_DISABLED}`;
const SELECTOR_TAB_PANEL = ".tab-pane";
//const SELECTOR_LEFT_FILTER = '[data-bs-leftfilter="true"]';

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */

class AppTabLeftMenu extends BaseComponent {
  // Public

  show() {
    if (
      (this._element.parentNode &&
        this._element.parentNode.nodeType === Node.ELEMENT_NODE &&
        this._element.classList.contains(CLASS_NAME_ACTIVE)) ||
      this._element.classList.contains(CLASS_NAME_DISABLED)
    ) {
      return;
    }

    let previous;
    const target = getElementFromSelector(this._element);
    const listElement = this._element.closest(SELECTOR_NAV_LIST_GROUP);

    if (listElement) {
      const itemSelector = SELECTOR_ACTIVE_BTN;
      previous = SelectorEngine.find(itemSelector, listElement);
      previous = previous[previous.length - 1];

      const leftfilter = Manipulator.getDataAttribute(
        listElement,
        "leftfilter"
      );
      if (leftfilter) {
        const bsTarget = Manipulator.getDataAttribute(this._element, "target");
        DataApp.setData(APP_FILTER_DATA_KEY, bsTarget);
      }
    }

    const hideEvent = previous
      ? EventHandler.trigger(previous, EVENT_HIDE, {
          relatedTarget: this._element,
        })
      : null;

    const showEvent = EventHandler.trigger(this._element, EVENT_SHOW, {
      relatedTarget: previous,
    });

    if (
      showEvent.defaultPrevented ||
      (hideEvent !== null && hideEvent.defaultPrevented)
    ) {
      return;
    }

    this._activate(this._element, listElement);

    const complete = () => {
      EventHandler.trigger(previous, EVENT_HIDDEN, {
        relatedTarget: this._element,
      });
      EventHandler.trigger(this._element, EVENT_SHOWN, {
        relatedTarget: previous,
      });
    };

    if (target) {
      this._activate(target, listElement, complete, true);
    } else {
      complete();
    }
  }

  // Private

  _activate(element, container, callback, isContent = false) {
    const selector = isContent ? SELECTOR_ACTIVE_CONTENT : SELECTOR_ACTIVE_BTN;

    const activeElements = SelectorEngine.find(selector, container);

    const active = activeElements[0];

    const complete = () => this._transitionComplete(element, active, callback);

    complete();
  }

  _transitionComplete(element, active, callback) {
    if (active) {
      active.classList.remove(CLASS_NAME_ACTIVE);

      if (active.getAttribute("role") === "tab") {
        active.setAttribute("aria-selected", false);
      }
    }

    element.classList.add(CLASS_NAME_ACTIVE);
    if (element.getAttribute("role") === "tab") {
      element.setAttribute("aria-selected", true);
    }

    reflow(element);

    if (callback) {
      callback();
    }
  }

  // Static

  static menuUrlActiveSet() {
    const { pathname } = document.location;

    const container = document.body.querySelector(SELECTOR_LEFT_CATEGORY);
    if (!container) return;

    const carrentLinkElement = container.querySelector(SELECTOR_ACTIVE_LINK);
    const carrentLinkHref = Manipulator.getAttribute(
      carrentLinkElement,
      "href"
    );
    if (carrentLinkHref) {
      const url = new Url(carrentLinkHref);
      if (url.pathname === pathname) return;
      Manipulator.removeClass(carrentLinkElement, CLASS_NAME_DISABLED);
    }

    const newLinkElement = container.querySelector(`[href="${pathname}"]`);

    let tabToggelElement;

    if (newLinkElement) {
      Manipulator.addClass(newLinkElement, CLASS_NAME_DISABLED);
      const tabPanelEl = newLinkElement.closest(SELECTOR_TAB_PANEL);
      tabToggelElement = SelectorEngine.findByTarget(tabPanelEl, container);
    }

    if (!tabToggelElement) {
      const defaultTabTarget = Manipulator.getDataAttribute(
        container,
        "def-target"
      );

      const homeTabPanelEl = container.querySelector(defaultTabTarget);
      if (Manipulator.hasClass(homeTabPanelEl, CLASS_NAME_ACTIVE)) return;

      tabToggelElement = SelectorEngine.findByTarget(
        defaultTabTarget,
        container
      );
    }
    if (tabToggelElement) {
      const data =
        Data.getData(tabToggelElement, DATA_KEY, NAME_KEY) ||
        new AppTabLeftMenu(tabToggelElement);
      data.show();
    }
  }

  static filterActiveSet(event) {
    const targetCarrent = DataApp.getData(APP_FILTER_DATA_KEY);
    if (!targetCarrent) return;
    const filterElement = event.target;
    const element = SelectorEngine.findOne(
      `[data-bs-target="${targetCarrent}"]`,
      filterElement
    );
    if (!element) {
      return DataApp.removeData(APP_FILTER_DATA_KEY);
    }
    AppTabLeftMenu.elementInterface(element, "show");
  }

  static init() {
    EventHandler.on(
      document,
      EVENT_CLICK_DATA_API,
      SELECTOR_DATA_TOGGLE,
      function (event) {
        event.preventDefault();
        AppTabLeftMenu.elementInterface(this, "show");
      }
    );

    AppTabLeftMenu.menuUrlActiveSet();

    EventHandler.on(
      document,
      EVENT_APP_MENU_ACTIVE_SET,
      null,
      AppTabLeftMenu.menuUrlActiveSet
    );

    EventHandler.on(
      document,
      EVENT_APP_FILTER_ACTIVE_SET,
      null,
      AppTabLeftMenu.filterActiveSet
    );
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

/**
 * ------------------------------------------------------------------------
 * Data Api implementation
 * ------------------------------------------------------------------------
 */

export default AppTabLeftMenu;
