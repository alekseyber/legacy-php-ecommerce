import fetchApp from "./app-fetch";
import EventHandler from "@Dom/event-handler";
import {
  EVENT_CHANGE_CITY,
  EVENT_JS_L_TOGGLE,
  SELECTOR_CONTEINER_DELIV,
} from "@Constans";

const SOURCE_SEARCH = "products";
const SOURCE_CITY = "city";

const SOURCE_LIST = [SOURCE_SEARCH, SOURCE_CITY];

const BIND_ACTIONS = {
  [SOURCE_SEARCH]: {
    setSelect: function ({ link }) {
      if (!link) return;
      if (!window.jsLOff2) {
        EventHandler.trigger(this.selector._element, EVENT_JS_L_TOGGLE, {
          detail: {
            url: link,
            onOnpostat: true,
          },
        });
      } else {
        window.location = link;
      }
    },
  },
  [SOURCE_CITY]: {
    setSelect: function (selectedValue) {
      const targetEl = this.selector._getParrentElement(
        SELECTOR_CONTEINER_DELIV,
        this.selector._element
      );

      EventHandler.trigger(targetEl, EVENT_CHANGE_CITY, {
        detail: {
          selectedValue,
        },
      });
    },
  },
};

const WILDCARD = "%QUERY";

const URL = {
  [SOURCE_SEARCH]: `/search/typeahead?query=${WILDCARD}`,
  [SOURCE_CITY]: `/delivery/typeahead?query=${WILDCARD}`,
};

const CONFIG_SET = {
  [SOURCE_SEARCH]: {
    greatTitle: (item) => {
      return `${item.title}`;
    },
    getSelectedValue: () => "",
    divider: true,
    dividerCount: 7,
    dividerContent: `<li><hr class="dropdown-divider" /></li>
        <a class="dropdown-item js-link-true2" href="/search?s=%QUERY">Показать все</a>`,
  },
  [SOURCE_CITY]: {
    greatTitle: (item) => {
      return `
      <div>
        <div class="fw-bold text-wrap">${item.CityName}</div>
        <div class="text-wrap">${item.OblName}</div>        
      </div>      
      `;
    },
    getSelectedValue: (item) => {
      return `${item.CityName}`;
    },
    divider: false,
  },
};

class AppSource {
  constructor(selector, source) {
    if (SOURCE_LIST.indexOf(source) < 0) {
      throw new Error(
        `${source} отсутствует в списке зарегистированных source`
      );
    }
    this.selector = selector;
    this.sourceName = source;
    this.selector.setConfig(CONFIG_SET[this.sourceName]);
    this.url = URL[this.sourceName];
    return {
      setValue: this.setValue.bind(this),
      setSelect: BIND_ACTIONS[this.sourceName].setSelect.bind(this),
    };
  }

  //public
  setValue(value) {
    this._request(this._getUrl(value));
  }
  _getUrl(value) {
    return this.url.replace(WILDCARD, value);
  }
  async _request(url) {
    try {
      this.selector.setLoading(true);
      const rezult = await fetchApp.get(url);

      this.selector.setLoading();

      if (!rezult) return this.selector.setNotFound();
      if (rezult.length === 0) return this.selector.setNotFound();

      this.selector.setList(rezult);
    } catch (e) {
      //e.message
      // this.selector.setError("Получена ошибка, повторите позже");
      console.log(e);
      this.selector.setError(e.message);
    }
  }
}

export default AppSource;
