import Vue from "vue";
import Vuex from 'vuex';
import person from "../../store/modules/person";
import common from "../../store/common"


Vue.use(Vuex);

export const store = new Vuex.Store({
    namespaced: true,
    modules: {
        person,
        common
    }
});
