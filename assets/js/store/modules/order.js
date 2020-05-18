import axios from 'axios';
import menuStore from './menu'

export default {
    namespaced: true,
    state: {
        order: {},
        orders: [],
        ordersWeek: []
    },
    getters: {
        getOrder: state => {
            return state.order
        },
        getOrders: state => {
            return state.orders
        },
        getOrdersWeek: state => {
            return state.ordersWeek
        }
    },
    mutations: {
        addOrders(state, payload) {
            state.orders = payload
        },
        addOrdersWeek(state, payload) {
            state.ordersWeek = payload
        },
        addOrder(state, payload) {
            state.order = payload
        },
        deleteOrder: state => {
            this.order = false
        },
        changeOrderStatus: (state, payload) => {
            if (state.order.hasOwnProperty('status')) {
                state.order.status = payload
            } else {
                console.log('проблема в сторе, нет такого order')
            }
        }
    },
    actions: {
        loadOrdersByDate({commit}, payload) {
            console.log(payload)
            new Promise(((resolve, reject) => {
                axios
                    .get('/orders/date/' + payload)
                    .then(response => {
                        commit('addOrders', response.data)
                        resolve(response.data)
                    })
            }));
        },
        loadOrdersByWeek({commit}, payload) {
            new Promise(((resolve, reject) => {
                axios
                    .get('/orders/week/' + payload)
                    .then(response => {
                        commit('addOrdersWeek', response.data)
                        resolve(response.data)
                    })
            }));
        },
        loadOrderById({commit}, payload) {
            console.log('ORDER ID:', payload)

            new Promise(((resolve, reject) => {
                resolve(
                    axios.get('/orders/' + payload).then(response => {
                        console.log(response)
                        commit('addOrder', response.data)
                        resolve(response.data)
                    }))
                reject(console.log(response))
            }));

        },
        createOrder({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios.post(' /orders/', payload)
                    .then(response => {
                        resolve(response.data)
                        reject(response)
                    })
            }));
        },
        editOrder({commit}, payload) {
            // payload must be ORDER ID
            axios.get('/orders/' + payload)
                .then(response => {
                    commit('addOrder', response.data)
                    return response.data
                })
                .then(response => {
                    axios.get('/menus/date/' + response.date)
                        .then(response => {
                            console.log('TEST -----------------------------')
                            if (response.status === 200) {
                                commit('menu/addMenu', response.data, {root: true})
                            }
                        }).catch(e => {
                        console.log(e)
                    })
                })
                .catch(e => {
                    console.log(e)
                })
        },
        updateOrder({commit}, payload) {
            return new Promise(((resolve, reject) => {
                console.log(payload)

                axios.patch(' /orders/' + payload.order_id, payload)
                    .then(response => {
                        resolve(response.data)
                        reject(response)
                    })
            }));
        },
        deleteOrder({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios.delete('/orders/' + payload).then(response => {
                    console.log(response)
                    if (response.status === 200) {
                        resolve(response.data);
                    }
                    reject(response)
                })
            }));
        },
        cancelOrder({commit}, payload) {
            new Promise(((resolve, reject) => {
                axios
                    .patch('/orders/' + payload + '/cancel')
                    .then(response => {
                        if (response.status === 200) {
                            commit('changeOrderStatus', response.data)
                            resolve(response.data)
                        }
                    })
            }))
        }
    }
}
