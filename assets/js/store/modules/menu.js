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
        }
    },
    mutations: {
        addMenus(state, payload) {
            state.menus = payload
        },
        addMenu(state, payload) {
            state.menu = payload
        },
        addIdToMenu: (state, payload)=> {
            console.log('try update MENU_ID',payload)
            state.menu.menu_id = payload
        },
        addEmptyDish(state, payload) {
            console.log(state)
            state.menu['dishes'].push(payload)
        },
        setSelectedDate(state, payload) {
            state.selectedDate = payload
        }

    },
    actions: {
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
        async loadMenuByDate({commit}, payload) {
            try {
                let data = await axios.get('/dishes/date/' + payload.date)
                    .then(response => {
                        console.log(payload)
                        let data = response.data;
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

        }
    },
}
