import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import BaseComponent from "./base-app-component";
import {
  APP_NAME_SPACE,
  // EVENT_FORM_CLEAR_ERR,
  // EVENT_JS_L_TOGGLE,
} from "@Constans";
//import AppRecaptcha from "@App/app-components-data/app-recaptcha";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const NAME = "appJsVkAuthForm";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

const EVENT_CLICK = `click${EVENT_KEY}`;
//const EVENT_SUBMIT = `submit${EVENT_KEY}`;

const EVENT_JS_VK_INIT = `vkinit${EVENT_KEY}`;

const SELECTOR_SOCIAL_BTN_WRAP = '[data-selector="social-btn-wrap"]';
const SELECTOR_SOCIAL_BTN = '[data-selector="auth-vk"]';
const SELECTOR_NAME_WRAP = '[data-selector="name-wrap"]';


const VK_TRANSPORT_LIB_SCR_URL = "https://vk.com/js/api/openapi.js?168";

const D_NONE_CLASS = "d-none";

const VK_API_VER = "5.73";
//const LOADER_SELECTOR = '[data-selector="js-form"]';
//MODE_STATUS.JS_FORM

const getTemplate = (src, name) => {
  return `<div class="d-flex align-items-center mb-1">
  <div><img class="img-fluid" src="${src}" alt="${name}"></div>
  <div class="ms-3 h5">${name}</div></div>`;
};


/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {};

class AppJsVkAuthForm extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));
    try {
      this._activate();
    } catch (e) {
      this._logError(e);
    }
  }
  // Public

  dispose() {
    super.dispose();
    this._addListners("off");    
    if (this._initStatus && this._btnWrap) {
      this._addListnersAfterInit("off");
      this._btnWrap = null;
      this._initStatus = null;
    }
    
  }
  // private


  _vkAsyncInit(apiId) {
    const elementTrigger = this._element;
    window.vkAsyncInit = function () {
      VK.init({ apiId });
      EventHandler.trigger(elementTrigger, EVENT_JS_VK_INIT);
    };
    this._loadApiScript();
  }

  _loadApiScript() {
    const divEl = document.createElement("div");
    divEl.id = "vk_api_transport";
    this._element.insertAdjacentElement("afterbegin", divEl);
    setTimeout(() => {
      const el = document.createElement("script");
      // el.type = "text/javascript";
      el.src = VK_TRANSPORT_LIB_SCR_URL;
      el.async = true;
      document.getElementById("vk_api_transport").appendChild(el);
    }, 0);
  }

  _vkInitHangler() {
    this._initStatus = true;
    this._btnWrap = this._getChildElement(
      SELECTOR_SOCIAL_BTN_WRAP,
      this._element
    );
    this._addListnersAfterInit();
    Manipulator.removeClass(this._btnWrap, D_NONE_CLASS);
  }

  _vkAuthComplite(user) {
    
    const nameWrap = this._getChildElement(SELECTOR_NAME_WRAP, this._element);
  
    const nameValue = user.first_name + " " + user.last_name;
    nameWrap.innerHTML = getTemplate(user.avatar, nameValue);
    this._addListnersAfterInit("off");
    Manipulator.detach(this._btnWrap);
    this._btnWrap = null;
    this._initStatus = null;

    const name = document.createElement("input");
    name.type = "hidden";
    name.value = nameValue;
    name.name = "author[name]";
    this._element.insertAdjacentElement("afterbegin", name);

    const avatar = document.createElement("input");
    avatar.type = "hidden";
    avatar.value = user.avatar;
    avatar.name = "author[avatar]";
    this._element.insertAdjacentElement("afterbegin", avatar);

    const uid = document.createElement("input");
    uid.type = "hidden";
    uid.value = user.id;
    uid.name = "author[uid]";
    this._element.insertAdjacentElement("afterbegin", uid);

    const provider = document.createElement("input");
    provider.type = "hidden";
    provider.value = "vk";
    provider.name = "author[provider]";
    this._element.insertAdjacentElement("afterbegin", provider);
  }

  _vkAuthHangler() {
    if (!window.VK) {
      return this._setError(`Failed to find VK Init`);
    }

    const self = this;

    const compliteLogin = (response) => {
      if (response.session) {
        const user = response.session.user;
        const user_ids = user.id;
        const fields = "photo_50";
        const v = VK_API_VER;

        const compliteGetUser = (r) => {
          if (r.response) {
            user.avatar = r.response[0].photo_50.replace("https:", "");
            self._vkAuthComplite.call(self, user);
          }
        };
        VK.Api.call("users.get", { user_ids, fields, v }, compliteGetUser);
      } else {
        console.log("Пользователь нажал кнопку Отмена в окне авторизации");
      }
    };
    VK.Auth.login(compliteLogin);
  }

  _activate() {
    try {
      const apiId = Manipulator.getDataAttributeNoBs(this._element, "apiid");
      if (!apiId) this._setError(`Failed to find apiId`);      
      this._initStatus = false;
      this._addListners();
      if (!window.VK) {
        return this._vkAsyncInit(apiId);
      }
      EventHandler.trigger(this._element, EVENT_JS_VK_INIT);
    } catch (e) {
      this._logError(e);
    } finally {
      this._appLoading(false);
    }
  }

  _addListners(action = "on") {
    EventHandler[action](
      this._element,
      EVENT_JS_VK_INIT,
      null,
      this._vkInitHangler.bind(this)
    );
  }
  _addListnersAfterInit(action = "on") {
    EventHandler[action](
      this._element,
      EVENT_CLICK,
      SELECTOR_SOCIAL_BTN,
      this._vkAuthHangler.bind(this)
    );
  }

  // Static


  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppJsVkAuthForm;
