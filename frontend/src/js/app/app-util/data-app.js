const mapData = (() => {
  const storeData = {};

  return {
    set(key, data) {
      if (typeof data === "undefined" || typeof key !== "string") return;
      storeData[key] = data;
    },
    get(key) {
      if (typeof key !== "string") {
        return null;
      }
      if (storeData[key]) {
        return storeData[key];
      }

      return null;
    },
    delete(key) {
      if (typeof key !== "string") {
        return;
      }
      if (storeData[key]) {
        delete storeData[key];
      }
    },
  };
})();

const DataApp = {
  setData(key, data) {
    mapData.set(key, data);
  },
  getData(key) {
    return mapData.get(key);
  },
  removeData(key) {
    mapData.delete(key);
  },
};

export default DataApp;
