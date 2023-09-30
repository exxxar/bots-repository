import util from './utilites';


const BASE_COMPANIES_LINK = '/bot-client/companies'

let state = {
    company: null,
    companies: [],
    locations: [],
    companies_paginate_object: null,
}

const getters = {
    getLocations: state => state.locations || [],
    getCompany: state => state.company || null,
    getCompanies: state => state.companies || [],
    getCompanyById: (state) => (id) => {
        return state.companies.find(item => item.id === id)
    },
    getCompaniesPaginateObject: state => state.companies_paginate_object || null,
}

const actions = {
    async removeManagerCompany(context, payload= {companyId: null}){
        let link = `${BASE_COMPANIES_LINK}/${payload.companyId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadManagersCompanies(context, payload = { page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50


        let data = {
            ...payload
        }
        let link = `${BASE_COMPANIES_LINK}/manager-companies-list?page=${page}&size=${size}`
        let method = 'POST'

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
    async loadCompanies(context, payload = { page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50


        let data = {
            ...payload
        }

        let link = `${BASE_COMPANIES_LINK}?page=${page}&size=${size}`
        let method = 'POST'

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
    async loadCompany(context) {


        let link = `${BASE_COMPANIES_LINK}/company`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setCompany", dataObject.data)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadLocationsByCompany(context) {


        let link = `${BASE_COMPANIES_LINK}/location-list`

        let _axios = util.makeAxiosFactory(link,"POST")

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setLocations", dataObject.data)
            delete dataObject.data
            //context.commit('setCompaniesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createLocation(context, payload = {locationForm: null}) {

        let link = `${BASE_COMPANIES_LINK}/location`

        let _axios = util.makeAxiosFactory(link,"POST",  payload.locationForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setCompany(state, payload) {
        state.company = payload || [];
        localStorage.setItem('cashman_current_company', JSON.stringify(payload));
    },
    setCompanies(state, payload) {
        state.companies = payload || [];
        localStorage.setItem('cashman_companies', JSON.stringify(payload));
    },
    setLocations(state, payload) {
        state.locations = payload || [];
        localStorage.setItem('cashman_locations', JSON.stringify(payload));
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
