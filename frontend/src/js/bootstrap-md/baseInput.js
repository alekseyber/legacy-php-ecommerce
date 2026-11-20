import Base from "./base";
import Util from "./util";
import Data from "@Dom/data";
import EventHandler from "../dom/event-handler";
import SelectorEngine from "@Dom/selector-engine";
import Manipulator from "@Dom/manipulator";

const ClassName = {
  FORM_GROUP: "form-group",
  BMD_FORM_GROUP: "bmd-form-group",
  BMD_LABEL: "bmd-label",
  BMD_LABEL_STATIC: "bmd-label-static",
  BMD_LABEL_PLACEHOLDER: "bmd-label-placeholder",
  BMD_LABEL_FLOATING: "bmd-label-floating",
  HAS_DANGER: "has-danger",
  IS_FILLED: "is-filled",
  IS_FOCUSED: "is-focused",
  INPUT_GROUP: "input-group",
};

const Selector = {
  FORM_GROUP: `.${ClassName.FORM_GROUP}`,
  BMD_FORM_GROUP: `.${ClassName.BMD_FORM_GROUP}`,
  BMD_LABEL_WILDCARD: `label[class^='${ClassName.BMD_LABEL}'], label[class*=' ${ClassName.BMD_LABEL}']`, // match any label variant if specified
};

const Default = {
  validate: false,
  formGroup: {
    required: false,
  },
  bmdFormGroup: {
    template: `<span class='${ClassName.BMD_FORM_GROUP}'></span>`,
    tagName: "div",
    create: true, // create a wrapper if form-group not found
    required: true, // not recommended to turn this off, only used for inline components
  },
  label: {
    required: false,

    // Prioritized find order for resolving the label to be used as an bmd-label if not specified in the markup
    //  - a function(thisComponent); or
    //  - a string selector used like $bmdFormGroup.find(selector)
    //
    // Note this only runs if $bmdFormGroup.find(Selector.BMD_LABEL_WILDCARD) fails to find a label (as authored in the markup)
    //
    selectors: [
      `.form-control-label`, // in the case of horizontal or inline forms, this will be marked
      `> label`, // usual case for text inputs, first child.  Deeper would find toggle labels so don't do that.
    ],
    className: ClassName.BMD_LABEL_STATIC,
  },
  requiredClasses: [],
  invalidComponentMatches: [],
  convertInputSizeVariations: true,
};

