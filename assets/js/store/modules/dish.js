import axios from 'axios';

export default {
    namespaced: true,
    state: {
        dishes: [],
    },
    getters: {
        getDishes: state => {
            return state.dishes;
        },
    },
    mutations: {
        setDishes(state, payload) {
            state.dishes = payload
        },
    },
    actions: {
        loadDishes({commit}) {
            console.log('dish/loadDishes was called')
            commit('setDishes', ['новые диши зашли', 'никуда теперь не дется'])
        }
    },
}
