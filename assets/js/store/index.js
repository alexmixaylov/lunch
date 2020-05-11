import Vue from "vue";
import Vuex from 'vuex';
import actionsGlobal from './actions';
import mutationsGlobal from './mutations';
import menu from "./modules/menu";
import dish from "./modules/dish";
import actions from "./actions";
import order from "./modules/order";
import reducer from "./modules/reducer";

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        global: true,
    },
    getters: {},
    actions,
    modules: {
        dish,
        menu,
        order,
        reducer
    }
});
