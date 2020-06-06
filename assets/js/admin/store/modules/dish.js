import axios from 'axios';
import menu from "./menu";

export default {
    namespaced: true,
    state: {
        dishes: [],
        editingMode: false
    },
    getters: {
        getDishes: state => {
            return state.dishes;
        },
        getEditingMode: state => {
            return state.editingMode
        }
    },
    mutations: {
        setDishes(state, payload) {
            state.dishes = payload
        },
        loadDishesToMenu(state, payload) {
            menu.state.menu['dishes'].push(payload)
        },
        enableEditingMode: state => {
            state.editingMode = true
        }
    },
    actions: {
        createDish({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios.post('/dishes/', payload).then(response => {
                    resolve(response)
                }, error => {
                    reject(error)
                })
            }))
        },
        updateDish({commit}, payload) {
            return new Promise((resolve, reject) => {
                axios.patch('/dishes/' + payload.dish_id, payload).then(response => {
                    resolve(response)
                }, error => {
                    reject(error)
                })
            })
        },
        deleteDish({commit}, payload) {
            console.log(payload, 'DELETE DISH')
            return new Promise(((resolve, reject) => {
                return axios.delete('/dishes/' + payload).then(response => {
                    console.log(response)
                    resolve(response)
                }).catch(error => {
                    console.log(error)
                    reject(error)
                })
            }));

        },

    },
}
