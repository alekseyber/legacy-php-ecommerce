import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";
import { APP_NAME_SPACE, EVENT_MAIN_TOGGLE, NAME_KEY } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.mainclasstoggler`;
// const EVENT_KEY = `.${DATA_KEY}`;
// const DATA_API_KEY = ".data-api";

const CLASS_NAME_ACTIVE = "main-p";

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */

class AppMainClassToggler extends BaseComponent {
  // Public

  start() {
    const { pathname } = document.location;
    this._setClass(pathname);
  }

  // Private

  _setClass(pathname) {
    const classStatus = Manipulator.hasClass(this._element, CLASS_NAME_ACTIVE);
    const mainPageStatus = pathname === "/";

    if (classStatus === mainPageStatus) return;
    const action = mainPageStatus ? "addClass" : "removeClass";
    Manipulator[action](this._element, CLASS_NAME_ACTIVE);
  }

  // Static

  static elementInterface(config) {
    // const $element = document.querySelector(SELECTOR_ELEMENT);
    // if (!$element) {
    //   return console.error(`AppMainClassToggler - элемент ${SELECTOR_ELEMENT} - не найден`);
    // }
    const $element = document.body;
    const data =
      Data.getData($element, DATA_KEY, NAME_KEY) ||
      new AppMainClassToggler($element, config);
    if (data) data.start();

    return data;
  }

  static init() {
    AppMainClassToggler.elementInterface();
    // window.onpopstate = AppMainClassToggler.elementInterface;
    EventHandler.on(
      document,
      EVENT_MAIN_TOGGLE,
      null,
      AppMainClassToggler.elementInterface
    );
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppMainClassToggler;
