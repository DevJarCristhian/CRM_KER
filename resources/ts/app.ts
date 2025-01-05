import "../views/ss";
import { createApp } from "vue";
import { createPinia } from "pinia";
import App from "./App.vue";
import router from "./router";
import Naive from "naive-ui";
import { registerGlobalComponents } from "./utils/GlobalComponents";

const pinia = createPinia();
const app = createApp(App);
app.use(router);
app.use(pinia);
app.use(Naive);
registerGlobalComponents(app);

app.mount("#app");
