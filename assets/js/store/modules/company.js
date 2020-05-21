import axios from 'axios';

export default {
    namespaced: true,
    state: {
        company: false,
        companies: [],
    },
    getters: {
        getCompany: state => {
            return state.company
        },
        getCompanies: state => {
            return state.companies
        },

    },
    mutations: {
        setCompany(state, payload) {
            state.company = payload
        },
        setCompanies(state, payload) {
            state.companies = payload
        },

    },
    actions: {
        // this module ONLY FOR READING aggregated data. ONLY LOAD DATA FROM SERVER
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
    }
}
