import axios from 'axios';

export default {
    namespaced: true,
    state: {
        person: false,
        persons: []
    },
    getters: {
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
        setPersons: (state, payload) => {
            state.persons = payload
        }
    },
    actions: {
        loadPersonById({commit}, payload) {
            axios.get('/persons/' + payload).then(response => {
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
    }
}
