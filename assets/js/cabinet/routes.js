import Vue from 'vue';
import VueRouter from 'vue-router';
import Main from "./pages/Main";
import Orders from "./pages/Orders";
import Profile from "./pages/Profile";

Vue.use(VueRouter);

const router = new VueRouter({
    routes: [
        {path: '/', name: 'main', component: Main},
        {path: '/orders', name: 'orders', component: Orders},
        {path: '/profile', name: 'profile', component: Profile}
    ]
})

export default router;
