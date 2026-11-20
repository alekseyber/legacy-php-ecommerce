import BaseFormControl from "./baseFormControl";
import Util from "./util";
import Data from "@Dom/data";
import Manipulator from "@Dom/manipulator";


/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "text";
const DATA_KEY = `bmd.${NAME}`;


const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class Text extends BaseFormControl {

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
      )} is invalid for type='text'.`
    );
  }

  // ------------------------------------------------------------------------
  // protected

  // ------------------------------------------------------------------------
  // private

  // ------------------------------------------------------------------------
  // static


  static elementInterface($element, config) {
    const data = Data.getData($element, DATA_KEY) || new Text($element, config);

    return data;
  }

  static jQueryInterface(config) {
    return this.each(function () {
      Text.elementInterface(this, config);
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


export default Text;
