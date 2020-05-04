export default {
    namespaced: true,
    state: {
        menus: ['monday', 'wednesday'],
        isClosed: false
    },
    getters: {
        getMenus: state => {
            return state.menus;
        },
        getStatus: (state) => {
            return state.isClosed
        }
    },
    mutations: {
        setMenus(state, payload) {
            state.menus = payload
        }
    },
    actions: {

    },
}
