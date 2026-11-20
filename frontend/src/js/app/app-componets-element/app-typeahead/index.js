import Dropdown from "./dropdown";
//import Dropdown from "bootstrap/js/src/dropdown";
import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import SelectorEngine from "@Dom/selector-engine";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "../base-app-component";
import AppSource from "@AppFetch/app-sorce";
import { APP_NAME_SPACE } from "@Constans";
import { throttle } from "@AppUtil/util";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "appTypeahead";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;
// const DATA_API_KEY = ".data-api";

const EVENT_CLICK = `click${EVENT_KEY}`;
const EVENT_INPUT = `input${NAME}${EVENT_KEY}`;

const ELEMENT_SELECTOR = '[data-selector="app-typeahead"]';
const INPUT_SELECTOR = '[data-selector="app-typeahead"] input[type=text]';

const BTN_CLEAR_SELECTOR = '[data-selector="app-typeahead-clear"]';

const IN_INPUT_SELECTOR = "input[type=text]";
const IN_DROPDROWN_CONTEINER_SELECTOR = ".dropdown-menu";
const IN_DROPDROWN_TOGGLE = '[data-bs-toggle="dropdown-t"]';
const IN_DROPDROWN_ITEM_SELECTOR = '[data-selector="item-t"]';
const IN_INPUT_RESET_GR = ".reset-btn";
const IN_BMB_HELP_SELECTOR = ".bmd-help";

const FILLED_CLASS = "is-filled";
const BTN_RESET_ON_CLASS = "show-reset";

const renderTemplate = (data = [], fnTitle = () => {}, divider = "") => {
  const list = data.map(
    (item, i) => `    
      <li><div class="dropdown-item" data-selector="item-t" data-bs-id="${i}">${fnTitle(
      item
    )}</div></li>
      `
  );

  return list.join("") + divider;
};

const notFoundTemplate = (text = "") => {
  return `    
 <li><div class="dropdown-item">${text}</div></li>
 `;
};

