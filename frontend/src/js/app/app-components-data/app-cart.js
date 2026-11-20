//bootstrap
import Modal from "bootstrap/js/src/modal";

import AppBaseData from "./app-base-data";
import Manipulator from "@Dom/manipulator";
import EventHandler from "@Dom/event-handler";
import {
  EVENT_SCROLL_TOP,
  EVENT_RUN_CART_UPDATE,
  EVENT_RUN_CART_DELINF_UPDATE,
  APP_CART_DATA_KEY,
  APP_NAME_SPACE,
  // MODE_STATUS,
  EVENT_HIDE_QPRODUCT,
  // EVENT_AJAX_CONTENT_LOADED,
  EVENT_HIDE_QORDER,
  EVENT_FORM_CLEAR_ERR,
} from "@Constans/index";

const NAME = "appCart";
const DATA_KEY = `${APP_NAME_SPACE}.${NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;
// const DATA_API_KEY = ".data-api";

const EVENT_CLICK = `click${EVENT_KEY}`;
const EVENT_CHANGE = `change${EVENT_KEY}`;
const EVENT_SUBMIT = `submit${EVENT_KEY}`;

const SELECTOR_CART_SMAL_CONTEINER = '[data-selector="small-cart-body"]';
const SELECTOR_CART_SMAL_BTN = '[data-selector="btn-top-cart"]';
const SELECTOR_CART_SMAL_IN = '[data-selector="app-in-load-cart-mini"]';

const NAME_CART_SMAL_MODAL = "modalcart";
const SELECTOR_CART_SMAL_MODAL = "#modalcart";
const SELECTOR_CART_CONTEINER = '[data-selector="cart-wrap"]';
const SELECTOR_CART_ORDER_FORM_WRAP = '[data-selector="order-form"]';
const SELECTOR_CART_SMAL_DEL_BTN = '[data-selector="cart-small-del"]';
const SELECTOR_CART_QTY_SELECTOR = '[data-selector="cart-item-qty"]';
const SELECTOR_CART_IN = '[data-selector="app-in-load-cart"]';

const SELECTOR_CART_DELIVERY_INF = '[data-selector="deliv-inf-bl"]';

const NAME_CART_ADDED = "addedcart";
const SELECTOR_CART_ADDED = "#addedcart";
const SELECTOR_CART_ADDED_CONTEINER = '[data-selector="added-conteiner"]';

const SELECTOR_MODAL_CLOCE_TOOGLER = '[data-selector="modal-close"]';
const SELECTOR_MODAL = ".modal";

const SELECTOR_QORDER_FORM = "[data-qorder]";

const SELECTOR_CART_Q_ORDER = "#qorder";
const NAME_CART_Q_ORDER = "qorder";
const NAME_FORM_Q_ORDER = "qorder";

const EVENT_HIDE_MODAL = "hidden.bs.modal";

const MODALS_FOR_ADD = [
  {
    name: NAME_CART_SMAL_MODAL,
    selector: SELECTOR_CART_SMAL_MODAL,
    // trigger: null,
    required: false,
    smCartSelector: SELECTOR_CART_SMAL_CONTEINER,
  },
  {
    name: NAME_CART_ADDED,
    selector: SELECTOR_CART_ADDED,
    // trigger: null,
    required: false,
    addedselector: SELECTOR_CART_ADDED_CONTEINER,
  },
  {
    name: NAME_CART_Q_ORDER,
    selector: SELECTOR_CART_Q_ORDER,
    // trigger: MODE_STATUS.Q_ORDER,
    required: false,
  },
];

const SELECTOR_ADD_CART_FORM = "form.js-add-card-form";

const SELECTOR_Q_ORDER_BTN = '[data-selector="qorder-btn"]';

const QTY_KEY_NAME = "qty";
const URL_CART_MINI = "/cart/cartmini";
const URL_CART = "/cart/cart";

const URL_ADD_CART = "/cart/add";

const URL_CART_UPDATE_COUNT = "/cart/changeitemqty";
const URL_CART_DELETE_ITEM = "/cart/delete";

//const Default = {};

class AppCart extends AppBaseData {
  // Public
  addCart(event, form) {
    let qOrder = true;
    if (event) {
      event.preventDefault();
      form = event.target;
      qOrder = false;
    }

    //const qOrder = Manipulator.getDataAttributeNoBs(event.submitter, "qorder");
    const data = new FormData(form);   

    let edit = data.has("cardData[edit]");
    let addAction = !qOrder && !edit;
    this._requestAddCart(data, edit, addAction, qOrder);
  }

  cartUpdate() {
    this._cartMiniUpdate();
    this._cartUpdate();
  }
  cartItemUpdateCount(event) {
    const selectElement = event.target;
    const id = Manipulator.getDataAttributeNoBs(selectElement, "id");
    if (!id)
      return console.error("cartItemUpdateCount ID not found for AppCart");

    let qty = selectElement.value;
    qty = this._chekIntValue(qty);
    const url = `${URL_CART_UPDATE_COUNT}?id=${id}&qty=${qty}`;
    this._requestData(url);
  }

  cartSmallDeleteItem(event) {
    event.preventDefault();
    let element = event.target;

    if (!element.matches(SELECTOR_CART_SMAL_DEL_BTN)) {
      element = element.closest(SELECTOR_CART_SMAL_DEL_BTN);
    }
    const id = Manipulator.getDataAttributeNoBs(element, "id");
    if (!id)
      return console.error("cartSmallDeleteItem ID not found for AppCart");
    const url = `${URL_CART_DELETE_ITEM}?id=${id}`;
    this._requestData(url);
  }

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

    this._btnCart = null;
    for (const item of MODALS_FOR_ADD) {
      if (this._bsModals[item.selector]) {
        this._bsModals[item.selector].dispose();
      }
    }
    this._bsModals = null;
    this._addedConteiner = null;
    this._smCartConteiner = null;
  }

  // Private

  async _requestAddCart(data, edit, addAction, qOrder) {
    try {
      const rezult = await this.fetchApp.post(URL_ADD_CART, data);

      if (rezult.succes) {
        this.cartUpdate();
        if (addAction) {
          this._addedShow(rezult);
        }
        if (edit) {
          this._hideQProduct();
        }
        if (qOrder) {
          this.showBs(SELECTOR_CART_Q_ORDER);
        }

        return true;
      }
      if (rezult.message) {
        this._alertDanger(rezult.message);
      }
    } catch (e) {
      this._alertDanger("Ошибка связи, попробуйте позже.");
      this._logError(e);
    }
  }

  async _requestData(url) {
    try {
      const rezult = await this.fetchApp.get(url);
      if (rezult.succes) {
        this.cartUpdate();
      } else if (rezult.message) {
        this._alertDanger(rezult.message);
      }
    } catch (e) {
      this._logError(e);
    }
  }
  _cartUpdate() {
    const cartConteiner = this._getChildElement(
      SELECTOR_CART_CONTEINER,
      document.body,
      false
    );
    if (!cartConteiner) return;
    this._requestCart(cartConteiner);
  }
  async _cartMiniUpdate() {
    if (!this._smCartConteiner) {
      console.error("CartMini conteiner not found for AppCartMini");
      return;
    }
    await this._requestCartMini();
  }

  async _requestCart(conteiner) {
    try {
      const rezult = await this.fetchApp.get(URL_CART);

      const { content } = rezult;
      if (content) {
        conteiner.innerHTML = content;

        const inCartEl = this._getChildElement(SELECTOR_CART_IN, conteiner);
        const isEmpty = Manipulator.getDataAttributeNoBs(inCartEl, "empty");

        if (isEmpty) {
          const orderFormEl = this._getChildElement(
            SELECTOR_CART_ORDER_FORM_WRAP,
            document.body,
            false
          );

          if (orderFormEl) {
            Manipulator.detach(orderFormEl);
            EventHandler.trigger(document.body, EVENT_SCROLL_TOP);
          }
        }
      }
    } catch (e) {
      this._logError(e);
    }
  }

  async _requestCartMini() {
    try {
      const rezult = await this.fetchApp.get(URL_CART_MINI);
      const { content } = rezult;
      if (content !== undefined) {
        this._smCartConteiner.innerHTML = content;

        const cartBodyIn = this._getChildElement(
          SELECTOR_CART_SMAL_IN,
          this._smCartConteiner,
          true
        );

        let qty = Manipulator.getDataAttributeNoBs(cartBodyIn, QTY_KEY_NAME);
        qty = this._chekIntValue(qty);
        Manipulator.setDataAttributeNoBs(this._btnCart, QTY_KEY_NAME, qty);
      }
    } catch (e) {
      this._logError(e);
    }
  }

  async _deliveryCartUpdate() {
    const deliveryConteiner = this._getChildElement(
      SELECTOR_CART_DELIVERY_INF,
      document.body,
      false
    );
    if (!deliveryConteiner) return;

    const { content } = await this.fetchApp.get(`${URL_CART}?delifinf`);
    if (content !== undefined) {
      deliveryConteiner.innerHTML = content;
    }
  }

  _hideQProduct(element = document.body) {
    EventHandler.trigger(element, EVENT_HIDE_QPRODUCT);
  }

  _qorderSubmitHangler(event) {
    if (event.target.checkValidity()) {
      this._hideQProduct(event.target);
    }
  }

  _qorderRunCloseHangler() {
    this.hideBs(SELECTOR_CART_Q_ORDER);
  }
  _qorderQlearErroHangler(event) {
    EventHandler.trigger(event.target, EVENT_FORM_CLEAR_ERR, {
      detail: {
        name: NAME_FORM_Q_ORDER,
      },
    });
  }

  _qorderBtnHangler(event) {
    event.preventDefault();
    event.stopPropagation();
    const form = this._getParrentElement("form", event.target);
    this.addCart(null, form);
  }

  _addedShow(rezult) {
    if (this._addedConteiner) {
      this._addedConteiner.innerHTML = rezult.content;
      this.showBs(SELECTOR_CART_ADDED);
    } else {
      this._alertSuccess(rezult.message);
    }
  }
  _cartSmalBtnHangler() {
    this.toggleBs(SELECTOR_CART_SMAL_MODAL);
  }

  _modalCloseHangler(event) {
    const modal = this._getParrentElement(SELECTOR_MODAL, event.target, false);
    const id = Manipulator.getAttribute(modal, "id");
    const name = "#" + id;
    this.hideBs(name);
    this._hideQProduct(event.target);
  }

  async _getModalsBs() {
    this._bsModals = {};
    this._addedConteiner = null;

    try {
      for (const item of MODALS_FOR_ADD) {
        this._bsModals[item.selector] = null;

        const itemModal = await this._getModal(
          item.selector,
          this._getUrlTemplate(item.name),
          item.required
        );

        if (itemModal) {
          this._bsModals[item.selector] = new Modal(itemModal);

          if (item.addedselector) {
            this._addedConteiner = this._getChildElement(
              item.addedselector,
              itemModal,
              item.required
            );
          }
          if (item.smCartSelector) {
            this._smCartConteiner = this._getChildElement(
              item.smCartSelector,
              itemModal,
              item.required
            );
          }
        }
      }
    } catch (e) {
      this._logError(e);
    }
  }

  async _init() {
    this._setLoadingOn();
    this._addListners();
    this._btnCart = this._getChildElement(
      SELECTOR_CART_SMAL_BTN,
      document.body
    );
    await this._getModalsBs();
    await this._cartMiniUpdate();
    this._setLoadingOff();
  }
  _addListners(action = "on") {
    EventHandler[action](
      document,
      EVENT_RUN_CART_UPDATE,
      null,
      this.cartUpdate.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_RUN_CART_DELINF_UPDATE,
      null,
      this._deliveryCartUpdate.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_CLICK,
      SELECTOR_CART_SMAL_DEL_BTN,
      this.cartSmallDeleteItem.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_CLICK,
      SELECTOR_MODAL_CLOCE_TOOGLER,
      this._modalCloseHangler.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_CHANGE,
      SELECTOR_CART_QTY_SELECTOR,
      this.cartItemUpdateCount.bind(this)
    );

    EventHandler[action](
      document,
      EVENT_SUBMIT,
      SELECTOR_ADD_CART_FORM,
      this.addCart.bind(this)
    );

    EventHandler[action](
      document,
      EVENT_CLICK,
      SELECTOR_Q_ORDER_BTN,
      this._qorderBtnHangler.bind(this)
    );

    EventHandler[action](
      document,
      EVENT_SUBMIT,
      SELECTOR_QORDER_FORM,
      this._qorderSubmitHangler.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_HIDE_QORDER,
      null,
      this._qorderRunCloseHangler.bind(this)
    );

    EventHandler[action](
      document,
      EVENT_HIDE_MODAL,
      SELECTOR_CART_Q_ORDER,
      this._qorderQlearErroHangler.bind(this)
    );
    EventHandler[action](
      document,
      EVENT_CLICK,
      SELECTOR_CART_SMAL_BTN,
      this._awaitLoadingFunc.bind(
        this,
        this._cartSmalBtnHangler.bind(this, [...arguments])
      )
    );
  }

  static get DATA_KEY() {
    return APP_CART_DATA_KEY;
  }
}

export default AppCart;
