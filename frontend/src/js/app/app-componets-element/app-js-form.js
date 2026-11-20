import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";
import {
  APP_NAME_SPACE,
  EVENT_FORM_CLEAR_ERR,
  EVENT_JS_L_TOGGLE,
  EVENT_HIDE_QORDER,
  EVENT_RUN_CART_UPDATE,
} from "@Constans/index";
import AppRecaptcha from "@App/app-components-data/app-recaptcha";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const NAME = "appJsForm";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

//const EVENT_CLICK = `click${EVENT_KEY}`;
const EVENT_SUBMIT = `submit${EVENT_KEY}`;

const DANGER_SELECTOR = '[data-selector="alert-danger"]';
const SUCCESS_SELECTOR = '[data-selector="alert-success"]';
const LOADER_SELECTOR = '[data-selector="alert-light"]';
const FORM_SELECTOR = "form";

const ALERTS_TEMPLATE = `
<div class="askquestion-message-wrap">
<div class="alert alert-danger" data-selector="alert-danger"></div>
<div class="alert alert-success" data-selector="alert-success"></div>
<div class="alert alert-light" data-selector="alert-light">
    <div class="d-flex align-items-center">
        <strong>Данные отправляются...</strong>
        <div class="spinner-border ms-auto"></div>
    </div>
</div>
</div>`;

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {};

class AppJsForm extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      this._activate();
    } catch (e) {
      this._logError(e);
    }
  }
  // Public
  claerError() {
    if (this._isError) {
      this._setLoader(false, false, "", true);
    }
  }

  dispose() {
    super.dispose();
    this._addListners("off");
    this._recaptcha = null;
    this._danger = null;
    this._success = null;
    this._loader = null;
    this._form = null;
    this._url = null;
    this._isError = null;
    this._nameForm = null;
  }
  // private

  async _request() {
    try {
      this._setLoader(true);
      if (this._recaptcha) {
        await this._recaptcha.addForm(this._form);
      }
      const data = new FormData(this._form);
      const request = await this.fetchApp.post(this._url, data);

      if (request.succes) {
        //  this._form.reset();
        if (request.clearSuccess) {
          this._setLoader(false, true, "", false, true);
        } else {
          this._setLoader(false, true, request.message);
        }

        if (request.qorder) {
          EventHandler.trigger(this._form, EVENT_HIDE_QORDER);
        }
        if (request.order) {          
          EventHandler.trigger(this._form, EVENT_RUN_CART_UPDATE);
        }        

        if (request.location) {
          if (!window.jsLOff) {
            return EventHandler.trigger(this._form, EVENT_JS_L_TOGGLE, {
              detail: {
                url: request.location,
                onOnpostat: true,
              },
            });
          }
          window.location = request.location;
        }

        return true;
      }
      return this._setLoader(false, false, rezult.message);
    } catch (e) {
      this._setLoader(false, false, "Ошибка связи, попробуйте позже.");
      this._logError(e);
    }
  }

  _submitHanglerAwait(event) {
    event.preventDefault();
    this._awaitLoadingFunc.call(this, this._submitHangler.bind(this, event));
  }
  _submitHangler(event) {
    if (!this._form) {
      this._form = event.target;

      if (!this._form instanceof HTMLFormElement) {
        this._form = this._getParrentElement(FORM_SELECTOR, this._form);
      }

      this._url = this._form.action;
    }
    if (this._form.checkValidity() === false) return;
    this._request();
  }

  _clearErrHangler({ detail }) {
    if (this._nameForm === detail.name) {
      this.claerError();
    }
  }

  _setLoader(on, success, text = "", clear, clearSuccess) {
    const formFadeIn = () => Manipulator.fadeIn(this._form);
    this._isError = false;
    if (on) {
      const loaderFadeIn = () => Manipulator.fadeIn(this._loader);
      return Manipulator.fadeOut(this._form, loaderFadeIn);
    }
    if (success && text) {
      const successFadeIn = () => Manipulator.fadeIn(this._success);
      this._success.innerText = text;
      return Manipulator.fadeOut(this._loader, successFadeIn);
    }
    if (!success && text) {
      this._isError = true;
      const dangerFadeIn = () => {
        Manipulator.fadeIn(this._danger, formFadeIn);
      };
      this._danger.innerText = text;
      return Manipulator.fadeOut(this._loader, dangerFadeIn);
    }
    if (clear) {
      return Manipulator.fadeOut(this._danger);
    }
    if (clearSuccess) {
      return Manipulator.fadeOut(this._loader, formFadeIn);
    }
    Manipulator.fadeOut(this._loader, formFadeIn);
  }

  async _activate() {
    try {
      this._setLoadingOn();
      this._appLoading(true);
      this._addListners();
      this._nameForm = Manipulator.getDataAttributeNoBs(this._element, "name");
      const reOn = Manipulator.getDataAttributeNoBs(this._element, "re");
      this._recaptcha = null;
      if (reOn) {
        this._recaptcha = await AppRecaptcha.interface({
          logError: this._logError,
        });
      }
      this._element.insertAdjacentHTML("afterbegin", ALERTS_TEMPLATE);

      this._danger = this._getChildElement(DANGER_SELECTOR, this._element);
      this._success = this._getChildElement(SUCCESS_SELECTOR, this._element);
      this._loader = this._getChildElement(LOADER_SELECTOR, this._element);
      this._form = null;
      this._isError = false;
      this._url = null;
      this._setLoadingOff();
    } catch (e) {
      this._logError(e);
    } finally {
      this._appLoading(false);
    }
  }

  _addListners(action = "on") {
    EventHandler[action](
      this._element,
      EVENT_SUBMIT,
      FORM_SELECTOR,
      this._submitHanglerAwait.bind(this)
    );

    EventHandler[action](
      document,
      EVENT_FORM_CLEAR_ERR,
      null,
      this._clearErrHangler.bind(this)
    );
  }

  // Static

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppJsForm;
