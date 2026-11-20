import Util from "./util";
import BaseComponent from "./base-component";

import Data from "@Dom/data";
import EventHandler from "@Dom/event-handler";
import Manipulator from "@Dom/manipulator";

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */
const NAME = "ripples";
const DATA_KEY = `bmd.${NAME}`;

const ClassName = {
  CONTAINER: "ripple-container",
  DECORATOR: "ripple-decorator",
};

const Selector = {
  CONTAINER: `.${ClassName.CONTAINER}`,
  DECORATOR: `.${ClassName.DECORATOR}`, //,
};

const Default = {
  container: {
    template: `<div class='${ClassName.CONTAINER}'></div>`,
  },
  decorator: {
    template: `<div class='${ClassName.DECORATOR}'></div>`,
  },
  trigger: {
    start: "mousedown touchstart",
    end: "mouseup mouseleave touchend",
  },
  touchUserAgentRegex: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i,
  duration: 500,
};

/**
 * ------------------------------------------------------------------------
 * Class Definition || extends BaseComponent
 * ------------------------------------------------------------------------
 */
class Ripples extends BaseComponent {
  constructor($element, config) {
    super($element, Data.extendConfig(Default, config));

    // attach initial listener
    const startRippleHangler = (event) => {
      this._onStartRipple(event);
    };

    const eventsStart = this.config.trigger.start.split(" ");

    eventsStart.forEach((eventNameItem) => {
      EventHandler.on(
        this._element,
        eventNameItem,
        startRippleHangler.bind(this)
      );
    });
  }

  dispose() {
    super.dispose();
    this.$container = null;
    this.$decorator = null;
  }

  // ------------------------------------------------------------------------
  // private

  _onStartRipple(event) {
    // Verify if the user is just touching on a device and return if so
    if (this._isTouch() && event.type === "mousedown") {
      return;
    }

    // Find or create the ripple container element
    this._findOrCreateContainer();

    // Get relY and relX positions of the container element
    let relY = this._getRelY(event);
    let relX = this._getRelX(event);

    // If relY and/or relX are false, return the event
    if (!relY && !relX) {
      return;
    }

    // set the location and color each time (even if element is cached)

    Manipulator.css(this.$decorator, {
      left: relX,
      top: relY,
      backgroundColor: this._getRipplesColor(),
    });

  
    // Make sure the ripple has the styles applied (ugly hack but it works)
    this._forceStyleApplication();

    // Turn on the ripple animation
    this.rippleOn();

    // Call the rippleEnd function when the transition 'on' ends
    setTimeout(() => {
      this.rippleEnd();
    }, this.config.duration);

    // Detect when the user leaves the element to cleanup if not already done?

    const endRippleHangler = () => {
      if (this.$decorator) {
        // guard against race condition/mouse attack

        Manipulator.setDataAttributeNoBs(this.$decorator, "mousedown", "off");
        
        if (Manipulator.getDataAttributeNoBs(this.$decorator, "animating")) {
          this.rippleOut();
        }
      }
    };

    const eventsEnd = this.config.trigger.end.split(" ");
    eventsEnd.forEach((eventNameItem) => {
      EventHandler.on(
        this._element,
        eventNameItem,
        endRippleHangler.bind(this)
      );
    });
  }

  _findOrCreateContainer() {
    
    if (!this.$container) {
      this._element.insertAdjacentHTML(
        "beforeend",
        this.config.container.template
      );      
      this.$container = this._element.querySelector(Selector.CONTAINER);
    }
    this.$container.insertAdjacentHTML(
      "beforeend",
      this.config.decorator.template
    );
    this.$decorator = this.$container.querySelector(Selector.DECORATOR);
    // always add the rippleElement, it is always removed
   
  }

  // Make sure the ripple has the styles applied (ugly hack but it works)
  _forceStyleApplication() {
    // return window.getComputedStyle(this.$decorator[0]).opacity;
    return window.getComputedStyle(this.$decorator).opacity;
  }

  /**
   * Get the relX
   */
  _getRelX(event) {
   
    let wrapperOffset = Manipulator.offset(this.$container);

    let result = null;
    if (!this._isTouch()) {
      // Get the mouse position relative to the ripple wrapper
      result = event.pageX - wrapperOffset.left;
    } else {
      // Make sure the user is using only one finger and then get the touch
      //  position relative to the ripple wrapper
      event = event.originalEvent;

      if (event.touches.length === 1) {
        result = event.touches[0].pageX - wrapperOffset.left;
      } else {
        result = false;
      }
    }

    return result;
  }

