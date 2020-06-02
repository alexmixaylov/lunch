import Vue from 'vue';
import VueRouter from 'vue-router';
import Main from "./pages/Main";
import Orders from "./pages/orders/Orders";
import OrderRead from "./pages/orders/OrderRead";
import CreateOrder from "./pages/orders/CreateOrder";
import Profile from "./pages/Profile";
import Employees from "./pages/Employees";


Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {path: '/', name: 'main', component: Main},
        {path: '/orders', name: 'orders', component: Orders},
        {path: '/orders/create', name: 'orders#create', component: CreateOrder},
        {path: '/orders/:id', name: 'orders#read', component: OrderRead},
        {path: '/profile', name: 'profile', component: Profile},
        {path: '/employees', name: 'employees', component: Employees}
    ]
})

export default router;
