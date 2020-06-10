import Vue from "vue";
import Vuex from 'vuex';
import dish from "./modules/dish";
import reducer from "./modules/reducer";
import delivery from "./modules/delivery";

import common from "../../store/common"

Vue.use(Vuex);

export const store = new Vuex.Store({
    namespaced: true,

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
        reducer,
        delivery,
        common
    }
});
