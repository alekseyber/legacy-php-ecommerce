import BaseInput from "./baseInput";
import Data from "@Dom/data";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const Default = {
  requiredClasses: ["form-control"],
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class BaseFormControl extends BaseInput {
  constructor($element, config) {
   
    super($element, Data.extendConfig(Default, config));

    // Initially mark as empty
    if (this.isEmpty()) {
      this.removeIsFilled();
    }
  }
}

export default BaseFormControl;
