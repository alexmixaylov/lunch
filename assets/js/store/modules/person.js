import axios from 'axios';

export default {
    namespaced: true,
    state: {
        person: false,
        persons: []
    },
    getters: {
        getPersonName: state => state.personName,
        getPersonId: state => state.personId,
        getPerson: state => {
            return state.person
        },
        getPersons: state => {
            return state.persons
        }
    },
    mutations: {
        setPerson: (state, payload) => {
            state.person = payload
        },
        addPerson: (state, payload) => {
            state.persons.push(payload)
        },
        setPersons: (state, payload) => {
            state.persons = payload
        },
        updatePerson: (state, payload) => {
            state.persons[payload.index] = payload.person
        },
        deletePersonByIndex: (state, payload) => {
            console.log(payload)
            state.persons.splice(payload, 1)
        }
    },
    actions: {
        loadPersonById({commit}, payload) {
            axios.get('/persons/' + payload).then(response => {
                console.log(response)
                commit('setPerson', response.data)
            }).catch(e => console.log(e))
        },
        loadPersonByUserId({commit}, payload) {
            axios.get('/persons/user/' + payload).then(response => {
                console.log(response)
                commit('setPerson', response.data)
            }).catch(e => console.log(e))
        },
        loadPersonsByCompany({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    console.log('PERSON STORE loadPersonsByCompany', payload)
                    axios.get('/persons/company/' + payload).then(response => {
                        console.log(response)
                        if (response.status === 200) {
                            commit('setPersons', response.data)
                        }
                    }).catch(e => console.log(e))
                })
            );
        },
        linkPersonToUser({commit}, payload) {
            return new Promise(((resolve, reject) => {
                axios
                    .patch('/cabinet/person', payload)
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
        createPerson({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    axios.post('/persons/create', payload).then(response => {
                        console.log(response)
                        commit('addPerson', response.data)
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
                    resolve(response)
                }).catch(error => reject(error))
            }));
        }
    }
}
