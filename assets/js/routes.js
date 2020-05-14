import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from "./components/Home";
import Menu from './pages/Menu';
import Week from "./pages/Week";
import Kitchen from "./pages/Kitchen";
import Delivery from "./pages/Delivery";
import Money from "./pages/Money";
import Orders from "./pages/Orders";
import Dish from "./components/dish/DishTeaser";
import OrderCreate from "./components/order/OrderCreate";
import OrderRead from "./components/order/OrderRead";

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
            path: '/orders',
            name: 'orders',
            component: Orders,
        },
        {
            path: '/orders/create',
            name: 'orders#create',
            component: OrderCreate,
        },
        {
            path: '/orders/:id',
            name: 'orders#read',
            component: OrderRead,
        },
    ]
});

export default router;
