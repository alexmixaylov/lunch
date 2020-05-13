import axios from 'axios';

export default {
    namespaced: true,
    state: {
        orders: []
    },
    getters: {
        getOrders: state => {
            return state.orders
        }
    },
    mutations: {
        addOrders(state, payload) {
            state.orders = payload
        }
    },
    actions: {
        loadOrdersByDate({commit}, payload) {
            console.log(payload)
            axios
                .get('/orders/date/' + payload)
                .then(response => {
                    console.log(response)
                    commit('addOrders', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        createOrder({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios.post(' /orders/', payload)
                    .then(response => {
                        resolve(response.data)
                        reject(response)
                    })
            }));
        }
    }
}
