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
import OrderRead from "./components/order/OrderRead";
import OrderEdit from "./components/order/OrderEdit";
import AdminOrderCreate from "../admin/pages/orders/AdminOrderCreate"
import Company from "./pages/Company";
import CompanyRead from "./components/company/CompanyRead";
import DeliveryRead from "./components/delivery/DeliveryRead";
import Login from "./components/auth/Login";

Vue.use(VueRouter);

const router = new VueRouter({
    // mode: 'history',
    routes: [
        {path: '/', name: 'home', component: Home},
        {path: '/week', name: 'week', component: Week},
        {path: '/kitchen', name: 'kitchen', component: Kitchen},
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
            component: AdminOrderCreate,
        },
        {
            path: '/orders/:id/edit',
            name: 'orders#edit',
            component: OrderEdit
        },
        {
            path: '/orders/:id',
            name: 'orders#read',
            component: OrderRead,
        },
        {
            path: '/companies',
            name: 'companies',
            component: Company
        },
        {
            path: '/companies/:id',
            name: 'companies#read',
            component: CompanyRead
        },
        {
            path: '/delivery',
            name: 'delivery',
            component: Delivery
        },
        {
            path: '/delivery/:company',
            name: 'delivery#by_company',
            component: DeliveryRead,
        },
        {
            path: '/login',
            name: 'login',
            component: Login
        }

    ]
});

export default router;
