import Base from "./base";
import Util from "./util";
import Data from "@Dom/data";

const ClassName = {
  CANVAS: "bmd-layout-canvas",
  CONTAINER: "bmd-layout-container",
  BACKDROP: `bmd-layout-backdrop`,
};

const Selector = {
  CANVAS: `.${ClassName.CANVAS}`,
  CONTAINER: `.${ClassName.CONTAINER}`,
  BACKDROP: `.${ClassName.BACKDROP}`,
};

const Default = {
  canvas: {
    create: true, //
    required: true,
    template: `<div class="${ClassName.CANVAS}"></div>`,
  },
  backdrop: {
    create: true,
    required: true,
    template: `<div class="${ClassName.BACKDROP}"></div>`,
  },
};

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
class BaseLayout extends Base {
  constructor($element, config, properties = {}) {
    super($element, Data.extendConfig(Default, config), properties);

    this.$container = this.findContainer(true);
    this.$backdrop = this.resolveBackdrop();
    this.resolveCanvas();
  }

  dispose() {
    super.dispose();
    this.$container = null;
    this.$backdrop = null;
  }

  // ------------------------------------------------------------------------
  // protected

  // Will wrap container in bmd-layout-canvas if necessary
  resolveCanvas() {
    let bd = this.findCanvas(false);
    // if (bd === undefined || bd.length === 0) {
    if (!bd) {
      if (this.config.canvas.create) {
        this.addWrap("div", ClassName.CANVAS, this.$container);
      }

      bd = this.findCanvas(this.config.canvas.required);
    }

    return bd;
  }

  // Find closest bmd-layout-container based on the given context
  findCanvas(raiseError = true, context = this.$container) {
    let canvas = context.closest(Selector.CANVAS);

    if (!canvas && raiseError) {
      throw new Error(
        `Failed to find ${Selector.CANVAS} for ${Util.describe(context)}`
      );
    }
    return canvas;
  }

  // Will add bmd-layout-backdrop to bmd-layout-container if necessary
  resolveBackdrop() {
    let bd = this.findBackdrop(false);
    if (!bd) {
      if (this.config.backdrop.create) {
        this.$container.insertAdjacentHTML(
          "beforeend",
          this.config.backdrop.template
        );
      }

      bd = this.findBackdrop(this.config.backdrop.required);
    }

    return bd;
  }

  // Find closest bmd-layout-container based on the given context
  findBackdrop(raiseError = true, context = this.$container) {
    // let backdrop = context.find(`> ${Selector.BACKDROP}`);
    let backdrop = context.querySelector(Selector.BACKDROP);
    if (!backdrop && raiseError) {
      throw new Error(
        `Failed to find ${Selector.BACKDROP} for ${Util.describe(context)}`
      );
    }
    return backdrop;
  }

  // Find closest bmd-layout-container based on the given context
  findContainer(raiseError = true, context = this._element) {
    let container = context.closest(Selector.CONTAINER);
    if (!container && raiseError) {
      throw new Error(
        `Failed to find ${Selector.CONTAINER} for ${Util.describe(context)}`
      );
    }
    return container;
  }
}

export default BaseLayout;
