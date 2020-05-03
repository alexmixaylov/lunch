import Vue from 'vue';
import Routes from './routes.js';
import App from './views/App.vue';

import vuetify from './plugins/vuetify';
import store from './stores/index'



const app = new Vue({
    el: '#app',
    router: Routes,
    store,
    render: h => h(App),
    vuetify
});

import '../css/app.scss';

export default app;

console.log('Application was loaded');
