import Vue from 'vue';
import VueRouter from 'vue-router';

import Menu from './components/Menu';

Vue.use(VueRouter);

const router = new VueRouter({
    // mode: 'history',
    routes:[
        {path:'/menu', name:'menu', component:Menu}
    ]
});

export default router;
