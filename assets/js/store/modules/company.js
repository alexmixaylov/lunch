import axios from 'axios';

export default {
    namespaced: true,
    state: {
        company: false,
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
        setPersons: (state, payload) => {
            state.persons = payload
        }
    },
    actions: {
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
                .get('/companies/'+ payload)
                .then(response => {
                    console.log(response)
                    commit('setCompany', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
        loadPersonsByCompany({commit}, payload) {
            // console.log(payload)
            axios
                .get('/persons/company/' + payload)
                .then(response => {
                    console.log(response)
                    commit('setPersons', response.data)
                })
                .catch(error => {
                    console.log(error)
                })
        },
    }
}
