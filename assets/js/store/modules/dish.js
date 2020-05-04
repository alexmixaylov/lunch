export default {
    namespaced: true,
    state: {
        dishes: [1, 2, 3],
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
        async loadDishes({dispatch, commit}) {
            commit('setDishes', [1, 2])
        }
    },
}
