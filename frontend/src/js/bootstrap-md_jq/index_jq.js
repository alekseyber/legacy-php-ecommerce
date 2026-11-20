import "@/css/index.css";
import "@/scss/index.scss";
//bootstrap-material-design
import "./bootstrap-md";
import "picturefill";
import "lightgallery";
import Inputmask from "inputmask";
import "ion-rangeslider";
import Bloodhound from "typeahead.js/dist/bloodhound";
import "typeahead.js/dist/typeahead.jquery";
import Url from "url-parse";
import { Object } from "core-js";

let statusModalQuickview = false;
let openModalQuickview = false;

const scroolTop = () => {
  $("html, body").animate(
    {
      scrollTop: 0,
    },
    900
  );
};

const setAllert = (message = "", type = "danger") => {
  const allertEl = $("#js-alert-" + type);

  allertEl.find(".js-allert-message").text(message);
  allertEl.addClass("show");

  setTimeout(() => {
    allertEl.removeClass("show");
  }, 3000);
};

const setAllertDanger = (message = "") => {
  setAllert(message);
};

const setAllertSuccess = (message = "") => {
  setAllert(message, "success");
};

const setLoader = (loader = true) => {
  if (loader) {
    $(".preloader").addClass("onvisible");
  } else {
    $(".preloader").removeClass("onvisible");
  }
};

const setlightGall = (className = "pr-image-gallery") => {
  $("." + className).lightGallery({
    download: false,
    nextHtml: '<span class="material-icons">arrow_forward</span>',
    prevHtml: '<span class="material-icons">arrow_back</span>',
    thumbnail: false,
    fullScreen: false,
  });
};

const menuTabsToggle = (selector, self = null) => {
  if (!selector && !self) {
    return false;
  }

  if (self) {
    const parentId = $(self).parents(".tab-pane").attr("id");
    //  $('#' + parentId).toggleClass('active');
    $("#" + parentId).removeClass("active");
    // $('#' + parentId).toggleClass('show');
  } else {
    $(selector)
      .parents(".tab-content")
      .find(".tab-pane.active")
      .removeClass("active");
  }

  $(selector).tab("show");
};

const getActiveMenuTab = (selector) =>
  $(selector).find(".tab-content").find(".tab-pane.active").attr("id");

const getMenuTabIdByItem = (element) => element.closest(".tab-pane").attr("id");

const getProductMedia = (dataObj) => {
  const src = dataObj.thumb.src;
  const alt = dataObj.thumb.alt;
  const srcset = dataObj.thumb.srcset;
  const badgeOn = dataObj.badgeOn;
  const badgeSale = dataObj.badgeSale;
  const badge = dataObj.badge;

  let bageContent = "";

  if (badgeOn) {
    const className = badgeSale ? " primary" : "";
    bageContent = `<div class="bage${className}">${badge}</div>`;
  }

  if (window.webp) {
    const type = /\.jpg/i.test(src) ? "image/jpeg" : "image/png";
    const re = new RegExp(".(png|jpg)", "i");
    const srcsetWebP = srcset.replace(re, ".webp");

    const mediaWebPContent = `<div><picture>
            <source srcset='${srcsetWebP}' type='image/webp'>
            <source srcset='${srcset}' type='${type}'>
            <img class="card-img-top" src='${src}' alt='${alt}'>
        </picture></div>`;

    return {
      media: mediaWebPContent,
      bageContent,
    };
  }
  return {
    media: `<div><img class="card-img-top" src='${src}' srcset='${srcset}' alt='${alt}'></div>`,
    bageContent,
  };
};

const productListSelectColor = function (e) {
  e.preventDefault();

  const element = $(this);

  if (element.attr("class").indexOf("selected") >= 0) {
    return true;
  }

  element.parent(".colors-body").find(".selected").removeClass("selected");
  element.addClass("selected");
  const dataObj = {
    badge: element.data("badge"),
    badgeSale: element.data("badgesale"),
    thumb: element.data("thumb"),
    badgeOn: element.data("badgeon"),
    price: parseInt(element.data("price")),
    oldpPice: parseInt(element.data("oldprice")),
    href: element.attr("href"),
  };

  const media = getProductMedia(dataObj);
  const elementPr = element.parents(".product");
  const elPrMedia = elementPr.find(".pr-media");
  const elBage = elementPr.find(".pr-bage");
  const elPrMediaDiv = elementPr.find(".pr-media > div");
  const height = elPrMedia.height();
  elPrMedia.css({ minHeight: height });
  elBage.html(media.bageContent);

  elPrMediaDiv.fadeOut(200, () => {
    elPrMedia.html(media.media);
    if (dataObj.price) {
      elementPr.find(".price > .value").text(dataObj.price);
    }
    if (dataObj.oldpPice) {
      if (elementPr.is(".old-price")) {
        elementPr.find(".old-price > .value").text(dataObj.oldpPice);
      }
    }
    elementPr.find(".js-l-edit").attr("href", dataObj.href);
    elementPr.find(".js-b-edit").attr("data-href", dataObj.href);

    elPrMediaDiv.fadeIn(200, () => {
      setTimeout(() => {
        elPrMedia.css({ minHeight: "auto" });
      }, 300);
    });
  });
};

