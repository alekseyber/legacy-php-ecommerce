import Util from "./util";


import Manipulator from "@Dom/manipulator";
import BaseComponent from "./base-component";
import { isElement } from "@AppUtil/util";



const ClassName = {
  BMD_FORM_GROUP: "bmd-form-group",
  IS_FILLED: "is-filled",
  IS_FOCUSED: "is-focused",
};

const Selector = {
  BMD_FORM_GROUP: `.${ClassName.BMD_FORM_GROUP}`,
};

// const Default = {};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class Base extends BaseComponent {


  // ------------------------------------------------------------------------
  // protected

  addFormGroupFocus() {
    if (!Manipulator.getAttribute(this._element, "disabled")) {      
      Manipulator.addClass(this.$bmdFormGroup, ClassName.IS_FOCUSED);
    }
  }

  removeFormGroupFocus() {
    Manipulator.removeClass(this.$bmdFormGroup, ClassName.IS_FOCUSED);
  }

  removeIsFilled() {
    Manipulator.removeClass(this.$bmdFormGroup, ClassName.IS_FILLED);
  }

  addIsFilled() {
    Manipulator.addClass(this.$bmdFormGroup, ClassName.IS_FILLED);
  }

  // Find bmd-form-group
  findMdbFormGroup(raiseError = true) {
   
    let mfg = this._element.closest(Selector.BMD_FORM_GROUP);

    if (!mfg && raiseError) {
      throw new Error(
        `Failed to find ${Selector.BMD_FORM_GROUP} for ${Util.describe(
          this._element
        )}`
      );
 
    }
    return mfg;
  }
  addWrap(tagName, className, container) {
    if (!tagName || !className || !container || !isElement(container)) {
      return;
    }

    const wrapper = document.createElement(tagName);
    wrapper.classList.add(className);
    container.parentNode.insertBefore(wrapper, container);
    wrapper.appendChild(container);

    return container;
  }

  // ------------------------------------------------------------------------
  // private

  // ------------------------------------------------------------------------
  // static

}



export default Base;
