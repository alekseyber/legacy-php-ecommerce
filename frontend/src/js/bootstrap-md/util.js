const Util = (() => {
  /**
   * ------------------------------------------------------------------------
   * Private TransitionEnd Helpers
   * ------------------------------------------------------------------------
   */

  let transitionEnd = false;
  let transitionEndSelector = "";

  const TransitionEndEvent = {
    WebkitTransition: "webkitTransitionEnd",
    MozTransition: "transitionend",
    OTransition: "oTransitionEnd otransitionend",
    transition: "transitionend",
  };

  function transitionEndTest() {
    if (window.QUnit) {
      return false;
    }

    let el = document.createElement("bmd");

    for (let name in TransitionEndEvent) {
      if (el.style[name] !== undefined) {
        return TransitionEndEvent[name]; // { end: TransitionEndEvent[name] }
      }
    }

    return false;
  }

  function setTransitionEndSupport() {
    transitionEnd = transitionEndTest();

    // generate a concatenated transition end event selector
    for (let name in TransitionEndEvent) {
      transitionEndSelector += ` ${TransitionEndEvent[name]}`;
    }
  }

  /**
   * --------------------------------------------------------------------------
   * Public Util Api
   * --------------------------------------------------------------------------
   */

  let Util = {
    transitionEndSupported() {
      return transitionEnd;
    },

    transitionEndSelector() {
      return transitionEndSelector;
    },

    isChar(event) {
      if (typeof event.which === "undefined") {
        return true;
      } else if (typeof event.which === "number" && event.which > 0) {
        return (
          !event.ctrlKey &&
          !event.metaKey &&
          !event.altKey &&
          event.which !== 8 && // backspace
          event.which !== 9 && // tab
          event.which !== 13 && // enter
          event.which !== 16 && // shift
          event.which !== 17 && // ctrl
          event.which !== 20 && // caps lock
          event.which !== 27 // escape
        );
      }
      return false;
    },

    assert($element, invalidTest, message) {
      if (invalidTest) {
        if (!$element === undefined) {
          // $element.css("border", "1px solid red");
          $element.style.border = "1px solid red";
        }
        console.error(message, $element); // eslint-disable-line no-console
        throw message;
      }
    },
    isElement(obj) {
      return (obj[0] || obj).nodeType;
    },

    describe($element) {
      if ($element === undefined) {
        return "undefined";
      } else if (!this.isElement($element)) {
        return "(no matching elements)";
      }
      return `${$element.outerHTML.split(">")}>`;
    },

    isTouchDevice() {
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
    },

    isIosDevice() {
      return navigator.userAgent.match(/(iPad|iPhone|iPod)/i);
    },
  };

  setTransitionEndSupport();
  return Util;
})();

export default Util;