const clickCloseModal = (e) => {
  const id = $(e.target).parents(".modal").attr("id");
  const selector = "#" + id;
  $(selector).modal("hide");
  if (selector === "#myModalquickviewProduct") {
    statusModalQuickview = false;
  }
};

const getHref = (element) => {
  const elBtn = $(element);
  const href = elBtn.data("href");
  const blank = elBtn.data("blank") === true ? "_blank" : "";

  return { href, blank };
};

const setJsLinkOpen = function () {
  const obj = getHref(this);

  if (obj.href) {
    window.open(decodeURI(obj.href), obj.blank);
  }
};

const setJsLinkTrueOpen = function () {
  const obj = getHref(this);

  if (obj.href) {
    if (window.js_link_true) {
      jsLinkUpdateContent(null, decodeURI(obj.href));
    } else {
      window.open(decodeURI(obj.href), obj.blank);
    }
  }
};

const setSizeSelect = function () {
  const elBtn = $(this);
  const pid = elBtn.data("pid");
  const input = elBtn
    .parents(".js-add-card-form")
    .find('[name="cardData[pid]"]');
  const noSize = elBtn
    .parents(".js-add-card-form")
    .find('[name="cardData[nosizeselect]"]');
  const btnactive = elBtn.parent(".sizes-selector").find(".btn.active");
  if (btnactive) {
    btnactive.removeClass("active");
  }
  elBtn.addClass("active");
  input.val(pid);
  noSize.val("0");
};

const setInputMask = (
  selector = "input[type='tel']",
  options = { mask: "9-999-999-99-99" }
) => {
  const el = $(selector);
  const im = new Inputmask(options);
  im.mask(el);
};

const throttle = (callback, limit = 700) => {
  let wait = false;

  return (data) => {
    if (!wait) {
      wait = true;
      setTimeout(() => {
        wait = false;
        callback.call(this, data);
      }, limit);
    }
  };
};

const onPriceSliderChangeF = (data) => {
  const from = data.from;
  const to = data.to;
  //  let url = data.input[0].dataset.uri;
  let url = window.sliderFilterUri;
  url = url.replace("@fr@", from);
  url = url.replace("@to@", to);
  url = "/" + ltrim(url);

  if (window.js_link_true2) {
    jsLinkUpdateContent(null, url);
    return true;
  }
  window.location = url;
};

const ionRangesliderPriceFilterInit = (selector = "#pricefilterslider") => {
  const el = $(selector);
  window.sliderFilterUri = el.data("uri");
  const params = {
    type: "double",
    grid: true,
    min: el.data("pricefr") ?? 0,
    max: el.data("priceto") ?? 100,
    from: el.data("pricefrlimit") ?? 0,
    to: el.data("pricetolimit") ?? 100,
    prefix: el.data("prefix") ?? "",
    postfix: el.data("postfix") ?? "",
    step: el.data("step") ?? 100,
    input_values_separator: ",",
    block: el.data("block") ?? false,
    onChange: throttle(onPriceSliderChangeF, 2000),
  };

  // if (onChangeF) {
  //     params.onChange = throttle(onChangeF, 2000);
  // }

  el.ionRangeSlider(params);
};

window.cityselect = null;

const cityselectInit = (prefix = "") => {
  window.cityselect = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      wildcard: "%QUERY",
      url: prefix + "/delivery/typeahead?query=%QUERY",
    },
  });
  cityselect.initialize();
};

const classNamesTypeahead = {
  input: "tt-input",
  hint: "tt-hint",
  selectable: "typeahead-selectable",
  menu: "dropdown-menu", //tt-menu
  suggestion: "dropdown-item", // tt-suggestion dropdown-item
  empty: "tt-empty",
  open: "show", //tt-open
  cursor: "tt-cursor",
  highlight: "highlight",
  dataset: "tt-dataset",
};

