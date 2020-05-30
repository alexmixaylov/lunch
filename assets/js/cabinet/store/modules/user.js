import axios from 'axios';

export default {
    namespaced: true,
    state: {
        user: {},
    },
    getters: {
        getUser: state => {
            return state.user
        },
    },
    mutations: {
        setUser: (state, payload) => {
            state.user = payload
        },
        setUserPerson: (state, payload) => {
            state.user['person'] = payload
        }
    },
    actions: {
    }
}
