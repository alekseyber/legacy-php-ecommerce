import Data from "@Dom/data";
import BaseComponent from "./base-app-component";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import SelectorEngine from "@Dom/selector-engine";
import { APP_NAME_SPACE } from "@Constans";

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

const SELECTOR_SIZE_BTN = '[data-selector="sizes-sel-btn"]'; //".sizes-selector .btn";
const SELECTOR_CONTEINER_FORM = ".js-add-card-form";
const SELECTOR_INPUT = '[name="cardData[pid]"]';
const SELECTOR_NO_SIZE_INPUT = '[name="cardData[nosizeselect]"';
const SELECTOR_CONTEINER_SIZE = ".sizes-selector";
const SELECTOR_IN_SIZE_BTN_ACTIVE = `${SELECTOR_SIZE_BTN}.${CLASS_NAME_ACTIVE}`;

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {};

class AppSizeSelect extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    try {
      this._activate();
    } catch (e) {
      this._logError(e);
    }
  }
  // Public

  setActive() {
    if (this._element.classList.contains(CLASS_NAME_ACTIVE)) return;
    this._removeActiveBtn();
    this._inputEl.value = this._pid;

    if (this._inputNoSIzeEl) {
      this._inputNoSIzeEl.value = "0";
    }
    Manipulator.addClass(this._element, CLASS_NAME_ACTIVE);
  }

  dispose() {
    super.dispose();
    this._pid = null;
    this._inputEl = null;
    this._inputNoSIzeEl = null;
    this._btnConteiner = null;
  }

  // private

  _activate() {
    this._pid = Manipulator.getDataAttributeNoBs(this._element, "pid");

    if (!this._pid) {
      this._setError("Отсутствует обязательный атрибут - pid");
    }
    const formEl = this._getParrentElement(SELECTOR_CONTEINER_FORM, this._element);

    this._inputEl = this._getChildElement(SELECTOR_INPUT, formEl);
    this._inputNoSIzeEl = this._getChildElement(
      SELECTOR_NO_SIZE_INPUT,
      formEl,
      false
    );
    this._btnConteiner = this._getParrentElement(SELECTOR_CONTEINER_SIZE, this._element);
  }
  _removeActiveBtn() {
    const $elements = SelectorEngine.find(
      SELECTOR_IN_SIZE_BTN_ACTIVE,
      this._btnConteiner
    );
    if (!$elements) return;
    $elements.forEach((item) => {
      Manipulator.removeClass(item, CLASS_NAME_ACTIVE);
    });
  }



  // Static

  static init() {
    EventHandler.on(
      document,
      EVENT_CLICK_DATA_API,
      SELECTOR_SIZE_BTN,
      (event) => {
        AppSizeSelect.elementInterface(event.target, "setActive");
      }
    );
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppSizeSelect;
