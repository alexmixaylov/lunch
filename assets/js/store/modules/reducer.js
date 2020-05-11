import axios from 'axios';

export default {
    namespaced: true,
    state: {
        orders: [],
        dishes:[],
        clients:[]
    },
    getters: {
        getOrders: state =>{
            return state.orders
        },
        getDishes: state =>{
            return state.dishes
        },
    },
    mutations: {
        addOrders(state, payload){
            state.orders = payload
        },
        addDishes(state, payload){
            state.dishes = payload
        }
    },
    actions: {
        // this module ONLY FOR READING aggregated data. ONLY LOAD DATA FROM SERVER
        loadOrdersByDate({commit}, payload){
            console.log(payload)
            axios
                .get('/orders/date/'+ payload)
                .then(response => {
                    console.log(response)
                    commit('addOrders', response.data)
                })
                .catch(error => {console.log(error)})
        },
        loadDishesByDate({commit}, payload){
            console.log(payload)
            axios
                .get('/stats/dishes/'+ payload)
                .then(response => {
                    console.log(response)
                    commit('addDishes', response.data)
                })
                .catch(error => {console.log(error)})
        }
    }
}
