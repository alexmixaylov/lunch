import Vue from 'vue';
import Routes from './admin/routes.js';
import App from './admin/views/App.vue';

import vuetify from './plugins/vuetify';
import {store} from './admin/store'



const admin = new Vue({
    el: '#app',
    router: Routes,
    store,
    render: h => h(App),
    vuetify
});

import '../css/app.scss';

export default admin;

console.log('Application was loaded');
