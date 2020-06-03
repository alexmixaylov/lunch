import Vue from 'vue';
import Routes from './routes.js';
import App from './views/App.vue';

import vuetify from './../plugins/vuetify';
import {store} from "./store";

const app = new Vue({
    el: '#app',
    router: Routes,
    store,
    render: h => h(App),
    vuetify,
   });

import '../../css/cabinet.scss';

console.log('Cabinet was loaded');
