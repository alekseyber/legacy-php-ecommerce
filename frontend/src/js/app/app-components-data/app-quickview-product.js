//bootstrap component
import Modal from "bootstrap/js/src/modal";

import DataApp from "@AppUtil/data-app";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import AppBaseData from "./app-base-data";
import {
  APP_NAME_SPACE,
  APP_QPRODUCT_DATA_KEY,
  APP_QPRODUCT_OPEN_KEY,
  EVENT_QLEAR_QPRODUCT,
  EVENT_HIDE_QPRODUCT,
  SELECTOR_IN_PRODUCT_GLOBAL,
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
const EVENT_MOADL_HIDDEN = "hidden.bs.modal";

const TOGGLE_SELECTOR = ".js-quickview";

const MODAL_SELECTOR = "#quickviewproduct";

const MODAL_NO_QP_SELECTOR = `.modal:not(${MODAL_SELECTOR})`;

const CONTEINER_SELECTOR = '[data-selector="main-product"]';
const CONTENT_IN_SELECTOR = SELECTOR_IN_PRODUCT_GLOBAL;

const Q_PR_CLOSE_TOGGLER_SELECTOR = '[data-selector="qpr-close"]';

const MODAL_OPEN_CLASS = "modal-open";

const NAME_DATA_KEY_TARGET = "href";

const NAME_TEMPLATE_MODAL = "quickview";

//const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppQuickviewProduct extends AppBaseData {
  // Public
  show(event) {
    const element = event.target;
    let url;
    if (element instanceof HTMLAnchorElement) {
      // event.preventDefault();
      url = element.href;
    } else {
      url = Manipulator.getDataAttributeNoBs(element, NAME_DATA_KEY_TARGET);
    }

    if (!url) {
      this._setError(
        `AppQuickviewProduct - отсутствуе обязательный атрибут ${NAME_DATA_KEY_TARGET}`
      );
    }
    url = "/" + ltrim(url);

    this._request(url);
  }
  claerDataAppModal() {
    DataApp.setData(APP_QPRODUCT_OPEN_KEY, null);
  }
  hideBs() {
    const isOPen = DataApp.getData(APP_QPRODUCT_OPEN_KEY);
    if (this._bsModal && isOPen) {
      this._bsModal.hide();
    }
  }
  showBs() {
    if (this._bsModal) {
      this._bsModal.show();
    }
  }
  otherHideHangler() {
    const isOPen = DataApp.getData(APP_QPRODUCT_OPEN_KEY);

    if (isOPen) {
      document.body.classList.add(MODAL_OPEN_CLASS);
    }
  }

  dispose() {
    super.dispose();
    this._data = null;
    this._modal = null;
    this._conteiner = null;
    this._bsModal.dispose();
    this._bsModal = null;
  }

  // ------------------------------------------------------------------------
  // private

  async _showAwait(event) {
    if (event.target instanceof HTMLAnchorElement) {
      event.preventDefault();
    }

    this._awaitLoadingFunc.call(this, this.show.bind(this, event));
  }

  async _request(url) {
    try {
      this._appLoading(true);
      const rezult = await this.fetchApp.post(url, this._data);

      const { content } = rezult;

      if (content) {
        this._conteiner.innerHTML = content;

        const targetEl = this._getChildElement(
          CONTENT_IN_SELECTOR,
          this._conteiner
        );
        this._triggerModeSatatus(targetEl);

        this._bsModal.show();
        DataApp.setData(APP_QPRODUCT_OPEN_KEY, this._bsModal);
      }
    } catch (e) {
      this._logError(e);
    } finally {
      this._appLoading(false);
    }
  }

  // ------------------------------------------------------------------------
  // protected

  async _init() {
    this._setLoadingOn();
    this._addListners();
    this._modal = await this._getModal(
      MODAL_SELECTOR,
      this._getUrlTemplate(NAME_TEMPLATE_MODAL)
    );

    this._conteiner = this._getChildElement(CONTEINER_SELECTOR, this._modal);
    this._data = new FormData();
    this._data.append("productcard", "product_modal");
    this._bsModal = new Modal(this._modal);
    this._setLoadingOff();
  }
  _addListners(action = "on") {
    EventHandler[action](
      document,
      EVENT_CLICK,
      TOGGLE_SELECTOR,
      this._showAwait.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_MOADL_HIDDEN,
      MODAL_SELECTOR,
      this.claerDataAppModal.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_MOADL_HIDDEN,
      MODAL_NO_QP_SELECTOR,
      this.otherHideHangler.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_QLEAR_QPRODUCT,
      null,
      this.claerDataAppModal.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_HIDE_QPRODUCT,
      null,
      this.hideBs.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_CLICK,
      Q_PR_CLOSE_TOGGLER_SELECTOR,
      this.hideBs.bind(this)
    );
  }

  // ------------------------------------------------------------------------
  // Static

  static get DATA_KEY() {
    return APP_QPRODUCT_DATA_KEY;
  }
}

export default AppQuickviewProduct;
