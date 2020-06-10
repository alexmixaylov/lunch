import Vue from "vue";
import Vuex from 'vuex';
import user from '../../store/modules/user'
import dish from "./modules/dish";
import reducer from "./modules/reducer";
import company from "../../store/modules/company";
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
        user,
        dish,
        reducer,
        company,
        delivery,
        common
    }
});
