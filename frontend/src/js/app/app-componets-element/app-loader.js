import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";
import { EVENT_APP_LOADER, APP_NAME_SPACE } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.apploader`;
const CLASS_NAME_ACTIVE = "onvisible";
const SELECTOR_ELEMENT = ".preloader";
const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */

class AppLoader extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      this._addListners();
    } catch (e) {
      this._logError(e);
    }
  }
  // Public

  show() {
    Manipulator.addClass(this._element, CLASS_NAME_ACTIVE);
  }
  hide() {
    Manipulator.removeClass(this._element, CLASS_NAME_ACTIVE);
  }
  dispose() {
    super.dispose();
    this._addListners("off");
  }
  // Private
  _loaderHangler({ detail }) {
    if (detail.name && this[detail.name]) {
      this[detail.name]();
    }
  }
  _addListners(action = "on") {
    EventHandler[action](
      document,
      EVENT_APP_LOADER,
      null,
      this._loaderHangler.bind(this)
    );
  }

  // Static

  static init(config) {
    const element = document.querySelector(SELECTOR_ELEMENT);
    AppLoader.elementInterface(element, config);
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppLoader;