  /**
   * Get the relY
   */
  _getRelY(event) {
    // let containerOffset = this.$container.offset();
    let containerOffset = Manipulator.offset(this.$container);
    let result = null;

    if (!this._isTouch()) {
      /**
       * Get the mouse position relative to the ripple wrapper
       */
      result = event.pageY - containerOffset.top;
    } else {
      /**
       * Make sure the user is using only one finger and then get the touch
       * position relative to the ripple wrapper
       */
      event = event.originalEvent;

      if (event.touches.length === 1) {
        result = event.touches[0].pageY - containerOffset.top;
      } else {
        result = false;
      }
    }

    return result;
  }

  /**
   * Get the ripple color
   */
  _getRipplesColor() {
    const rippleColor = Manipulator.getDataAttributeNoBs(
      this._element,
      "ripple-color"
    );

    let color = rippleColor
      ? rippleColor
      : window.getComputedStyle(this._element).color;

    return color;
  }

  /**
   * Verify if the client is using a mobile device
   */
  _isTouch() {
    return this.config.touchUserAgentRegex.test(navigator.userAgent);
  }

  /**
   * End the animation of the ripple
   */
  rippleEnd() {
    if (this.$decorator) {
      // guard against race condition/mouse attack

      Manipulator.setDataAttributeNoBs(this.$decorator, "animating", "off");      
      if (Manipulator.getDataAttributeNoBs(this.$decorator, "mousedown")) {
        this.rippleOut(this.$decorator);
      }
    }
  }

  /**
   * Turn off the ripple effect
   */
  rippleOut() {

    // $(this.$decorator).off(); //????

    if (Util.transitionEndSupported()) {     
      Manipulator.addClass(this.$decorator, "ripple-out");
    } else {
      const animation = this.$decorator.animate(
        {
          opacity: 0,
        },
        {
          opacity: 1,
        },
        100
      );
      animation.onfinish = () => {
        EventHandler.trigger(this.$decorator, "transitionend", {
          relatedTarget: this.$decorator,
        });
      };
    }

    const transitionEndEventsName = Util.transitionEndSelector().split(" ");
    const transitionEndHandler = () => {
      if (this.$decorator) {
        this.$decorator.remove();
        this.$decorator = null;
      }
    };
 
    transitionEndEventsName.forEach((eventNameItem) => {
      EventHandler.on(
        this.$decorator,
        eventNameItem,
        transitionEndHandler.bind(this)
      );
    });
  }

  /**
   * Turn on the ripple effect
   */
  rippleOn() {
    let size = this._getNewSize();

    if (Util.transitionEndSupported()) {
      Manipulator.css(this.$decorator, {
        transform: `scale(${size})`,
      });

      Manipulator.addClass(this.$decorator, "ripple-on");
      Manipulator.setDataAttributeNoBs(this.$decorator, "animating", "on");
      Manipulator.setDataAttributeNoBs(this.$decorator, "mousedown", "on");

    } else {
      const animation = this.$decorator.animate(
        {
          width:
            Math.max(this._element.offsetWidth, this._element.offsetHeight) * 2,
          height:
            Math.max(this._element.offsetWidth, this._element.offsetHeight) * 2,
          marginLeft:
            Math.max(this._element.offsetWidth, this._element.offsetHeight) *
            -1,
          marginTop:
            Math.max(this._element.offsetWidth, this._element.offsetHeight) *
            -1,
          opacity: 0.2,
        },
        {
          width: Math.max(
            this._element.offsetWidth,
            this._element.offsetHeight
          ),
          height: Math.max(
            this._element.offsetWidth,
            this._element.offsetHeight
          ),
          marginLeft: Math.max(
            this._element.offsetWidth,
            this._element.offsetHeight
          ),
          marginTop: Math.max(
            this._element.offsetWidth,
            this._element.offsetHeight
          ),
          opacity: 1,
        },
        this.config.duration
      );
      animation.onfinish = () => {
        EventHandler.trigger(this.$decorator, "transitionend", {
          relatedTarget: this.$decorator,
        });
      }; 
    }
  }

  /**
   * Get the new size based on the element height/width and the ripple width
   */
  _getNewSize() {
    return (
      (Math.max(this._element.offsetWidth, this._element.offsetHeight) /
        this.$decorator.offsetWidth) *
      2.5
    );
  }

  _getConfig(config) {
    const _config = typeof config === "object" && config !== null ? config : {};
    config = {
      ...this.constructor.Default,
      ..._config,
    };
    return config;
  }

  // ------------------------------------------------------------------------

  // Getters
  static get Default() {
    return Default;
  }

  static get DATA_KEY() {
    return DATA_KEY;
  }
  // static

  static elementInterface($element, config) {
    const data =
      Data.getData($element, DATA_KEY) || new Ripples($element, config);
    return data;
  }

}

export default Ripples;