const setDivelTeahead = (modal) => {
  modal = modal ? 1 : "";

  if (!window.cityselect) {
    cityselectInit(baseUrl);
  }

  const cssInputSelector = modal ? "#typeahead-city-modal" : "#typeahead-city";
  const cssDivSelector = modal
    ? "#delivery-modal-content"
    : "#delivery-no-modal-content";
  const modalprefix = modal ? "&modal=1" : "";
  const oldval = $(cssInputSelector).val();

  $(cssDivSelector).find('[data-toggle="tooltip"]').tooltip();

  $(cssInputSelector + "close").on("click", () => {
    $(cssInputSelector).val("");
  });

  $(cssInputSelector).typeahead(
    {
      // hint: false,
      minLength: 3,
      highlight: true,
      classNames: classNamesTypeahead,
    },
    {
      name: "cityselect",
      display: "CityName",
      limit: 20,
      source: cityselect,
      templates: {
        empty:
          '<div class="dropdown-item disabled">Сожалеем, ни чего не найдено</div>',
        suggestion: function (s) {
          return "<div>" + s.CityName + " - " + s.OblName + "</div>";
        },
      },
    }
  );

  $(cssInputSelector).on("typeahead:select", (ev, suggestion) => {
    const inputEl = $(cssInputSelector);
    const url =
      "/delivery?receiverCityId=" +
      suggestion.ID +
      modalprefix +
      "&" +
      "rezult=1";
    const div = $(cssDivSelector);

    $.ajax({
      url: url,
      type: "GET",
      dataType: "json",
      beforeSend: () => {
        $("#dilevery-preloader" + modal).fadeIn();
      },
      success: (res) => {
        if (res.err) {
          inputEl.val(oldval);
        } else {
          div.html(res.content);
          inputEl.val(suggestion.CityName);
          $(".js-city-btn-text").text(suggestion.CityName);
          delifinfUpdate();
          div.find('[data-toggle="tooltip"]').tooltip();

          if (res.js) {
            eval(res.js);
          }
        }

        $("#dilevery-preloader" + modal).fadeOut();
      },
      error: (e) => {
        logError(e);
        inputEl.val(oldval);
        $("#dilevery-preloader" + modal).fadeOut();
      },
    });
  });
};

const searchProductInit = (prefix = "", selector = "#search") => {
  /* Search */

  const products = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      wildcard: "%QUERY",
      url: prefix + "/search/typeahead?query=%QUERY",
    },
  });

  products.initialize();

  $(selector).typeahead(
    {
      minLength: 3,
      highlight: false,
      classNames: classNamesTypeahead,
    },
    {
      name: "products",
      display: "title",
      limit: 20,
      source: products,
      templates: {
        empty:
          '<div class="dropdown-item disabled">Сожалеем, ни чего не найдено</div>',
        footer: (q) => {
          const dLength = $(".tt-dataset").children(".dropdown-item").length;
          let foot = "";
          if (dLength > 1) {
            foot =
              '<div class="dropdown-divider"></div><div class="dropdown-item"><a class="search-form-result-all js-link-true2" href="/search?s=' +
              encodeURIComponent(q.query) +
              '">Показать все</a></div>';
          }
          return foot;
        },
      },
    }
  );
  $(selector).on("typeahead:select", (ev, suggestion) => {
    //  console.log('typeahead:select')
    if (window.js_link_true2) {
      jsLinkUpdateContent(null, suggestion.link);
    } else {
      window.location = suggestion.link;
    }
  });
};

const urlParser = (inputUrl) => {
  if (!inputUrl) {
    return window.location;
  }
  return new Url(inputUrl);
};

const clearActiveMenu = (selector, homeTabId) => {
  const elements = $(selector).find("a.disabled");
  if (elements.length) {
    elements.removeClass("disabled");
    const selector = "#" + homeTabId;
    menuTabsToggle(selector);
    return true;
  }
  return false;
};

const setActiveMenu = (parentSelector, findSelector) => {
  const elements = $(parentSelector).find(findSelector);

  if (elements.length) {
    const actveEl = $(elements[0]);
    actveEl.addClass("disabled");
    const tabId = getMenuTabIdByItem(actveEl);
    menuTabsToggle("#" + tabId);
    return true;
  }
  return false;
};

const menuActiveSet = (chek = true) => {
  if (chek) {
    const rezClear = clearActiveMenu("#left-cat-menu", "v-tab-Cathome");
    if (!rezClear) {
      clearActiveMenu("#left-page-menu", "v-tab-Pagehome");
    }
  }
  const url = urlParser();
  const pathname = url.pathname;
  const findSelector = "a[href='" + pathname + "']";

  const rezSet = setActiveMenu("#left-cat-menu", findSelector);
  if (!rezSet) {
    setActiveMenu("#left-page-menu", findSelector);
  }
};

const mainClassToggler = () => {
  const url = urlParser();
  const el = $("#layout-container");
  const className = "main-p";
  const classTrue = el.hasClass(className);

  if (url.pathname === "/") {
    if (!classTrue) {
      el.addClass(className);
    }
  } else {
    if (classTrue) {
      el.removeClass(className);
    }
  }
};

