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
        getMenus: state => {
            return state.menus;
        },
        getMenu: state => {
            return state.menu;
        },
        getStatus: state => {
            return state.isClosed
        },
        getSelectedDate: state => {
            return state.selectedDate
        },

    },
    mutations: {
        addMenus(state, payload) {
            state.menus = payload
        },
        addMenu(state, payload) {
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

            console.log(payload)
            axios.get('/menus/date/' + payload)
                .then(response => {
                    if (response.status === 200) {
                        console.log(response.data)
                        commit('addMenu', response.data)
                    }
                })
                .catch(error => console.log(error))

        },
        async loadMenuById({commit}, payload) {
            try {
                await axios.get('/dishes/menu/' + payload)
                    .then(response => {
                        if (response.status === 200) {
                            console.log(response.data)
                            commit('addMenu', response.data)
                        }
                    })
                    .catch(error => console.log(error))
            } catch (e) {
                console.log(e)
            }
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
                        commit('addMenu', response.data)
                    })
            } catch (error) {
                console.log(error)
            }
        },
        async loadMenuTable({commit}, payload) {
            console.log(payload)
            try {
                await axios.get('/menus/table/' + payload)
                    .then(response => {
                        // console.log((response))
                        if (response.status === 200) {
                            const decodedData = JSON.parse(response.data);
                            const normalizedMenus = Object.keys(decodedData).map(key => {
                                return decodedData[key][key];
                            });
                            commit('addMenus', normalizedMenus)
                        }
                    })
                    .catch(errors => {
                        console.log(errors)
                    })
            } catch (e) {
                console.log(e)
            }

        },
        async addEmptyDishToMenu({commit, getters}) {
            const newDish = {price: null, title: null, weight: null, type: null};
            return new Promise(((resolve, reject) => {
                commit('addEmptyDish', newDish)
                const lastDishIndex = getters.getMenu.dishes.length - 1;
                resolve(lastDishIndex)
            }))
        },

    }
}
