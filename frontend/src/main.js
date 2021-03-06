import {createApp} from "vue";
import App from "./App.vue";
import ElementPlus from "element-plus";
import "element-plus/lib/theme-chalk/index.css";
import "./scss/index.scss";
import store from "./store";

createApp(App)
  .use(store)
  .use(ElementPlus)
  .mount("#app");
