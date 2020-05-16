import Vue from "vue";
import Vuex from 'vuex';
import menu from "./modules/menu";
import dish from "./modules/dish";
import order from "./modules/order";
import reducer from "./modules/reducer";
import company from "./modules/company";

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        globalDate: false,
    },
    getters: {
        getGlobalDate: state => {
            return state.globalDate
        }
    },
    mutations: {
        setGlobalDate: (state, payload) => {
            state.globalDate = payload
        }
    },
    actions: {},
    modules: {
        dish,
        menu,
        order,
        reducer,
        company
    }
});
