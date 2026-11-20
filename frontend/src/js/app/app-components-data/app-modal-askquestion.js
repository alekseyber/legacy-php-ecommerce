//bootstrap component
import Modal from "bootstrap/js/src/modal";

import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import AppBaseData from "./app-base-data";
import {
  APP_NAME_SPACE,
  APP_APPASKQUESTION_KEY,
  EVENT_FORM_CLEAR_ERR,
  FORM_NAME_ASKQUESTION,
} from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
//const Modal = window.bootstrap.Modal;

const NAME = "appaskquestion";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

const EVENT_CLICK = `click${EVENT_KEY}`;
const EVENT_MOADL_HIDDEN = "hidden.bs.modal";

const ELEMENT_TOGGLE_SELECTOR = "[data-toggle='askquestion']";
const NAME_TEMPLATE_MODAL = "askquestion";
const MODAL_SELECTOR = "#askquestion";
const MODAL_TITLE_SELECTOR = '[data-selector="modaltitle"]';

//const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppAskquestion extends AppBaseData {
  // Public

  show(event) {
    const element = event.target;
    if (element instanceof HTMLAnchorElement) {
      event.preventDefault();
    }
    const title = Manipulator.getDataAttributeNoBs(element, "title");

    if (this._title && title) {
      if (this._title.innerText !== title) {
        this._title.innerText = title;
      }
    }
    this._awaitLoadingFunc.call(this, this.showBs.bind(this));
  }

  hideBs() {
    if (this._bsModal && isOPen) {
      this._bsModal.hide();
    }
  }
  showBs() {
    if (this._bsModal) {
      this._bsModal.show();
    }
  }

  claerError() {
    EventHandler.trigger(this._modal, EVENT_FORM_CLEAR_ERR, {
      detail: {
        name: FORM_NAME_ASKQUESTION,
      },
    });
  }

  dispose() {
    super.dispose();
    this._title = null;
    this._bsModal.dispose();
    this._bsModal = null;
    this._modal = null;
  }

  // ------------------------------------------------------------------------
  // private

  // ------------------------------------------------------------------------
  // protected

  async _init() {
    this._setLoadingOn();
    this._appLoading(true);

    this._addListners();

    this._modal = await this._getModal(
      MODAL_SELECTOR,
      this._getUrlTemplate(NAME_TEMPLATE_MODAL)
    );
    this._title = this._getChildElement(MODAL_TITLE_SELECTOR, this._modal);

    this._bsModal = new Modal(this._modal);
    this._triggerModeSatatus(this._modal);

    this._setLoadingOff();
    this._appLoading(false);
  }
  _addListners(action = "on") {
    EventHandler[action](
      document,
      EVENT_MOADL_HIDDEN,
      MODAL_SELECTOR,
      this.claerError.bind(this)
    );
  }

  // ------------------------------------------------------------------------
  // Static
  static clickHangler(config, event) {
    const data = AppAskquestion.interface(event.target, config);

    if (data) {
      data.show(event);
    }
  }
  static init(config) {
    EventHandler.on(
      document,
      EVENT_CLICK,
      ELEMENT_TOGGLE_SELECTOR,
      AppAskquestion.clickHangler.bind(null, config)
    );
  }

  static get DATA_KEY() {
    return APP_APPASKQUESTION_KEY;
  }
}

export default AppAskquestion;
