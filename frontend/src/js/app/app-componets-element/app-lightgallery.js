import LightGallery from "@Components/lightgallery";

import Data from "@Dom/data";
import BaseComponent from "./base-app-component";
import { APP_NAME_SPACE } from "@Constans";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const DATA_KEY = `${APP_NAME_SPACE}.lightgallery`;
// const EVENT_KEY = `.${DATA_KEY}`;

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */
const Default = {
  download: false,
  nextHtml: '<span class="material-icons">arrow_forward</span>',
  prevHtml: '<span class="material-icons">arrow_back</span>',
  thumbnail: false,
  fullScreen: false,
};

const CONFIG_VALID_KEYS = [
  "download",
  "nextHtml",
  "prevHtml",
  "thumbnail",
  "fullScreen",
];

class AppLightgallery extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    try {
      const configGal = {};
      CONFIG_VALID_KEYS.forEach((key) => {
        if (this.config[key] !== undefined) {
          configGal[key] = this.config[key];
        }
      });

      this.lightGallery = new LightGallery(this._element, configGal);
    } catch (e) {
      this._logError(e);
    }
  }
  // Public

  slide(number = 0) {
    this.lightGallery.slide(number);
  }
  goToNextSlide() {
    this.lightGallery.goToNextSlide();
  }
  goToPrevSlide() {
    this.lightGallery.goToPrevSlide();
  }

  dispose() {
    super.dispose();
    this.lightGallery.destroy(true);
    this.lightGallery = null;
  }

  // Static


  static get DATA_KEY() {
    return DATA_KEY;
  }
}

export default AppLightgallery;
