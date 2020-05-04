import Vue from "vue";
import Vuex from 'vuex';
import actionsGlobal from './actions';
import mutationsGlobal from './mutations';
import menu from "./modules/menu";
import dish from "./modules/dish";

Vue.use(Vuex);

 export const store = new Vuex.Store({
    state: {
        global: true,
    },
    getters:{
        getglobal: state => {
            return state.global;
        },
    },
    modules: {
        actionsGlobal,
        mutationsGlobal,
        dish,
        menu
    }
});
