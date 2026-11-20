/**
 * --------------------------------------------------------------------------
 * Bootstrap (v5.0.0-beta2): dom/data.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/main/LICENSE)
 * --------------------------------------------------------------------------
 */

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const mapData = (() => {
  const storeData = {};
  let id = 1;
  return {
    set(element, key, data, nameKey) {
      if (typeof element[nameKey] === "undefined") {
        element[nameKey] = {
          key,
          id,
        };
        id++;
      }
      storeData[element[nameKey].id] = data;
    },
    get(element, key, nameKey) {
      if (!element || typeof element[nameKey] === "undefined") {
        return null;
      }

      const keyProperties = element[nameKey];

      if (keyProperties.key === key) {
        return storeData[keyProperties.id];
      }

      return null;
    },
    delete(element, key, nameKey) {
      if (typeof element[nameKey] === "undefined") {
        return;
      }

      const keyProperties = element[nameKey];
      if (keyProperties.key === key) {
        delete storeData[keyProperties.id];
        delete element[nameKey];
      }
    },
  };
})();

const Data = {
  setData(instance, key, data, nameKey = "bsKey") {
    mapData.set(instance, key, data, nameKey);
  },
  getData(instance, key, nameKey = "bsKey") {
    return mapData.get(instance, key, nameKey);
  },
  removeData(instance, key, nameKey = "bsKey") {
    mapData.delete(instance, key, nameKey);
  },
  validatorConfig(config) {
    return typeof config === "object" && config !== null ? config : {};
  },
  extendConfig(defaultConfig, config, debug) {
    const _config = typeof config === "object" && config !== null ? config : {};
    const _defaultConfig =
      typeof defaultConfig === "object" && defaultConfig !== null
        ? defaultConfig
        : {};

    // const rezult = {};

    // const keys = new Set([
    //   ...Object.keys(_defaultConfig),
    //   ...Object.keys(_config),
    // ]);

    // for (let key of keys) {
    //   if (!_defaultConfig[key]) {
    //     rezult[key] = _config[key];
    //     continue;
    //   }
    //   if (!_config[key]) {
    //     rezult[key] = _defaultConfig[key];
    //     continue;
    //   }
    //   if (
    //     typeof _defaultConfig[key] !== "object" ||
    //     typeof _config[key] !== "object"
    //   ) {
    //     rezult[key] = _config[key];
    //     continue;
    //   }

    //   // rezult[key] = { ..._defaultConfig[key], ..._config[key] };
    //   rezult[key] = _config[key];
    // }

    const rezult = {
      ..._defaultConfig,
      ..._config,
    };
    if (debug) {
      console.log("extendConfig_defaultConfig", _defaultConfig);
      console.log("extendConfig_config", _config);
      console.log("extendConfig_rezult", rezult);
    }

    return rezult;
  },
};

export default Data;
