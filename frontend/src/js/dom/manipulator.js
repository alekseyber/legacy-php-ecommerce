const NAME_SPACE = "";
const NAME_SPACE_DATA = NAME_SPACE ? `${NAME_SPACE}-` : "";

const isElement = (obj) => (obj[0] || obj).nodeType;

function isNoArgumets(...theArgs) {
  let rezult = false;
  if (!isElement(theArgs[0])) {
    return true;
  }

  for (let variable of theArgs) {
    rezult = !variable;
    if (rezult) break;
  }

  return rezult;
}

function _getDataAttributes(element, nameSpaceVal = "bs") {
  if (!element || !isElement(element)) {
    return {};
  }

  const attributes = {};

  let dataKeys = Object.keys(element.dataset);
  if (nameSpaceVal) {
    dataKeys = dataKeys.filter((key) => key.startsWith(nameSpaceVal));
  }

  dataKeys.forEach((key) => {
    let pureKey = key;
    if (nameSpaceVal) {
      pureKey = key.replace(new RegExp(`/^${nameSpaceVal}/`), "");
    }

    pureKey =
      pureKey.charAt(0).toLowerCase() + pureKey.slice(1, pureKey.length);
    attributes[pureKey] = normalizeData(element.dataset[key]);
  });

  return attributes;
}

function normalizeData(val) {
  if (val === "true") {
    return true;
  }

  if (val === "false") {
    return false;
  }

  if (val === Number(val).toString()) {
    return Number(val);
  }

  if (val === "" || val === "null") {
    return null;
  }

  return val;
}

function normalizeDataKey(key) {
  return key.replace(/[A-Z]/g, (chr) => `-${chr.toLowerCase()}`);
}

const Manipulator = {
  setDataAttribute(element, key, value) {
    if (isNoArgumets(arguments)) return false;
    element.setAttribute(`data-bs-${normalizeDataKey(key)}`, value);
  },

  setDataAttributeNoBs(element, key, value) {
    if (isNoArgumets(arguments)) return false;
    element.setAttribute(
      `data-${NAME_SPACE_DATA}${normalizeDataKey(key)}`,
      value
    );
  },

  removeDataAttribute(element, key) {
    if (isNoArgumets(arguments)) return false;
    element.removeAttribute(`data-bs-${normalizeDataKey(key)}`);
  },

  removeDataAttributeNoBs(element, key) {
    if (isNoArgumets(arguments)) return false;
    element.removeAttribute(`data-${NAME_SPACE_DATA}${normalizeDataKey(key)}`);
  },

  setAttributeNoBs(element, key, value) {
    if (isNoArgumets(arguments)) return false;
    element.setAttribute(key, value);
  },

  getDataAttributes(element) {
    return _getDataAttributes(element);
  },

  getDataAttributesNoBs(element) {
    return _getDataAttributes(element, NAME_SPACE);
  },

  getDataAttribute(element, key) {
    if (isNoArgumets(arguments)) return false;
    return normalizeData(
      element.getAttribute(`data-bs-${normalizeDataKey(key)}`)
    );
  },

  getDataAttributeNoBs(element, key) {
    if (isNoArgumets(arguments)) return false;
    return normalizeData(
      element.getAttribute(`data-${NAME_SPACE_DATA}${normalizeDataKey(key)}`)
    );
  },

  getAttribute(element, key) {
    if (isNoArgumets(arguments)) return false;
    return element.getAttribute(key);
  },

  offset(element) {
    if (isNoArgumets(arguments))
      return {
        top: 0,
        left: 0,
      };
    const rect = element.getBoundingClientRect();

    return {
      top: rect.top + document.body.scrollTop,
      left: rect.left + document.body.scrollLeft,
    };
  },

  position(element) {
    return {
      top: element.offsetTop,
      left: element.offsetLeft,
    };
  },

  removeClass(element, className) {
    if (isNoArgumets(arguments)) return false;
    element.classList.remove(className);
  },
  addClass(element, className) {
    if (isNoArgumets(arguments)) return false;
    element.classList.add(className);
  },
  innerText(element, text) {
    if (isNoArgumets(arguments)) return false;
    element.innerText = text;
  },
  innerHTML(element, html) {
    if (isNoArgumets(arguments)) return false;
    element.innerHTML = html;
  },
  hasClass(element, className) {
    if (isNoArgumets(arguments)) return false;
    return element.classList.contains(className);
  },
  matchesNode(element, selector) {
    if (isNoArgumets(arguments)) return false;
    if (element.matches(selector)) return true;
    const parentElement = element.parentElement;
    if (!parentElement) return false;
    return parentElement.matches(selector);
  },
  css(element, css = {}) {
    if (isNoArgumets(arguments) || typeof css !== "object") return false;
    Object.keys(css).forEach((itemKey) => {
      element.style[itemKey] = css[itemKey];
    });
  },

  fadeOut(element, complete, displayOff) {
    if (!isElement(element)) return;
    element.style.opacity = 1;
    const fade = () => {
      if ((element.style.opacity -= 0.2) < 0) {
        if (!displayOff) {
          element.style.display = "none";
        }
        if (typeof complete === "function") {
          complete();
        }
      } else {
        window.requestAnimationFrame(fade);
      }
    };
    fade();
  },
  fadeIn(element, complete, display = "block") {
    if (!isElement(element)) return;
    element.style.opacity = 0;
    if (display) {
      element.style.display = display;
    }
    const fade = () => {
      let val = parseFloat(element.style.opacity);
      if (!((val += 0.2) > 1)) {
        element.style.opacity = val;
        window.requestAnimationFrame(fade);
      } else if (typeof complete === "function") {
        complete();
      }
    };
    fade();
  },
  detach(element) {
    if (!isElement(element)) return;
    const parentElement = element.parentElement;
    if (!isElement(parentElement)) return;
    return parentElement.removeChild(element);
  },
};

export default Manipulator;
