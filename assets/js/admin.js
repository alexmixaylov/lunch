import Vue from 'vue';
import Routes from './admin/routes.js';
import App from './admin/App.vue';

import vuetify from './plugins/vuetify';
import {store} from './admin/store'
import '../css/app.scss';

const admin = new Vue({
    el: '#app',
    router: Routes,
    store,
    render: h => h(App),
    vuetify
});

export default admin;

console.log('Application was loaded');
