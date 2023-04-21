import util from './utilites';
import axios from "axios";

const BASE_COMPANIES_LINK = '/bot/companies'

let state = {
    companies: [],
    companies_paginate_object: null,
}

const getters = {
    getCompanies: state => state.companies || [],
    getCompanyById: (state) => (id) => {
        return state.companies.find(item => item.id === id)
    },
    getCompaniesPaginateObject: state => state.companies_paginate_object || null,
}

const actions = {
    async loadCompanies(context, payload = {dataObject: null, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_COMPANIES_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setCompanies", dataObject.data)
            delete dataObject.data
            context.commit('setCompaniesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateCompany(context, payload= {companyForm: null}){
        let link = `${BASE_COMPANIES_LINK}/company-update`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.companyForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createCompany(context, payload = {companyForm: null}) {
        let link = `${BASE_COMPANIES_LINK}/company`

        let _axios = util.makeAxiosFactory(link,"POST", payload.companyForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setCompanies(state, payload) {
        state.companies = payload || [];
        localStorage.setItem('cashman_companies', JSON.stringify(payload));
    },
    setCompaniesPaginateObject(state, payload) {
        state.companies_paginate_object = payload || [];
        localStorage.setItem('cashman_companies_paginate_object', JSON.stringify(payload));
    }
}

const companiesModule = {
    state,
    mutations,
    actions,
    getters
}
export default companiesModule;
