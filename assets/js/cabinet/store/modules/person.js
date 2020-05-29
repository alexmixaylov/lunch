import axios from 'axios';

export default {
    namespaced: true,
    state: {
        persons: []
    },
    getters: {
        getPersons: state => {
            return state.persons
        }
    },
    mutations: {
        setPersons: (state, payload) => {
            state.persons = payload
        }
    },
    actions: {
        loadPersonsByCompany({commit}, payload) {
            return new Promise(((resolve, reject) => {
                    axios.get('/persons/company/' + payload).then(response => {
                        console.log(response)
                        if (response.status === 200) {
                            commit('setPersons', response.data)
                        }
                    }).catch(e => console.log(e))
                })
            );
        }
    }
}
