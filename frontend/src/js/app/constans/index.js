export const APP_NAME_SPACE = `wb`;
const APP_NAME = "wbstore";
const DATA_KEY = `${APP_NAME_SPACE}.${APP_NAME}`;
const EVENT_KEY = `.${DATA_KEY}`;

export const NAME_KEY = `${APP_NAME_SPACE}Key`;

//window.location.origin;
export const BASE_URL = "";

export const APP_DATA_KEY = `app${EVENT_KEY}`;
export const APP_CART_DATA_KEY = `appcart${EVENT_KEY}`;
export const APP_FILTER_DATA_KEY = `filterleftmenu${EVENT_KEY}`;
export const APP_QPRODUCT_DATA_KEY = `appqproduct${EVENT_KEY}`;
export const APP_QPRODUCT_OPEN_KEY = `appqproductmodal${EVENT_KEY}`;
export const APP_MODAL_ACTION_KEY = `appmodalaction${EVENT_KEY}`;
export const APP_RECAPTCHA_KEY = `apprecaptcha${EVENT_KEY}`;
export const APP_APPASKQUESTION_KEY = `appaskquestion${EVENT_KEY}`;
export const APP_PAGE_KEY = `apppage${EVENT_KEY}`;

export const MODE_STATUS = {
  ALL: "all",
  PAGE: "page",
  FILTER: "filter",
  START: "start",
  DILVERY_INF: "divelery-inf",
  PRODUCT_MAIN: "productmain",
  Q_ORDER: "qorder",
  JS_FORM: "jsform",
  JS_VK_FORM: "jsvkform",
};



export const MODE_STATUS_ELEMENT_DATA_KEY = "mode-status";

export const URL_TEMPLATE = "/ajax?template=";

export const ALERT_SUCCESS = "alertsuccess";
export const ALERT_DANGER = "alertdanger";

export const SELECTOR_PAGE_CONTEINER_GLOBAL = '[data-selector="page-content"]';
export const SELECTOR_IN_CONTENT_GLOBAL = '[data-selector="app-in-load-el"]';
export const SELECTOR_FILTER_GLOBAL = '[data-selector="app-left-filter"]';

export const SELECTOR_APP_MODAL_CONTENT = '[data-selector="modal-content"]';


export const SELECTOR_CONTEINER_DELIV = '[data-selector="app-delivery"]';
export const SELECTOR_IN_DELIV_GLOBAL = '[data-selector="app-in-deliv-inf"]';

export const SELECTOR_IN_PRODUCT_GLOBAL = '[data-selector="app-in-product-m"]';

export const EVENT_AJAX_CONTENT_LOADED = `ajaxcontentloaded${EVENT_KEY}`;
export const EVENT_AJAX_DATA_LOADED = `ajaxdataloaded${EVENT_KEY}`;
export const EVENT_SLIDER_CHANGE = `fpriceslchange${EVENT_KEY}`;
export const EVENT_APP_ALERT = `alert${EVENT_KEY}`;
export const EVENT_SCROLL_TOP = `scrolltotop${EVENT_KEY}`;
export const EVENT_CHANGE_CITY = `changecity${EVENT_KEY}`;
export const EVENT_RUN_CART_UPDATE = `runcartupdate${EVENT_KEY}`;
export const EVENT_RUN_CART_DELINF_UPDATE = `runcartdelifupdate${EVENT_KEY}`;
export const EVENT_APP_LOADER = `apploader${EVENT_KEY}`;
export const EVENT_APP_FILTER_ACTIVE_SET = `appfilteractiveset${EVENT_KEY}`;
export const EVENT_QLEAR_QPRODUCT = `qlearqproduct${EVENT_KEY}`;
export const EVENT_HIDE_QPRODUCT = `hideqproduct${EVENT_KEY}`;
export const EVENT_MAIN_TOGGLE = `maintoggle${EVENT_KEY}`;
export const EVENT_JS_L_TOGGLE = `jslinktoggle${EVENT_KEY}`;
export const EVENT_APP_MENU_ACTIVE_SET = `appmenuactiveset${EVENT_KEY}`;
export const EVENT_HIDE_QORDER = `hideqorder${EVENT_KEY}`;


export const EVENT_FORM_CLEAR_ERR = `appformclearerror${EVENT_KEY}`;
export const FORM_NAME_ASKQUESTION = "askquestion";
export const FORM_NAME_ADDCOMMENT = "addcomment";
export const FORM_NAME_VOZVRAT = "vozvrat";



