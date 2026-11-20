import "picturefill";
import App from "./app";
import BootstrapMaterialDesign from "../bootstrap-md/app-design";
import Manipulator from "@Dom/manipulator";

const APP_BODY_PARAMS_NAME_KEY = "params";

const config = {};

document.addEventListener("DOMContentLoaded", () => {
  const paramsData = Manipulator.getDataAttributeNoBs(
    document.body,
    APP_BODY_PARAMS_NAME_KEY
  );

  if (paramsData) {
    const params = JSON.parse(paramsData);
    Object.keys(params).forEach((key) => {
      window[key] = params[key];
    });
  }

  App.init(config);
  BootstrapMaterialDesign.elementInterface(document.body);
  BootstrapMaterialDesign.init();
});