const ltrim = (str) => str.replace(/^\/*/, "");

const cartminiUpdate = () => {
  $.ajax({
    url: "/cart/cartmini",
    dataType: "html",
    cache: false,
    success: (res) => {
      $(".js-small-card-body").html(res);
      let qty = $(".js-body-card-small-wrap").data("qty");
      qty = parseInt(qty, 10);
      if (isNaN(qty)) {
        qty = 0;
      }

      $(".js-small-card-wrap").attr("data-qty", qty);
      $(".js-bage-cart-qty").text(qty);
    },
    error: (e) => {
      logError(e);
    },
  });
};

const delifinfUpdate = () => {
  if ($("body *").is(".js-deliv-inf-bl")) {
    const div = $(".js-deliv-inf-bl");

    $.ajax({
      url: "/cart/cart",
      dataType: "html",
      data: { delifinf: 1 },
      cache: false,
      type: "POST",
      success: (res) => {
        div.html(res);
      },
    });
  }
};

const cartUpdate = () => {
  if ($("body *").is(".js-cart-wrap")) {
    const div = $(".js-cart-wrap");
    $.ajax({
      url: "/cart/cart",
      dataType: "html",
      cache: false,
      success: (res) => {
        div.html(res);
        if ($(".content-wr *").is(".js-empty-c")) {
          $(".content-wr").find(".js-order-form").detach();
        }
      },
    });
  }
};

const cartItemUpdateCount = (event) => {
  const el = $(event.target);
  const id = el.data("id");
  const qty = el.val();
  $.ajax({
    url: "/cart/changeitemqty",
    data: { id, qty },
    type: "GET",
    success: () => {
      cartUpdate();
      cartminiUpdate();
    },
  });
};

const cartSmallDeleteItem = (event) => {
  event.preventDefault();
  const el = $(event.target);
  const id = el.data("id");
  const tpl = el.data("tpl");
  $.ajax({
    url: "/cart/delete",
    data: { id },
    type: "GET",
    success: () => {
      cartminiUpdate();
      cartUpdate();
    },
  });
};

// const parceFotmDate = formData => {
//     const rezult = {};
//     formData.forEach(element => {
//         rezult[element.name] = element.value;
//     });

//     return rezult;

// }

function qOrderFunction(event) {
  $(event.target).parents("form").data("qorder", 1);
}

const addCartFunction = function (event) {
  event.preventDefault();
  const form_data = $(this).serializeArray();
  const qOrder = $(this).data("qorder");
  let addAction = true;
  let edit = false;
  if (!qOrder) {
    $.each(form_data, (_, item) => {
      if (item.name == "cardData[edit]") {
        addAction = false;
        edit = true;
      }
    });
  } else {
    addAction = false;
  }
  if (form_data) {
    $.ajax({
      url: "/cart/add",
      data: { form_data },
      type: "POST",
      dataType: "html",
      success: (res) => {
        //  recomaccesUpdate();
        cartUpdate();
        cartminiUpdate();

        if (addAction) {
          $("#addcardwindow .add-cart-body").html(res);
          $("#addcardwindow").modal("toggle");
        }
        if (qOrder) {
          if (statusModalQuickview) {
            changeModalquickviewProduct(false);
          }
          $("#qorderwindow").modal("show");
        }

        if (edit && statusModalQuickview) {
          changeModalquickviewProduct(false);
        }
      },
      error: (e) => {
        setAllertDanger("Ошибка связи с сервером, повторите попытку позже.");
        logError(e);
      },
    });
  }
  //  return false;
};

const addPromocod = function (event) {
  event.preventDefault();
  //  var form_data = $(event.target).serializeArray(),
  const form = $(this);
  const form_data = $(this).serializeArray();
  if (form_data) {
    $.ajax({
      url: "/cart/cupon",
      data: { form_data },
      type: "POST",
      dataType: "json",
      success: (res) => {
        cartUpdate();
        cartminiUpdate();
        if (res.success == 1) {
          setAllertSuccess("Промокод принят");
        } else {
          // setAllertDanger(res.error);
        }
      },
      error: (e) => {
        logError(e);
        setAllertDanger("Промокод не найден или уже используется!");
      },
    });
  }
  // return false;
};

const quickviewProduct = function (e) {
  const href = $(this).data("href");
  //  let errorsost = false;
  const tpl = "product_modal";

  $.ajax({
    url: "/" + ltrim(href),
    data: { productcard: tpl },
    type: "POST",
    dataType: "html",
    success: (res) => {
      $("#myModalquickviewProduct .modal-body").html(res);
      initMainProduct();
      $("#myModalquickviewProduct").modal("toggle");
      return true;
    },
    error: (e) => {
      logError(e);
      // errorsost = true;
    },
  });
  //  return errorsost;
};

const tooltipInit = () => {
  $('[data-toggleuser="tooltip"]').tooltip();
};

const getLinkData = (element) => {
  const obj = {};
  const elLink = $(element);
  if (elLink.attr("href")) {
    obj.href = elLink.attr("href");
    obj.preventDefault = true;
  } else if (elLink.data("href")) {
    obj.href = elLink.data("href");
  }
  obj.dann = {};
  if (elLink.data("dann")) {
    obj.dann = elLink.data("dann");
  }
  return obj;
};

const linkModal = function (event) {
  const objLink = getLinkData(this);

  if (objLink.preventDefault) {
    event.preventDefault();
  }

  if (!objLink.href) {
    return false;
  }

  if (!$("#modal-content-wrap > div").is(objLink.href)) {
    let urlajax = "/ajax";
    const dann = objLink.dann;

    if (dann.content_url) {
      urlajax = "/" + ltrim(dann.content_url);
    }

    $.ajax({
      url: urlajax,
      data: { dann },
      type: "POST",
      dataType: "html",
      success: (res) => {
        const content = $("#modal-content-wrap").html();
        $("#modal-content-wrap").html(content + res);
        initInfModal(objLink.href);
        $(objLink.href).modal("toggle");
      },
      error: (e) => {
        logError(e);
      },
    });

    return true;
  }
  $(objLink.href).modal("toggle");
};

const linkAjblock = function (event) {
  const dann = $(event.target).data("dann");
  if (
    $("div").is("#" + dann.name) &&
    !$("#" + dann.name + " >").is("#" + dann.rezult_id)
  ) {
    let urlajax = "/ajax";
    if (dann.content_url) {
      urlajax = ltrim(dann.content_url);
    }
    $.ajax({
      url: urlajax,
      data: { dann },
      type: "POST",
      dataType: "html",
      success: (res) => {
        $("#" + dann.name).html(res);
      },
    });
  }
};

const loadContent = async (
  url,
  preloader = false,
  dann = {},
  cache = true,
  dataType = "html"
) => {
  let response = null;

  const params = {
    url,
    dataType,
    cache,
  };
  if (dann !== null && typeof dann === "object") {
    if (Object.keys(dann).length) {
      params.type = "POST";
      params.data = dann;
    }
  }

  try {
    if (preloader) {
      setLoader();
    }
    response = await $.ajax(params);
  } catch (e) {
    //console.error(e);
    throw e;
    //  setAllertDanger('Ошибка связи с сервером, повторите попытку позже.');
  } finally {
    if (preloader) {
      setLoader(false);
    }
  }

  return response;
};

const scrToFunc = (event) => {
  event.preventDefault();
  const linkhref = $(event.target).attr("href");
  const ofsetvalue = $(linkhref).offset();
  const ofsetvaluetop = ofsetvalue.top - 130;
  if (ofsetvaluetop < 56) {
    return false;
  }

  $("html, body").animate(
    {
      scrollTop: ofsetvaluetop,
    },
    1000
  );
};

const logError = (e) => {
  let message = "";
  if (e.message) {
    message = e.message;
  }
  if (e.fileName) {
    message = message + " | " + e.fileName;
  }
  if (e.lineNumber) {
    message = message + " | " + e.lineNumber;
  }
  if (message) {
    console.error(message);
    return true;
  }
  console.error("Ошибка связи с сервером");
};

const nextpaginAction = async function (event) {
  event.preventDefault();
  const dann = { pagin: true };
  const el = $(this);
  const url = "/" + ltrim(el.attr("href"));
  try {
    setLoader();
    const content = await loadContent(url, false, dann);
    if (content) {
      const div = el.closest(".page-content").find(".next-pagin-bl");
      const paginbl = el.closest(".next-pagin-bl").find(".next-pagination");
      paginbl.detach();
      const htmlold = div.html();
      div.html(htmlold + content).fadeIn();
    }
  } catch (e) {
    logError(e);
  } finally {
    setLoader(false);
  }
};

const paginAction = async function (event) {
  event.preventDefault();
  const dann = { pagin: true };
  const el = $(this);
  const url = "/" + ltrim(el.attr("href"));
  try {
    setLoader();
    const content = await loadContent(url, false, dann);
    if (content) {
      const div = el.closest(".page-content").find(".pagin-cont");
      div.html(content).fadeIn();
      initColorsListBtnChek(div);
      scroolTop();
    }
  } catch (e) {
    logError(e);
  } finally {
    setLoader(false);
  }
};

// const addGET = (url, name, value, amp = true) => {
//     if (url.indexOf('?') !== -1) {
//         url = url + "?" + name + "=" + value;
//     } else {
//         if (amp) {
//             amp = "&amp;";
//         } else {
//             amp = "&";
//         }
//         url = url + amp + name + "=" + value;
//     }
//     return url;
// }

const sortAction = async function (event) {
  event.preventDefault();
  const el = $(this);
  if (el.hasClass("disabled")) {
    return false;
  }
  const url = "/" + ltrim(el.attr("href"));
  try {
    setLoader();
    const content = await loadContent(url);
    if (content) {
      const div = el.closest(".page-content").find(".pagin-cont");
      div.html(content).fadeIn();
      initColorsListBtnChek(div);
      const selectedEl = el.closest(".dropdown-menu").find(".disabled");
      if (selectedEl.length) {
        selectedEl.removeClass("disabled").addClass("js-sort");
      }
      el.removeClass("js-sort").addClass("disabled");
    }
  } catch (e) {
    logError(e);
  } finally {
    setLoader(false);
  }
};

const jsSwColorProductUpdate = async function (event) {
  event.preventDefault();

  const elLink = $(this);
  if (elLink.hasClass("disabled")) {
    return false;
  }
  const colorsLEl = elLink.closest(".colors-pr").find(".js-sw-clr-pr");
  colorsLEl.addClass("disabled");
  const url = elLink.attr("href");
  const dann = elementDannParse(elLink);
  const dannRes = {
    productcard: dann.productcard ? dann.productcard : "mainproduct",
  };
  try {
    setLoader();
    const content = await loadContent(url, false, dannRes);

    if (content) {
      const div = elLink.closest(".js-pr-clr-load");
      div.html(content).fadeIn();
      initMainProduct();
      if (dannRes.productcard === "mainproduct") {
        scroolTop();
      }
    } else {
      colorsLEl.removeClass("disabled");
    }
  } catch (e) {
    logError(e);
    colorsLEl.removeClass("disabled");
    // window.location = url;
  } finally {
    setLoader(false);
  }
};

const updateFilter = (filter) => {
  if (filter) {
    const activeTabId = getActiveMenuTab(".filter-menu");
    $(".filter-menu").html(filter);
    initChekboxAndTab();
    ionRangesliderPriceFilterInit();
    if (activeTabId !== "v-tab-Filterhome") {
      const selector = "#" + activeTabId;
      menuTabsToggle(selector);
    }
  }
};

const updateContent = (response, url) => {
  if (!response) {
    return false;
  }

  const div = response.selector ? $(response.selector) : $("#page-content");
  const content = response.content ? response.content : false;
  if (!content) {
    return false;
  }
  const historySet = response.historyset && url ? true : false;
  const scrollTopStatus = response.scrolltop ? true : false;
  const jsFunc = response.js ? response.js : false;
  const filterContent = response.filter ? response.filter : false;

  //  console.log('updateContent')

  div.html(content).fadeIn();
  initColorsListBtnChek(div);

  // console.log(response.noToggler)
  // if (!response.noToggler) {
  //     mainClassToggler();
  // }

  if (filterContent) {
    updateFilter(filterContent);
  }

  if (historySet) {
    // history.pushState({ note: 'main' }, '', url);
    history.pushState({ note: "main" }, "", url);
    mainClassToggler();
    menuActiveSet();
    initBtnCatMenu();
  }

  if (jsFunc) {
    eval(jsFunc);
  }
  if (scrollTopStatus) {
    scroolTop();
  }
};

const elementDannParse = (element) => {
  const doneKeys = {
    productcard: true,
    sortproduct: true,
    pagin: true,
    dann: true,
  };
  const data = element.data();
  const rezult = {};
  if (data !== null && typeof data === "object") {
    const keys = Object.keys(data);
    if (keys.length) {
      keys.forEach((item) => {
        if (doneKeys[item]) {
          rezult[item] = data[item];
        }
      });
    }
  }
  return rezult;
};

const jsLinkUpdateContent = async function (event, location) {
  let url;
  let dann = {};
  if (event) {
    event.preventDefault();
    const elLink = $(this);
    if (elLink.hasClass("disabled")) {
      return false;
    }
    url = elLink.attr("href");
    dann = elementDannParse(elLink);
  } else {
    url = location;
  }
  try {
    setLoader(false);
    const content = await loadContent(url, true, dann, false, "json");
    if (content) {
      if (statusModalQuickview) {
        changeModalquickviewProduct(false);
      }
      updateContent(content, url);
    } else {
      window.location = url;
    }
  } catch (e) {
    logError(e);
    window.location = url;
  } finally {
    setLoader(false);
  }
};

const setLinkDisabled = function (event) {
  const el = $(this);
  if (
    el.hasClass("disabled") &&
    !el.hasClass("js-link-true") &&
    !el.hasClass("js-link-true2")
  ) {
    event.preventDefault();
  }
};

const initColorsListBtn = () => {
  $(".js-colors-btn").on("click", productListSelectColor);
  $(".js-colors-btn").mouseenter(productListSelectColor);
};
const initColorsListBtnChek = (el) => {
  const row = el.find(".pr-row");
  if (row.length) {
    initColorsListBtn();
  }
};

const initChekboxAndTab = () => {
  $('input[type="checkbox"]').bmdCheckbox();

  $(".js-l-return-tab").on("click", function (e) {
    const target = $(this).data("target");
    if (target) {
      menuTabsToggle("#" + target, this);
      return true;
    }

    const href = $(this).attr("href");
    if (href) {
      e.preventDefault();
      menuTabsToggle(href, this);
      return true;
    }
  });
};

const initMainProduct = () => {
  setlightGall();
  $(".js-l-open").on("click", setJsLinkOpen);
  $(".sizes-selector .btn").on("click", setSizeSelect);
  $(document).on("click", ".js-sw-clr-pr", jsSwColorProductUpdate);
};

const changeModalquickviewProduct = (on = true) => {
  const action = on ? "show" : "hide";
  $("#myModalquickviewProduct").modal(action);
  statusModalQuickview = on;
};

const initInfModal = (selector) => {
  $(selector).on("show.bs.modal", () => {
    if (statusModalQuickview) {
      openModalQuickview = true;
      changeModalquickviewProduct(false);
    }
  });

  $(selector).on("hide.bs.modal", () => {
    if (openModalQuickview) {
      openModalQuickview = false;
      changeModalquickviewProduct(true);
    }
  });
};

const layoutDrawerToggle = () => {
  const containerEl = $("#layout-container");
  const backdropEl = $(".bmd-layout-backdrop");
  const status = containerEl.hasClass("bmd-drawer-in");
  const drawerEl = $("#dw-p1");
  const expanded = !status;
  const hidden = status;

  if (status) {
    containerEl.removeClass("bmd-drawer-in");
    backdropEl.removeClass("in");
  } else {
    containerEl.addClass("bmd-drawer-in");
    backdropEl.addClass("in");
  }
  drawerEl.attr("aria-expanded", expanded);
  drawerEl.attr("aria-hidden", hidden);
};

const initBtnCatMenu = () => {
  const btnEl = $(".page-content").find('.btn-cat-menu[data-toggle="drawer"]');
  if (btnEl.length) {
    $('.btn-cat-menu[data-toggle="drawer"]').on("click", layoutDrawerToggle);
  }
};

const initMainPage = () => {
  setTimeout(() => {
    $(".cover-carrier").addClass("loaded");
  }, 900);
};

let baseUrl = "";

$(document).ready(() => {
  if (window.path) {
    baseUrl = window.path;
  }

  $(".bmd-layout-drawer").bmdDrawer();

  initColorsListBtn();
  initChekboxAndTab();

  $(".js-cl-modal-close").on("click", clickCloseModal);
  $(".js-l-open").on("click", setJsLinkOpen);

  $(".drawer-cl").on("click", function () {
    $(this).parents(".bmd-layout-container").removeClass("bmd-drawer-in");
    $(".bmd-layout-backdrop").removeClass("in");
  });

  setTimeout(() => {
    $(".cover-carrier").addClass("loaded");
  }, 900);

  // $('#myModalquickviewProduct').modal('show');
  // $('#addcardwindow').modal('show');
  //$('#qorderwindow').modal('show');

  $("#myModalquickviewProduct").on("show.bs.modal", () => {
    statusModalQuickview = true;
  });

  $("#myModalquickviewProduct").on("hide.bs.modal", () => {
    statusModalQuickview = false;
  });

  $(".modal:not(#myModalquickviewProduct)").on("hidden.bs.modal", () => {
    if (statusModalQuickview && !$("body").is(".modal-open")) {
      $("body").addClass("modal-open");
    }
  });

  $(document).on("click", ".visiblescr #topScrBtn", () => {
    $("html, body").animate({ scrollTop: 0 }, 500);
  });

  $(window).scroll(() => {
    const currentScrollTop = $(window).scrollTop();

    if (currentScrollTop > 150) {
      $(".main-nav").addClass("fixed-top");
      $(".bmd-layout-header").addClass("fixed-top");
      $(".fixed-bl").removeClass("d-none");
    }
    if (currentScrollTop < 140) {
      $(".main-nav").removeClass("fixed-top");
      $(".bmd-layout-header").removeClass("fixed-top");
      $(".fixed-bl").addClass("d-none");
    }
    if (currentScrollTop > 400) {
      $(".top-scr-btn").addClass("visiblescr");
    }
    if (currentScrollTop < 390) {
      $(".top-scr-btn").removeClass("visiblescr");
    }
  });

  $("a.disabled").on("click", setLinkDisabled);

  $(document).on("submit", ".js-add-card-form", addCartFunction);
  $(document).on("click", ".js-q-order", qOrderFunction);
  $(document).on("click", ".cart-small-deleteitem", cartSmallDeleteItem);
  $(document).on("change", ".cart-item-qty", cartItemUpdateCount);
  $(document).on("submit", "form.cupon-form", addPromocod);
  $(document).on("click", ".js-link-modal", linkModal);
  $(document).on("click", ".js-link-ajblock", linkAjblock);
  $(document).on("click", ".js-quickview", quickviewProduct);
  $(document).on("click", "a.js-scrollto", scrToFunc);
  $(document).on("click", ".next-pagination-btn", nextpaginAction);
  $(document).on("click", ".js-sort", sortAction);
  $(document).on("click", ".js-checkbox-true", setJsLinkTrueOpen);
  $(document).on("click", ".js-button-true", setJsLinkTrueOpen);

  $(document).on("click", "a.js-close-m", function () {
    $(this).parents(".modal").modal("hide");
  });

  if (window.js_link_true) {
    $(document).on("click", ".js-link-pagin", paginAction);
    $(document).on("click", ".js-link-true", jsLinkUpdateContent);
  }

  if (window.js_link_true2) {
    $(document).on("click", ".js-link-true2", jsLinkUpdateContent);
  }

  tooltipInit();
  setInputMask();
  ionRangesliderPriceFilterInit();
  searchProductInit(baseUrl);
  cartminiUpdate();
  menuActiveSet(false);

  $('[data-toggle="tooltip"]').tooltip();
});

const initFormsValidation = (iDRoot) => {
  const forms =
    iDRoot && typeof iDRoot === "string"
      ? document
          .getElementById(iDRoot)
          .getElementsByClassName("needs-validation")
      : document.getElementsByClassName("needs-validation");

  if (forms) {
    const validation = Array.prototype.filter.call(forms, (form) => {
      form.addEventListener(
        "submit",
        (event) => {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add("was-validated");
        },
        false
      );
    });
  }
};

(() => {
  "use strict";
  window.addEventListener("load", initFormsValidation, false);
})();

window.onpopstate = (event) => {
  if (event.state) {
    // console.log("location: " + document.location + ", state: " + JSON.stringify(event.state));
    const obj = event.state;
    if (obj.note === "main" && js_link_true) {
      jsLinkUpdateContent(null, document.location);
    } else {
      location.reload();
    }
    if (obj.note !== "main") {
      location.reload();
    }
  }
};

window.setAllertDanger = setAllertDanger;
window.setAllertSuccess = setAllertSuccess;
window.setLoader = setLoader;
window.setlightGall = setlightGall;
window.initInfModal = initInfModal;
window.setInputMask = setInputMask;
window.ionRangesliderPriceFilterInit = ionRangesliderPriceFilterInit;
window.setDivelTeahead = setDivelTeahead;
window.cartminiUpdate = cartminiUpdate;
window.cartUpdate = cartUpdate;
window.delifinfUpdate = delifinfUpdate;
window.cartItemUpdateCount = cartItemUpdateCount;
window.cartSmallDeleteItem = cartSmallDeleteItem;
window.tooltipInit = tooltipInit;
window.initMainProduct = initMainProduct;
window.initMainPage = initMainPage;
window.initFormsValidation = initFormsValidation;
//window.menuTabsToggle = menuTabsToggle;
//window.productListSelectColor = productListSelectColor;
//window.initColorsListBtn = initColorsListBtn;
//window.addGET = addGET;
//window.initChekboxAndTab = initChekboxAndTab;
//window.clickCloseModal = clickCloseModal;
//window.setJsLinkOpen = setJsLinkOpen;
//window.setJsLinkTrueOpen = setJsLinkTrueOpen;
//window.setSizeSelect = setSizeSelect;
//window.throttle = throttle;
//window.cityselectInit = cityselectInit;
//window.searchProductInit = searchProductInit;
//window.urlParser = urlParser;
//window.mainClassToggler = mainClassToggler;
//window.scroolTop = scroolTop;
//window.ltrim = ltrim;
//window.addCartFunction = addCartFunction;
//window.addPromocod = addPromocod;
//window.quickviewProduct = quickviewProduct;
//window.jsSwColorProductUpdate = jsSwColorProductUpdate;
//window.linkModal = linkModal;
//window.getLinkData = getLinkData;
//window.linkAjblock = linkAjblock;
//window.loadContent = loadContent;
//window.updateContent = updateContent;
//window.scrToFunc = scrToFunc;
//window.nextpaginAction = nextpaginAction;
//window.paginAction = paginAction;
//window.sortAction = sortAction;
//window.jsLinkUpdateContent = jsLinkUpdateContent;
//window.getActiveMenuTab = getActiveMenuTab;
//window.qOrderFunction = qOrderFunction;
//window.elementDannParse = elementDannParse;
//window.menuActiveSet = menuActiveSet;
//window.getMenuTabIdByItem = getMenuTabIdByItem;
