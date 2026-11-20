//bootstrap component
import Modal from "bootstrap/js/src/modal";

import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import AppBaseData from "./app-base-data";
import {
  APP_NAME_SPACE,
  APP_MODAL_ACTION_KEY,
  SELECTOR_APP_MODAL_CONTENT,
} from "@Constans";
import { ltrim } from "@AppUtil/util";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

//const Modal = window.bootstrap.Modal;

const NAME = "appQuickviewProduct";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

const EVENT_CLICK = `click${EVENT_KEY}`;

const ELEMENT_TOGGLE_SELECTOR = ".js-link-modal";
const NAME_DATA_KEY_DATA = "dann";
const NAME_DATA_KEY_TARGET = "href";
const NAME_DATA_FIELDS = ["id", "name", "modal", "block"];
const URL_DATA_KEY = "content_url";
const REZULT_SELECTOR_DATA_KEY = "rezult_id";
const ELEMENT_AJ_TOGGLE_SELECTOR = ".js-link-ajblock";

const URL_DEFAULT = "/ajax";

const LOADING_TEMPLATE = `
<div class="d-flex align-items-center">
  <strong>Загрузка...</strong>
  <div class="spinner-border ms-auto"></div>
</div>`;

//const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppModalAction extends AppBaseData {
  // Public

  hideBs(name) {
    if (this._bsModals[name]) {
      this._bsModals[name].hide();
    }
  }
  showBs(name) {
    if (this._bsModals[name]) {
      this._bsModals[name].show();
    }
  }
  toggleBs(name) {
    if (this._bsModals[name]) {
      this._bsModals[name].toggle();
    }
  }

  dispose() {
    super.dispose();
    this._modalConteiner = null;
    Object.keys(this._bsModals).forEach((key) => {
      this._bsModal[key].dispose();
    });
    this._bsModal = null;
  }

  // ------------------------------------------------------------------------
  // private

  _getData(element, block = false) {
    let dann = Manipulator.getDataAttributeNoBs(element, NAME_DATA_KEY_DATA);
    const blockData = {
      succes: false,
    };

    if (dann) {
      dann = JSON.parse(dann);
    }

    let url = URL_DEFAULT;

    if (dann[URL_DATA_KEY]) {
      url = "/" + ltrim(dann[URL_DATA_KEY]);
    }

    const data = new FormData();
    NAME_DATA_FIELDS.forEach((name) => {
      if (dann[name] !== undefined) {
        data.append(name, dann[name]);
      }
    });

    if (block) {
      if (dann[REZULT_SELECTOR_DATA_KEY] && dann.name) {
        blockData.succes = true;
        blockData.inSelector = "#" + dann[REZULT_SELECTOR_DATA_KEY];
        blockData.selector = `#${dann.name}`;
      }
    }

    return { url, data, blockData };
  }

  _show(element) {
    let target;
    if (element instanceof HTMLAnchorElement) {
      target = Manipulator.getAttribute(element, "href");
    } else {
      target = Manipulator.getDataAttributeNoBs(element, NAME_DATA_KEY_TARGET);
    }
    if (!target) {
      this._setError("Таргет не передан.");
    }

    if (this._bsModals[target]) return this.showBs(target);

    const { data, url } = this._getData(element);

    this._request(url, data, target);
  }

  _getError(rezult) {
    const message = rezult.message
      ? rezult.message
      : "Ошибка связи, попробуйте позже.";

    throw new Error(message);
  }

  _getErrorCatch(e) {
    this._alertDanger("Ошибка связи, попробуйте позже.");
    this._logError(e);
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

  async _request(url, data, target) {
    try {
      this._appLoading(true);

      const rezult = await this.fetchApp.post(url, data);
      if (rezult.succes && rezult.content) {
        this._modalConteiner.insertAdjacentHTML("beforeEnd", rezult.content);
        const modal = this._getChildElement(target, this._modalConteiner, true);
        this._triggerModeSatatus(modal, null, rezult.data);
        this._bsModals[target] = new Modal(modal);

        if (rezult.js) {
          this._jsFunc(rezult.js);
        }

        return this.showBs(target);
      }
      this._getError(rezult);
    } catch (e) {
      this._getErrorCatch(e);
    } finally {
      this._appLoading(false);
    }
  }

  async _requestAjBlock(url, data, conteiner, blockData) {
    try {
      this._appLoading(true);
      conteiner.innerHTML = LOADING_TEMPLATE;
      const response = await this.fetchApp.post(url, data);

      if (response.succes && response.content) {
        conteiner.innerHTML = response.content;
        const targetEl = this._getChildElement(blockData.inSelector);
        if (response.js) {
          this._jsFunc(response.js);
        }
        return this._triggerModeSatatus(targetEl);
      }
      this._getError(response);
    } catch (e) {
      this._getErrorCatch(e);
    } finally {
      this._appLoading(false);
    }
  }

  // ------------------------------------------------------------------------
  // protected
  _showHangler(event) {
    let element = event.target;
    if (!element.matches(ELEMENT_TOGGLE_SELECTOR)) {
      element = element.closest(ELEMENT_TOGGLE_SELECTOR);
    }
    if (element instanceof HTMLAnchorElement) {
      event.preventDefault();
    }
    this._show(element);
  }
  _ajBlockHangler(event) {
    const element = event.target;
    const { data, url, blockData } = this._getData(element, true);
    if (!blockData.succes) {
      this._setError("Обязательные параметры не переданы.");
    }
    const conteiner = this._getChildElement(blockData.selector);
    const rezult = this._getChildElement(
      blockData.inSelector,
      document.body,
      false
    );
    if (rezult) return;
    this._requestAjBlock(url, data, conteiner, blockData);
  }

  async _init() {
    this._modalConteiner = this._getChildElement(SELECTOR_APP_MODAL_CONTENT);
    this._bsModals = {};
    this._addListners();
  }
  _addListners(action = "on") {
    EventHandler[action](
      document,
      EVENT_CLICK,
      ELEMENT_TOGGLE_SELECTOR,
      this._showHangler.bind(this)
    );

    EventHandler[action](
      document,
      EVENT_CLICK,
      ELEMENT_AJ_TOGGLE_SELECTOR,
      this._ajBlockHangler.bind(this)
    );
  }

  // ------------------------------------------------------------------------
  // Static

  static get DATA_KEY() {
    return APP_MODAL_ACTION_KEY;
  }
}

export default AppModalAction;
