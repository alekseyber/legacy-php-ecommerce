import Data from "@Dom/data";
import DataApp from "@AppUtil/data-app";

import AppBase from "@App/app-base";

import { SELECTOR_APP_MODAL_CONTENT, URL_TEMPLATE } from "@Constans/index";

const Default = {};

class AppBaseData extends AppBase {
  constructor(config) {
    super(Data.extendConfig(Default, config));
    try {
      this._init();
      DataApp.setData(this.constructor.DATA_KEY, this);
    } catch (e) {
      this._logError(e);
    }
  }

  // Public

  dispose() {
    super.dispose();
    this._addListners("off");
    DataApp.removeData(this.constructor.DATA_KEY);
  }

  // Protected

  _getUrlTemplate(name) {
    return URL_TEMPLATE + name;
  }
  async _getModal(selector, url, required = true) {
    try {
      let modal;
      const appModalContainer = this._getChildElement(
        SELECTOR_APP_MODAL_CONTENT,
        document.body
      );
      modal = this._getChildElement(selector, appModalContainer, false);
      if (!modal) {
        const { content } = await this.fetchApp.get(url);
        if (content) {
          appModalContainer.insertAdjacentHTML("beforeEnd", content);
          modal = this._getChildElement(selector, appModalContainer, required);
          this._triggerModeSatatus(modal);
        }
        return modal;
      }
    } catch (e) {
      this._setError(e.message);
    }
  }

  _formSetHiddenInput(form, key, value, valid = true) {
    if (valid) {
      if (form.checkValidity() === false) return;
    }

    let input;
    const selector = `[name="${key}"]`;
    input = this._getChildElement(selector, form, false);
    if (input) {
      input.value = value;
      return;
    }
    input = document.createElement("input");
    input.setAttribute("name", key);
    input.setAttribute("value", value);
    input.hidden = true;
    form.insertAdjacentElement("afterbegin", input);
  }

  _chekIntValue(value) {
    value = parseInt(value, 10);
    if (isNaN(value)) {
      value = 0;
    }
    return value;
  }

  _init() {}
  _addListners(action = "on") {}

  static interface(config) {
    const data = DataApp.getData(this.DATA_KEY) || new this(config);

    if (typeof config === "string" && config) {
      if (data[config]) {
        return data[config];
      }
    }

    return data;
  }
}

export default AppBaseData;
