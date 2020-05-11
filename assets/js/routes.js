import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from "./components/Home";
import Menu from './components/Menu';
import Week from "./components/Week";
import Kitchen from "./components/Kitchen";
import Delivery from "./components/Delivery";
import Money from "./components/Money";
import Order from "./components/order/Order";
import Dish from "./components/Dish";
import CreateOrder from "./components/order/Create";

Vue.use(VueRouter);

const router = new VueRouter({
    // mode: 'history',
    routes: [
        {path: '/', name: 'home', component: Home},
        {path: '/week', name: 'week', component: Week},
        {path: '/kitchen', name: 'kitchen', component: Kitchen},
        {path: '/delivery', name: 'delivery', component: Delivery},
        {path: '/money', name: 'money', component: Money},

        {path: '/dish', name: 'dish', component: Dish},
        {
            path: '/menus/:date',
            name: 'menu',
            component: Menu,
            children: [
                {
                    path: 'dishes',
                    name: 'dishes',
                    component: Dish
                }

            ]
        },
        {
            path: '/order',
            name: 'order',
            component: Order,
            children: [
                {
                    path: 'create',
                    name: 'order#create',
                    component: CreateOrder
                }
            ]
        },
    ]
});

export default router;
