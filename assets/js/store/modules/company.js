import axios from 'axios';

export default {
    namespaced: true,
    state: {
        company: {},
        companies: [],
        persons: []
    },
    getters: {
        getCompany: state => {
            return state.company
        },
        getCompanies: state => {
            return state.companies
        },
        getPersons: state => {
            return state.persons
        }
    },
    mutations: {
        setCompany(state, payload) {
            state.company = payload
        },
        setCompanies(state, payload) {
            state.companies = payload
        },
        addCompany(state, payload) {
            state.companies.push(payload)
        },
        setPersons: (state, payload) => {
            state.persons = payload
        },
        setPerson: (state, payload) => {
            state.persons.push(payload)
        },
        deletePersonByIndex: (state, payload) => {
            console.log(payload)
            state.persons.splice(payload, 1)
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
        loadCompanyByOwner({commit}, payload) {
            console.log(payload)
            axios.get('/cabinet/company/' + payload).then(response => {
                commit('setCompany', response.data)
                console.log('GET COMPANY',response.data)
            }).catch(e => console.log(e))
        },
        loadCompanies({commit}) {
            // console.log(payload)
            axios
                .get('/companies/')
                .then(response => {
                    console.log(response)
                    commit('setCompanies', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        loadCompanyById({commit}, payload) {
            // console.log(payload)
            axios
                .get('/companies/' + payload)
                .then(response => {
                    console.log(response)
                    commit('setCompany', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        createCompany({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios.post('/companies/', payload)
                    .then(response => {
                        console.log(response.data)
                        commit('addCompany', response.data)
                        resolve(response.data)
                    })
                    .catch(e => reject(e))
            }));
        },
        createCompanyCabinet({commit}, payload) {
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
        },
        deleteCompany({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    axios.delete('/companies/' + payload).then(response => {
                        console.log(response)
                        resolve(response.status)
                    }).catch(error => reject(error))
                })
            );
        },
        loadPersonsByCompany({commit}, payload) {
            // console.log(payload)
            return new Promise(((resolve, reject) => {
                axios
                    .get('/persons/company/' + payload)
                    .then(response => {
                        console.log(response)
                        commit('setPersons', response.data)
                        resolve(response.data)
                    })
                    .catch(error => {
                        console.log(error)
                        reject(error)
                    })
            }));
        },
        createPerson({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    axios.post('/persons/create', payload).then(response => {
                        console.log(response)
                        commit('setPerson', response.data)
                        resolve(response.status)
                    }).catch(error => {
                        console.log(error)
                        reject(error)
                    })
                })
            );
        },
        updatePerson({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    const personId = payload.person_id
                    axios.patch('/persons/' + personId, payload).then(response => {
                        console.log(response)
                        // commit('setPerson', response.data)
                        resolve(response.status)
                    })
                        .catch(error => reject(error))
                })
            );
        },
        deletePerson({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    axios.delete('/persons/' + payload).then(response => {
                        console.log(response)
                        resolve(response)
                    }).catch(error => reject(error))
                })
            );
        }
    }
}
