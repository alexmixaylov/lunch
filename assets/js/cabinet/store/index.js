import Vue from "vue";
import Vuex from 'vuex';
import order from "./modules/order";
import user from "./modules/user"
import menu from "./modules/menu"

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        order,
        user,
        menu
    }
});
