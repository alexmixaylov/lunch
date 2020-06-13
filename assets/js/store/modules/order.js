import axios from 'axios';

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
        setOrders(state, payload) {
            state.orders = payload
        },
        setOrdersWeek(state, payload) {
            state.ordersWeek = payload
        },
        setOrder(state, payload) {
            state.order = payload
        },
        deleteOrder: state => {
            this.order = false
        },
        setOrderStatus: (state, payload) => {
            if (state.order.hasOwnProperty('status')) {
                state.order.status = payload
            } else {
                console.log('в сторе на данный момент, нет такого order, ORDER.JS 39 строка модуль стора')
            }
        }
    },
    actions: {
        loadOrdersByParams({commit}, params) {
            console.log(params)
            new Promise(((resolve, reject) => {
                axios
                    .get('/orders/gate?' + params)
                    .then(response => {
                        commit('setOrders', response.data)
                        resolve(response.data)
                    })
                    .catch(e => reject(e))
            }));
        },
        loadOrdersByDate({commit}, payload) {
            console.log(payload)
            new Promise(((resolve, reject) => {
                axios
                    .get('/orders/date/' + payload)
                    .then(response => {
                        console.log(response.data)
                        commit('setOrders', response.data)
                        resolve(response.data)
                    })
            }));
        },
        loadOrdersByWeek({commit}, payload) {
            new Promise(((resolve, reject) => {
                axios
                    .get('/orders/week/' + payload)
                    .then(response => {
                        commit('setOrdersWeek', response.data)
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
                        commit('setOrder', response.data)
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
        changeOrderStatus({commit}, payload) {
            const orderId = payload.order_id
            const status = payload.status
            return new Promise(((resolve, reject) => {
                axios
                    .patch(`/orders/${orderId}/status`, status)
                    .then(response => {
                        if (response.status === 200) {
                            // если комитить здесь - то возникает проблема когда этот же order используется в delivery
                            // commit('setOrderStatus', response.data)
                            resolve(response.data)
                        }
                        reject(response)
                    })
            }))
        }
    }
}
