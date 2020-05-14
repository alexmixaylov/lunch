import axios from 'axios';

export default {
    namespaced: true,
    state: {
        order: false,
        orders: []
    },
    getters: {
        getOrder: state => {
            return state.order
        },
        getOrders: state => {
            return state.orders
        }
    },
    mutations: {
        addOrders(state, payload) {
            state.orders = payload
        },
        addOrder(state, payload) {
            state.order = payload
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
        loadOrderById({commit}, payload) {
            console.log('ORDER ID:', payload)
            axios.get('/orders/' + payload).then(response => {
                console.log(response)
                commit('addOrder', response.data)
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
