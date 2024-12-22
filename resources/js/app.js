
import './bootstrap';
import router from './router/index'
// iView plugin
import ViewUIPlus from 'view-ui-plus'
import 'view-ui-plus/dist/styles/viewuiplus.css'
// Vuex
import store from './store'
import common from './common';
import { createApp } from 'vue';


const app = createApp({});

import mainapp from './components/main-admin.vue';
app.component('mainapp', mainapp);
import navbar from './components/customer-home.vue';
app.component('navbar', navbar);

app.mixin(common)
app.use(store);
app.use(ViewUIPlus);
app.use(router);
app.mount('#app');
