import axios from 'axios';

export default {
    namespaced: true,
    state: {
        menus: [],
        isClosed: false
    },
    getters: {
        getMenus: state => {
            return state.menus;
        },
        // getMenu: state => id => {
        //     console.log(id)
        //     return state.menus[id + 'id'];
        // },
        getStatus: (state) => {
            return state.isClosed
        }
    },
    mutations: {
        addMenus(state, payload) {
            console.log('MUTATION ADD_MENU WORKING')
            state.menus = payload
        },

    },
    actions: {
        async loadMenuById(state, payload) {
            console.log('loadMenuById ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++', payload)
            axios.get('/menus/', payload)
                .then(response => console.log(response))
                .catch(error => console.log(error))
        },
        async getMenuByDate(state, payload) {
            try {
                let data = await axios.get('/menus/date/' + payload)
                    .then(response => {
                        console.log('response')
                        let data = response.data;
                        // if (Object.keys(data).length > 0) {
                        //     state.commit('addMenu', {
                        //         menu_id: response.data.menu_id.toString(),
                        //         dishes: response.data.dishes
                        //     })
                        // }

                    })
            } catch (error) {
                console.log(error)
            }
        },

        async loadMenuTable({commit}, payload) {
            console.log(payload)
            try {
                axios.get('/menus/table/' + payload)
                    .then(response => {
                        if (response.status === 200) {
                            commit('addMenus', response.data)
                        }
                        console.log(response)
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
