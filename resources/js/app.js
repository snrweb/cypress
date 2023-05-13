import { createApp } from 'vue';
import Vuetify from "./vuetify";
import "material-design-icons-iconfont/dist/material-design-icons.css";
import moment from "moment";
import SvgIcon from "@jamescoyle/vue-icon";
import router from "./router";
import store from "./store";
import App from './components/App.vue';

const app = createApp({
    el: "#app",
    store,
    router,
    Vuetify,
    components: { App },
});
app.config.productionTip = false;

app.use(Vuetify);
app.use(router);
app.use(store);

app.component("main-app", App);

app.mount('#app');
