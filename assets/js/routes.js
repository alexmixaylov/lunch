import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from "./components/Home";
import Menu from './components/Menu';
import Week from "./components/Week";
import Kitchen from "./components/Kitchen";
import Delivery from "./components/Delivery";
import Money from "./components/Money";
import Order from "./components/Order";

Vue.use(VueRouter);

const router = new VueRouter({
    // mode: 'history',
    routes:[
        {path: '/', name: 'home', component: Home},
        {path:'/menu', name:'menu', component:Menu},
        {path:'/week', name:'week', component:Week},
        {path:'/kitchen', name:'kitchen', component:Kitchen},
        {path:'/delivery', name:'delivery', component:Delivery},
        {path:'/money', name:'money', component:Money},
        {path:'/order', name:'order', component:Order},
        // {path:'/delivery', name:'delivery', component:Delivery}
    ]
});

export default router;