const FormControlSizeMarkers = {
  "form-control-lg": "bmd-form-group-lg",
  "form-control-sm": "bmd-form-group-sm",
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class BaseInput extends Base {
  constructor($element, config, properties = {}) {
    super($element, Data.extendConfig(Default, config), properties);

    // Enforce no overlap between components to prevent side effects
    this._rejectInvalidComponentMatches();

    // Enforce expected structure (if any)
    this.rejectWithoutRequiredStructure();

    // Enforce required classes for a consistent rendering
    this._rejectWithoutRequiredClasses();

    // Resolve the form-group first, it will be used for bmd-form-group if possible
    //   note: different components have different rules
    this.$formGroup = this.findFormGroup(this.config.formGroup.required);

    // Will add bmd-form-group to form-group or create an bmd-form-group
    //  Performance Note: for those forms that are really performance driven, create the markup with the .bmd-form-group to avoid
    //    rendering changes once added.
    this.$bmdFormGroup = this.resolveMdbFormGroup();

    // Resolve and mark the bmdLabel if necessary as defined by the config
    this.$bmdLabel = this.resolveMdbLabel();

    // Signal to the bmd-form-group that a form-control-* variation is being used
    this.resolveMdbFormGroupSizing();

    this.addFocusListener();
    this.addChangeListener();

    if (this._element.value != "") {
      this.addIsFilled();
    }
  }

  dispose(dataKey) {
    super.dispose(dataKey);
    this.$bmdFormGroup = null;
    this.$formGroup = null;
  }

  // ------------------------------------------------------------------------
  // protected

  rejectWithoutRequiredStructure() {
    // implement
  }

  addFocusListener() {
    EventHandler.on(this._element, "focus", this.addFormGroupFocus.bind(this));
    EventHandler.on(
      this._element,
      "blur",
      this.removeFormGroupFocus.bind(this)
    );
  }

  addChangeListener() {
    const addIsFilledHandler = (event) => {
      if (Util.isChar(event)) {
        this.addIsFilled();
      }
    };

    const removeIsFilledHandler = () => {
      if (this.isEmpty()) {
        this.removeIsFilled();
      } else {
        this.addIsFilled();
      }

      if (this.config.validate) {
        let isValid =
          typeof this._element.checkValidity === "undefined" ||
          this._element.checkValidity();
        if (isValid) {
          this.removeHasDanger();
        } else {
          this.addHasDanger();
        }
      }
    };

    ["keydown", "paste"].forEach((evName) => {
      EventHandler.on(this._element, evName, addIsFilledHandler.bind(this));
    });

    ["keyup", "change"].forEach((evName) => {
      EventHandler.on(this._element, evName, removeIsFilledHandler.bind(this));
    });
  }

  addHasDanger() {
    Manipulator.addClass(this.$bmdFormGroup, ClassName.HAS_DANGER);
  }

  removeHasDanger() {
    Manipulator.addClass(this.$bmdFormGroup, ClassName.HAS_DANGER);
  }

  isEmpty() {
    return (
      this._element.value === null ||
      this._element.value === undefined ||
      this._element.value === ""
    );
  }

  // Will add bmd-form-group to form-group or create a bmd-form-group if necessary
  resolveMdbFormGroup() {
    let mfg = this.findMdbFormGroup(false);

    if (!mfg) {
      if (this.config.bmdFormGroup.create && !this.$formGroup) {
        // If a form-group doesn't exist (not recommended), take a guess and wrap the element (assuming no label).
        //  note: it's possible to make this smarter, but I need to see valid cases before adding any complexity.

        const outerElement = this.outerElement();
        const parentElement = outerElement.parentElement;

        if (Manipulator.hasClass(parentElement, ClassName.INPUT_GROUP)) {
          this.addWrap(
            this.config.bmdFormGroup.tagName,
            ClassName.BMD_FORM_GROUP,
            parentElement
          );
        } else {
          this.addWrap(
            this.config.bmdFormGroup.tagName,
            ClassName.BMD_FORM_GROUP,
            outerElement
          );
        }
      } else {
        // a form-group does exist, add our marker class to it

        Manipulator.addClass(this.$formGroup, ClassName.BMD_FORM_GROUP);
      }

      mfg = this.findMdbFormGroup(this.config.bmdFormGroup.required);
    }

    return mfg;
  }

  // Demarcation element (e.g. first child of a form-group)
  //  Subclasses such as file inputs may have different structures
  outerElement() {
    return this._element;
  }

  // Will add bmd-label to bmd-form-group if not already specified
  resolveMdbLabel() {
    let label = this.$bmdFormGroup
      ? SelectorEngine.find(Selector.BMD_LABEL_WILDCARD, this.$bmdFormGroup)
      : [];

    if (!label || label.length === 0) {
      // we need to find it based on the configured selectors
      label = this.findMdbLabel(this.config.label.required);

      if (label === undefined || label.length === 0) {
        // no label found, and finder did not require one
      } else {
        // a candidate label was found, add the configured default class name
        Manipulator.addClass(label[0], this.config.label.className);
      }
    }

    return label;
  }

  // Find bmd-label variant based on the config selectors
  findMdbLabel(raiseError = true) {
    let label = [];
    // use the specified selector order
    if (this.config.label.selectors) {
      for (let selector of this.config.label.selectors) {
        if (typeof selector === "function") {
          label = selector(this);
        } else {
          label = SelectorEngine.find(selector, this.$bmdFormGroup);
        }
      }
    }

    if (label.length === 0 && raiseError) {
      console.log(this.config.label);
      throw new Error(
        `Failed to find ${
          Selector.BMD_LABEL_WILDCARD
        } within form-group for ${Util.describe(this._element)}`
      );
    }
    return label;
  }

  // Find bmd-form-group
  findFormGroup(raiseError = false) {
    let fg = this._element.closest(Selector.FORM_GROUP);

    if (!fg && raiseError) {
      throw new Error(
        `Failed to find ${Selector.FORM_GROUP} for ${Util.describe(
          this._element
        )}`
      );
    }
    return fg;
  }

  // Due to the interconnected nature of labels/inputs/help-blocks, signal the bmd-form-group-* size variation based on
  //  a found form-control-* size
  resolveMdbFormGroupSizing() {
    if (!this.config.convertInputSizeVariations) {
      return;
    }

    // Modification - Change text-sm/lg to form-group-sm/lg instead (preferred standard and simpler css/less variants)
    for (let inputSize in FormControlSizeMarkers) {
      if (Manipulator.hasClass(this._element, inputSize)) {
        Manipulator.addClass(
          this.$bmdFormGroup,
          FormControlSizeMarkers[inputSize]
        );
      }
    }
  }

  // ------------------------------------------------------------------------
  // private
  _rejectInvalidComponentMatches() {
    if (!this._element) return;
    for (let otherComponent of this.config.invalidComponentMatches) {
      otherComponent.rejectMatch(this.constructor.name, this._element);
    }
  }

  _rejectWithoutRequiredClasses() {
    for (let requiredClass of this.config.requiredClasses) {
      let found = false;
      // allow one of several classes to be passed in x||y
      if (requiredClass.indexOf("||") !== -1) {
        let oneOf = requiredClass.split("||");
        for (let requiredClass of oneOf) {
          if (Manipulator.hasClass(this._element, requiredClass)) {
            found = true;
            break;
          }
        }
      } else if (this._element.classList.contains(requiredClass)) {
        found = true;
      }
    }
  }
}

export default BaseInput;
