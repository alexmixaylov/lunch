import axios from 'axios';

export default {
    namespaced: true,
    state: {
        company: {}
    },
    getters: {
        getCompany(state) {
            return state.company
        }
    },
    mutations: {

        setCompany: (state, payload) => {
            state.company = payload
        },
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
        loadCompanyByOwner({commit}, payload) {
            console.log(payload)
            axios.get('/cabinet/company/' + payload).then(response => {
                commit('setCompany', response.data)
                console.log('GET COMPANY',response.data)
            }).catch(e => console.log(e))
        },
        createCompany({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios
                    .patch('/cabinet/company', payload)
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
