import Vue from "vue";
import Vuex from 'vuex';
import user from "./modules/user"
import menu from "./modules/menu"
import person from "./modules/person";
import common from "../../store/common-store"


Vue.use(Vuex);

export const store = new Vuex.Store({
    namespaced: true,
    modules: {
        user,
        menu,
        person,
        common
    }
});
