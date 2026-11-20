import { BASE_URL } from "@Constans/index";
import { addGET } from "@AppUtil/util";

class HTTPError extends Error {
  constructor() {
    super("Fetch error");
    const response = arguments[0];
    this.response = null;

    if (response instanceof Response) {
      this.message = `Fetch error: ${response.statusText}`;
      this.status = response.status;
      this.response = response;
    }
  }
}

const fetcErrorHangler = async (error, json) => {
  try {
    const response = error.response;
    if (!response || !json) throw new Error(error.message);

    const rezult = await response.json();

    if (rezult) {
      rezult.status = response.status;
      rezult.statusText = response.statusText;
      rezult.succes = false;
      console.warn(`Fetch error: ${response.statusText} - ${response.url}`);
      return rezult;
    }

    throw new Error(error.message);
  } catch (e) {
    throw new Error(`fetcErrorHangler - ${e.message}`);
  }
};

const BASE_HEADERS = {
 "X-Requested-With": "XMLHttpRequest",
  // "Content-Type": "application/json",
};
const getCacheParams = (cacheOn) => {
  return cacheOn ? "default" : "no-store";
};

const fFetc = async (
  url,
  json = true,
  cacheOn = true,
  post = false,
  data = null,
  addHeaders = {}
) => {
  try {
    url = BASE_URL + url;

    if (json) {
      url = addGET(url, "json", "1");
     // addHeaders['Content-Type'] = 'application/json';
    }
   
    const headers = { ...BASE_HEADERS, ...addHeaders };
    const method = post ? "POST" : "GET";

    const params = {
      headers,
      method,
      cache: getCacheParams(cacheOn),
    };
    if (data !== null && typeof data === "object" && post) {
      if (data instanceof FormData) {
        params.body = data;
      } else {
        params.body = new FormData();
        Object.keys(data).forEach((name) => {
          params.body.append(name, data[name]);
        });
      }
    }

    const response = await fetch(url, params);

    if (!response.ok) {
      //  throw new HTTPError(`Fetch error: ${response.statusText}`);
      throw new HTTPError(response);
    }
    if (!json) {
      return await response.text();
    }

    return await response.json();
  } catch (error) {
    if (error instanceof HTTPError) {
      return await fetcErrorHangler(error, json);
    }
    throw new Error(error);
  }
};

const fetchApp = {
  async get(url, json = true, cacheOn = true) {
    return await fFetc(url, json, cacheOn);
  },
  async post(url, data, json = true, cacheOn = true) {
    return await fFetc(url, json, cacheOn, true, data);
  },
};

export default fetchApp;
