import axios from 'axios';

export default {
    namespaced: true,
    state: {
        menus: [],
        menu: {},
        selectedDate: new Date(),
        isClosed: false
    },
    getters: {
        getMenu: state => {
            return state.menu;
        },
        getMenus: state => {
            return state.menus;
        },
        getStatus: state => {
            return state.isClosed
        },
        getSelectedDate: state => {
            return state.selectedDate
        },

    },
    mutations: {
        setMenus(state, payload) {
            state.menus = payload
        },
        setMenu(state, payload) {
            state.menu = payload
        },
        addIdToMenu: (state, payload) => {
            console.log('try update MENU_ID', payload)
            state.menu.menu_id = payload
        },
        addEmptyDish(state, payload) {
            console.log(state)
            state.menu.dishes.push(payload)
        },
        setSelectedDate(state, payload) {
            state.selectedDate = payload
        }

    },
    actions: {
        loadMenuByDate({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    axios.get('/menus/date/' + payload).then(response => {
                        console.log(response)
                        if (response.status === 200) {
                            commit('setMenu', response.data)
                        }
                    }).catch(e => console.log(e))
                })
            );
        },
        loadMenuById({commit}, payload) {
            axios.get('/dishes/menu/' + payload)
                .then(response => {
                    if (response.status === 200) {
                        console.log(response.data)
                        commit('setMenu', response.data)
                    }
                })
                .catch(error => console.log(error))
        },
        getMenuIdByDate({}, payload) {
            console.log(payload)
            return new Promise(((resolve, reject) => {
                axios.get('/menus/id/' + payload).then(
                    //TODO сделать нормальную обработку ошибок, сейчас прилетает 500 а нужно 404
                    response => {
                        console.log(response)
                        if (response.status === 200) {
                            resolve(response.data)
                        }
                        reject(response)
                    })
                    .catch(error => {
                            console.log(error)
                        }
                    )
            }));
        },
        async loadDishesIntoMenuByDate({commit}, payload) {
            try {
                let data = await axios.get('/dishes/date/' + payload.date)
                    .then(response => {
                        console.log('payload DATE ', payload.date)
                        let data = response.data;
                        console.log('response DATE ', response.data)
                        commit('setMenu', response.data)
                    })
            } catch (error) {
                console.log(error)
            }
        },
        loadMenuTable({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios.get('/menus/table/' + payload)
                    .then(response => {
                        console.log(response.data)

                        if (response.status === 200) {
                            const decodedData = JSON.parse(response.data);
                            const normalizedMenus = Object.keys(decodedData).map(key => {
                                return decodedData[key][key];
                            });
                            console.log(normalizedMenus)
                            console.log(response.data)
                            commit('setMenus', normalizedMenus)
                            resolve(response.data)
                        }
                    })
                    .catch(e => {
                        reject(e)
                    })
            }));
        },
        addEmptyDishToMenu({commit, getters}) {
            const newDish = {price: null, title: null, weight: null, type: null};
            return new Promise(((resolve, reject) => {
                commit('addEmptyDish', newDish)
                const lastDishIndex = getters.getMenu.dishes.length - 1;
                resolve(lastDishIndex)
            }))
        },

    }
}
