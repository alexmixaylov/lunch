import axios from 'axios';

export default {
    namespaced: true,
    state: {
        menu: null,
        menus: []
    },
    getters: {
        getMenu: state => {
            return state.menu
        },
        getMenus: state => {
            return state.menus
        }
    },
    mutations: {
        setMenu: (state, payload) => {
            state.menu = payload
        },
        setMenus: (state, payload) => {
            state.menus = payload
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
        }
    }
}
