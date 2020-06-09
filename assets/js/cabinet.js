import Vue from 'vue';
import Routes from './cabinet/routes.js';
import App from './cabinet/App.vue';

import vuetify from './plugins/vuetify';
import {store} from "./cabinet/store";

const cabinet = new Vue({
    el: '#app',
    router: Routes,
    store,
    render: h => h(App),
    vuetify,
   });

import '../css/cabinet.scss';

console.log('Cabinet was loaded');
