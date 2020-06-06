import axios from 'axios';

export default {
    namespaced: true,
    state: {
        dishes: [],
        allOrdersByDay: [],
        ordersByCompany: []
    },
    getters: {
        getDishes: state => {
            return state.dishes
        },
        getAllOrdersByDay: state => {
            return state.allOrdersByDay
        },
        getOrdersByCompany: state => {
            return state.ordersByCompany
        }
    },
    mutations: {
        setDishes: (state, payload) => {
            state.dishes = payload
        },
        setAllOrdersByDay(state, payload) {
            state.allOrdersByDay = payload
        },
        setOrdersByCompany(state, payload) {
            state.ordersByCompany = payload
        }
    },
    actions: {
        loadDishesByOrder({commit}, payload) {
            const orderId = payload;
            return new Promise(((resolve, reject) => {
                axios
                    .get('/delivery/dishes/' + orderId)
                    .then(response => {
                        console.log(response)
                        if (response.status === 200) {
                            commit('setDishes', response.data)
                            resolve(response.data)
                        }
                        reject(e => {
                            console.log(e)
                        })
                    })
            }));
        },
        loadAllOrdersByDay({commit}, date) {
            axios
                .get('/delivery/orders/' + date)
                .then(response => {
                    console.log(response)
                    if (response.status === 200) {
                        commit('setAllOrdersByDay', response.data)
                    }
                })
        },
        loadOrdersByCompanyAndDate({commit}, payload) {
            const companyId = payload.company_id
            const date = payload.date
            console.log(payload)
            axios
                .get(`/delivery/company/${companyId}/${date}`)
                .then(response => {
                    console.log(response)
                    if (response.status === 200) {
                        commit('setOrdersByCompany', response.data)
                    }
                })
        },
    }
}
