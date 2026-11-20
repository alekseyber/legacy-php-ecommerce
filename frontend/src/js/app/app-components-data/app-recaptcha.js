// https://www.npmjs.com/package/recaptcha-v3
import { load } from "recaptcha-v3";
import EventHandler from "@Dom/event-handler";
import AppBaseData from "./app-base-data";
import { APP_NAME_SPACE, APP_RECAPTCHA_KEY } from "@Constans";


/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "appRecaptchat";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

const EVENT_SUBMIT = `submit${EVENT_KEY}`;

const SELECTOR_FORM_RECAPTCHA = '[data-g-recaptcha="true"]';
const INPUT_KEY_FORM_RECAPTCHA = "g-recaptcha-response";

//const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class AppRecaptcha extends AppBaseData {
  // Public

  async getTokent(action = "submit") {
    try {
      if (!this.recaptcha) return;
      return await this.recaptcha.execute(action);
    } catch (e) {
      this.logError(e);
    }
  }

  async addForm(form, action = "submit") {
    if (!form) return;
    const token = await this.getTokent(action);
    if (!token) return;
    this._formSetHiddenInput(form, INPUT_KEY_FORM_RECAPTCHA, token);
  }

  dispose() {
    super.dispose();
    this.recaptcha = null;
  }

  // ------------------------------------------------------------------------
  // private

  // ------------------------------------------------------------------------
  // protected

  async _recaptchaFormHangler({ target }) {
    await this.addForm(target);
  }

  async _init() {
    try {
      this.recaptcha = null;

      if (window.reCAPTCHA) {
        this.recaptcha = await load(window.reCAPTCHA, { autoHideBadge: true });
      }
      // this._addListners();
    } catch (e) {
      this.logError(e);
    }
  }
  _addListners(action = "on") {
    if (this.recaptcha) {
      EventHandler[action](
        document,
        EVENT_SUBMIT,
        SELECTOR_FORM_RECAPTCHA,
        this._recaptchaFormHangler.bind(this)
      );
    }
  }

  static get DATA_KEY() {
    return APP_RECAPTCHA_KEY;
  }
}

export default AppRecaptcha;
