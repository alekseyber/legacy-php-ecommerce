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

const getSelector = (element, prefix = "bs-") => {
  let selector = element.getAttribute(`data-${prefix}target`);

  if (!selector || selector === "#") {
    let hrefAttr = element.getAttribute("href");

    if (!hrefAttr || (!hrefAttr.includes("#") && !hrefAttr.startsWith("."))) {
      return null;
    }

    // Just in case some CMS puts out a full URL with the anchor appended
    if (hrefAttr.includes("#") && !hrefAttr.startsWith("#")) {
      hrefAttr = "#" + hrefAttr.split("#")[1];
    }

    selector = hrefAttr && hrefAttr !== "#" ? hrefAttr.trim() : null;
  }

  return selector;
};

const getElementFromSelector = (element, prefix = "bs-") => {
  const selector = getSelector(element, prefix);

  return selector ? document.querySelector(selector) : null;
};

const reflow = (element) => element.offsetHeight;

const isElement = (obj) => {
  if (!obj) return false;
  return (obj[0] || obj).nodeType;
};

const describe = ($element) => {
  if ($element === undefined) {
    return "undefined";
  } else if (!isElement($element)) {
    return "(no matching elements)";
  }
  return `${$element.outerHTML.split(">")}>`;
  //return `${$element[0].outerHTML.split(">")[0]}>`;
};

const isTouchDevice = () => {
  return (
    !!(
      typeof window !== "undefined" &&
      ("ontouchstart" in window ||
        (window.DocumentTouch &&
          typeof document !== "undefined" &&
          document instanceof window.DocumentTouch))
    ) ||
    !!(
      typeof navigator !== "undefined" &&
      (navigator.maxTouchPoints || navigator.msMaxTouchPoints)
    )
  );
};

const isIosDevice = () => {
  return navigator.userAgent.match(/(iPad|iPhone|iPod)/i);
};

const ltrim = (str) => str.replace(/^\/*/, "");

const addGET = (url, name, value, amp = false) => {
 
  if (url.indexOf("?") < 0) {
    url = url + "?" + name + "=" + value;
  } else {
    if (amp) {
      amp = "&amp;";
    } else {
      amp = "&";
    }
    url = url + amp + name + "=" + value;
  }  
  return url;
};

export {
  throttle,
  ltrim,
  isTouchDevice,
  isIosDevice,
  describe,
  isElement,
  getSelector,
  getElementFromSelector,
  reflow,
  addGET,
};
