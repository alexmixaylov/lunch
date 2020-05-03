import Vue from "vue";
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);


const store = new Vuex.Store({
    state: {
        dishes: [],
        menus: [],

    },
    getters: {
        getDishes: state => {
            return state.dishes;
        },
        getMenus: state => {
            return state.menus;
        },

    },
    mutations: {
        setDishes(state, payload) {
            state.dishes = payload
        },
        setMenus(state, payload) {
            state.menus = payload
        }
    },
    actions: {
        async loadDishes({dispatch, commit}) {
            commit('setDishes', [1, 2])
        }
    }
});

export default {
    store
}
