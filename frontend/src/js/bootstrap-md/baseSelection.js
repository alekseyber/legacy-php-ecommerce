import BaseInput from "./baseInput";
import Util from "./util";
import Manipulator from "@Dom/manipulator";
import Ripples from "./ripples";
import Data from "@Dom/data";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const Default = {
  label: {
    required: false,

    // Prioritized find order for resolving the label to be used as an bmd-label if not specified in the markup
    //  - a function(thisComponent); or
    //  - a string selector used like $bmdFormGroup.find(selector)
    //
    // Note this only runs if $bmdFormGroup.find(Selector.BMD_LABEL_WILDCARD) fails to find a label (as authored in the markup)
    //
    //selectors: [
    //  `.form-control-label`, // in the case of horizontal or inline forms, this will be marked
    //  `> label` // usual case for text inputs
    //]
  },
};

const Selector = {
  LABEL: "label",
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class BaseSelection extends BaseInput {
  constructor($element, config, properties) {
    super($element, Data.extendConfig(Default, config), properties);

    this.decorateMarkup();
  }

  // ------------------------------------------------------------------------
  // protected

  decorateMarkup() {
    this._element.insertAdjacentHTML("afterend", this.config.template);

    // initialize ripples after decorator has been inserted into DOM

    if (this.config.ripples) {
      //   $decorator.bmdRipples(); //не обработано
      Ripples.ripplesInterface(this._element); //decorator ?
    }
  }

  // Demarcation element (e.g. first child of a form-group)
  outerElement() {
    // .checkbox|switch|radio > label > input[type=checkbox|radio]
    // label.checkbox-inline > input[type=checkbox|radio]

    return this._element.parentElement.closest(`.${this.outerClass}`);
  }

  rejectWithoutRequiredStructure() {
    // '.checkbox|switch|radio > label > input[type=checkbox|radio]'

    const parentEl = this._element.parentElement.tagName;
    const elementName = parentEl.tagName;
    const outerEl = this.outerElement();

    Util.assert(
      this._element,
      !elementName === "label",
      `${this.constructor.name}'s ${Util.describe(
        this._element
      )} parent element should be <label>.`
    );
    Util.assert(
      this._element,
      !Manipulator.hasClass(outerEl, this.outerClass),
      `${this.constructor.name}'s ${Util.describe(
        this._element
      )} outer element should have class ${this.outerClass}.`
    );
  }

  addFocusListener() {
    // checkboxes didn't appear to bubble to the document, so we'll bind these directly

    const labelEl = this._element.closest(Selector.LABEL);
    if (labelEl) {
      labelEl.addEventListener("mouseenter", this.addFormGroupFocus.bind(this));
      labelEl.addEventListener(
        "mouseleave",
        this.removeFormGroupFocus.bind(this)
      );
    }
  }

  addChangeListener() {
    const addChangeListenerHandler = () => {
      this._element.blur();
    };
    this._element.addEventListener(
      "change",
      addChangeListenerHandler.bind(this)
    );
  }
}

export default BaseSelection;