const Default = {
  throttleLimit: 1000,
  greatTitle: (item) => {
    return `${item.title}`;
  },
  getSelectedValue: (item) => {
    return `${item.title}`;
  },
  // removeValueOnSelected: true,
  divider: true,
  dividerCount: 7,
  dividerContent: `<li><hr class="dropdown-divider" /></li>
  <a class="dropdown-item js-link-true2" href="/search?s=%QUERY">Показать все</a>`,
  notFoundText: "Сожалеем, ни чего не найдено",
  minLength: 2,
  loadingText: "Поиск на сервере...",
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppTypeahead extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      this._getSource();
      this._activate();
      this._addListners();
    } catch (e) {
      console.error(e)
      this._logError(e);
    }
  }

  // Public
  eventInput(value) {
    this._setValue(value);
    if (this.value.length > this.config.minLength) {
      if (this.source) {
        this.source.setValue(value);
      }
    }
  }

  setList(list = []) {
    this._list = list;
    
    this.clearSelected();
    this._render();    
    if (list.length) {
      this._dropdownOn();
    } else {
      this._dropdownOff();
    }
  }
  clearList() {
    this.setList([]);
  }

  clearSelected() {
    this.selected = null;
  }
  setNotFound() {
    Manipulator.innerHTML(
      this._dropdownConteiner,
      notFoundTemplate(this.config.notFoundText)
    );
    this._dropdownOn();
  }
  setConfig(config) {
    this.config = { ...this.config, ...config };
  }
  setLoading(status) {
    const text = status ? this.config.loadingText : "";
    Manipulator.innerText(this._bmdHelp, text);
  }
  setError(error = "") {
    if (this.isError != error.length > 0) {
      this.isError = error.length > 0;
      const text = this.isError ? error : "";

      Manipulator.innerText(this._bmdHelp, text);
    }
  }
  clearValue() {
    this._clearValue();
  }

  dispose() {
    super.dispose();
    this._addListners("off");
    this.dropdown.dispose();
    this.dropdown = null;
    this._input = null;
    this._dropdownConteiner = null;
    this._list = [];
    this.selected = null;
    this.value = "";
    this._dropdownToggler = null;
    this.source = null;
    this._inputGr = null;
    this._bmdHelp = null;
    this.isError = null;
  }

  // ------------------------------------------------------------------------
  // private
  // ------------------------------------------------------------------------
  // protected

  _eventSelect(event) {
    const element = event.target.matches(IN_DROPDROWN_ITEM_SELECTOR)
      ? event.target
      : event.target.closest(IN_DROPDROWN_ITEM_SELECTOR);

    const id = Manipulator.getDataAttribute(element, "id");

    if (id >= 0) {
      if (this._list[id]) {
        this._setSelect(this._list[id]);
      }
    }
  }

  _setResetBtn() {
    const isNullValue = this.value.length === 0;

    if (this._isNullValue != isNullValue) {
      const action = !isNullValue ? "addClass" : "removeClass";
      this._isNullValue = isNullValue;

      Manipulator[action](this._inputGr, BTN_RESET_ON_CLASS);
    }
  }
  _setSelect(item) {
    this.selected = item;
    this._setValueInput(this.config.getSelectedValue(item));
    this.source.setSelect(item);
  }
  _clearValue() {
    this._input.value = "";
    this.value = "";
    this._setResetBtn();
    this.setError();
    Manipulator.removeClass(this._dropdownToggler, FILLED_CLASS);
  }
  _setValue(text) {
    this.setError();
    text = text.trim();
    if (!text) return this._clearValue();
    this.value = text;

    this._setResetBtn();
    this.setError();
  }
  _setValueInput(text) {
    text = text.trim();
    if (!text) return this._clearValue();
    this._input.value = text;
    this._setValue(text);
  }
  _render() {
    Manipulator.innerHTML(
      this._dropdownConteiner,
      renderTemplate(this._list, this.config.greatTitle, this._getDivider())
    );
  }

  _getDivider() {
    if (!this.config.divider || !(this.config.dividerCount < this._list.length))
      return "";
    return this.config.dividerContent.replace("%QUERY", encodeURI(this.value));
  }
  _dropdownOn() {    
    if (!this.dropdown) return;
    this.dropdown.show();
  }
  _dropdownOff() {
    if (!this.dropdown) return;
    this.dropdown.hide();
  }
  _setDropdown() {
    this._dropdownToggler = this._getChildElement(IN_DROPDROWN_TOGGLE);
    //bootstrap.
    this.dropdown = new Dropdown(this._dropdownToggler);    
    if (!this.dropdown) this._setError("Failed to find Dropdown");
  }
  _getChildElement(selector, required = true) {
    const _element = SelectorEngine.findOne(selector, this._element);

    if (!_element && required) {
      this._setError(`Failed to find field ${selector}`);
    }
    return _element;
  }
  _activate() {
    this._input = this._getChildElement(IN_INPUT_SELECTOR);
    this._dropdownConteiner = this._getChildElement(
      IN_DROPDROWN_CONTEINER_SELECTOR
    );
    this._inputGr = this._getChildElement(IN_INPUT_RESET_GR, false);
    this._bmdHelp = this._getChildElement(IN_BMB_HELP_SELECTOR, false);

    this._setDropdown();
    this._list = [];
    this.selected = null;

    const value =
      this._input.value !== undefined ? this._input.value.trim() : "";
    this._setValue(value);
    this.isError = "";
  }
  _addListners(action = "on") {
    EventHandler[action](
      this._dropdownConteiner,
      EVENT_CLICK,
      IN_DROPDROWN_ITEM_SELECTOR,
      this._eventSelect.bind(this)
    );
  }
  _getSource() {
    const sourceName = Manipulator.getDataAttribute(this._element, "source");
    if (!sourceName) {
      this._setError("Не передан обязательный атрибут - source");
    }

    this.source = new AppSource(this, sourceName);
  }

  // ------------------------------------------------------------------------
  // Static

  static elementHangler(event) {
    const data = AppTypeahead.elementInterface(this);
    if (!data) return;

    if (event.type === EVENT_INPUT) {
      data.eventInput(event.target.value);
    }
  }

  static nativeInputHangler(event) {
    EventHandler.trigger(event.target, EVENT_INPUT, {
      relatedTarget: event.target,
    });
  }

  static init() {
    EventHandler.on(
      document,
      EVENT_INPUT,
      ELEMENT_SELECTOR,
      AppTypeahead.elementHangler
    );

    EventHandler.on(
      document,
      "input",
      INPUT_SELECTOR,
      throttle(AppTypeahead.nativeInputHangler, Default.throttleLimit)
    );

    EventHandler.on(document, EVENT_CLICK, ELEMENT_SELECTOR, function (event) {
      if (Manipulator.matchesNode(event.target, BTN_CLEAR_SELECTOR)) {
        const data = AppTypeahead.elementInterface(this);
        if (data) {
          data.clearValue();
        }
      }
    });
  }

  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppTypeahead;
