import BaseSelection from "./baseSelection";
import Util from "./util";
import Data from "@Dom/data";
import { isElement } from "@AppUtil/util";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "checkbox";
const DATA_KEY = `bmd.${NAME}`;
const CSS_SELECTOR = '.checkbox > label > input[type="checkbox"]';

const Default = {
  template: `<span class='checkbox-decorator'><span class='check'></span></span>`,
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class Checkbox extends BaseSelection {
  constructor(
    $element,
    config,
    properties = { inputType: NAME, outerClass: NAME, selector: CSS_SELECTOR }
  ) {
    super($element, Data.extendConfig(Default, config), properties);
  }

  static matches($element) {
    // '.checkbox > label > input[type=checkbox]'
    if ($element.getAttribute("type") === "checkbox") {
      return true;
    }
    return false;
  }

  static rejectMatch(component, $element) {
    Util.assert(
      this._element,
      this.matches($element),
      `${component} component element ${Util.describe(
        $element
      )} is invalid for type='checkbox'.`
    );
  }

  // ------------------------------------------------------------------------
  // static

  static elementInterface($element, config) {
    if (!isElement($element)) return;

    const data =
      Data.getData($element, DATA_KEY) || new Checkbox($element, config);

    const colorValue = $element.dataset.color;

    if (colorValue) {
      const parent = $element.closest("label");

      if (parent) {
        const checkEl = parent.querySelector(".check");

        if (checkEl) {
          checkEl.style.backgroundColor = colorValue;
          checkEl.style.borderColor = colorValue;
          checkEl.style.color = colorValue;
        }
      }
    }
    return data;
  }

  static jQueryInterface(config) {
    return this.each(function () {
      Checkbox.elementInterface(this, config);
    });
  }

  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }

  static get SELECTOR() {
    return CSS_SELECTOR;
  }
}

export default Checkbox;
