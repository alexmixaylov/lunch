import Vue from "vue";
import Vuex from 'vuex';
import auth from './modules/auth'
import menu from "./modules/menu";
import dish from "./modules/dish";
import order from "./modules/order";
import reducer from "./modules/reducer";
import company from "./modules/company";
import delivery from "./modules/delivery";

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
        auth,
        dish,
        menu,
        order,
        reducer,
        company,
        delivery
    }
});
