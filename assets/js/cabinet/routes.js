import Vue from 'vue';
import VueRouter from 'vue-router';
import Main from "./pages/Main";
import Orders from "../common/pages/orders/Orders";
import OrderRead from "../common/pages/orders/OrderRead";
// import CreateOrder from "../common/pages/orders/OrderCreate";
import CreateOrder from "../cabinet/pages/orders/UserOrderCreate";

import Profile from "./pages/Profile";
import Employees from "./pages/Employees";
import OrderEdit from "../common/pages/orders/OrderEdit";


Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {path: '/', name: 'main', component: Main},
        {path: '/orders', name: 'orders', component: Orders},
        {path: '/orders/create', name: 'orders#create', component: CreateOrder},
        {path: '/orders/:id', name: 'orders#read', component: OrderRead},
        {path: '/orders/:id/edit', name: 'orders#edit', component: OrderEdit},
        {path: '/profile', name: 'profile', component: Profile},
        {path: '/employees', name: 'employees', component: Employees}
    ]
})

export default router;
