import util from './utilites';
import axios from "axios";

const BASE_IIKO_LINK = '/bot-client/iiko'

let state = {

}

const getters = {

}

const actions = {
    async getIikoToken(context, payload) {
        let link = `${ BASE_IIKO_LINK}/token`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async getIikoMenu(context) {
        let link = `${ BASE_IIKO_LINK}/menu`
        let _axios = util.makeAxiosFactory(link, 'POST')
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async getIikoProducts(context, payload) {
        let link = `${ BASE_IIKO_LINK}/products`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async getIikoOrganizations(context,payload) {
        let link = `${ BASE_IIKO_LINK}/organizations`
        let _axios = util.makeAxiosFactory(link, 'POST',payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async getIikoTerminals(context,payload) {
        let link = `${ BASE_IIKO_LINK}/terminals`
        let _axios = util.makeAxiosFactory(link, 'POST',payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storeIikoProducts(context, payload) {
        let link = `${BASE_IIKO_LINK}/store-products`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeIiko(context, payload) {
        let link = `${BASE_IIKO_LINK}/store`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.iikoForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {

}

const iikoModule = {
    state,
    mutations,
    actions,
    getters
}
export default iikoModule;
