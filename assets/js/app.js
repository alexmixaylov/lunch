import Vue from 'vue';
import Vuetify from 'vuetify';
import Routes from './routes.js';
import App from './views/App.vue';
Vue.use(Vuetify);

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
});

import '../css/app.scss';

export default app;

console.log('Application was loaded');
