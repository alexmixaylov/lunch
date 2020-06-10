import axios from 'axios';

export default {
    namespaced: true,
    state: {
        user: {}
    },
    getters: {
        getUser: state => {
            return state.user
        }
    },
    mutations: {
        setUser: (state, payload) => {
            console.log('USER MUTATION', payload)
            state.user = payload
        },
        setUserPerson: (state, payload) => {
            state.user['person'] = payload
        }
    },
    actions: {
        searchCompanyByUUID({commit}, payload) {
            return new Promise((resolve, reject) => {
                axios
                    .get('/companies/uuid/' + payload)
                    .then(response => {
                        console.log(response.data)
                        const data = response.data.company ? response.data : null
                        resolve(data)
                    })
                    .catch(e => reject(e))
            });
        },
        linkPersonToUser({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios
                    .patch('/cabinet/link', payload)
                    .then(response => {
                        if (response.status === 200) {
                            console.log(response.data)
                            // commit('setUserPerson', response.data)
                        }
                        resolve(response.data)

                    })
                    .catch(e => reject(e))
            }));
        }
    }
}
