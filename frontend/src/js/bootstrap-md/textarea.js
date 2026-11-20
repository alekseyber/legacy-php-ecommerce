import BaseFormControl from "./baseFormControl";
import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";
import Util from "./util";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "textarea";
const DATA_KEY = `bmd.${NAME}`;

const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class Textarea extends BaseFormControl {
  dispose() {
    super.dispose(DATA_KEY);
  }

  static matches($element) {
    const elType = Manipulator.getAttribute($element, "type");
    if (elType === "text") return true;
    return false;
  }

  static rejectMatch(component, $element) {
    Util.assert(
      this._element,
      this.matches($element),
      `${component} component element ${Util.describe(
        $element
      )} is invalid for <textarea>.`
    );
  }

  // ------------------------------------------------------------------------
  // protected

  // ------------------------------------------------------------------------
  // private

  // ------------------------------------------------------------------------
  // static

  static elementInterface($element, config) {
    const data =
      Data.getData($element, DATA_KEY) || new Textarea($element, config);

    return data;
  }

  static jQueryInterface(config) {
    return this.each(function () {
      Textarea.elementInterface(this, config);
    });
  }

  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default Textarea;
