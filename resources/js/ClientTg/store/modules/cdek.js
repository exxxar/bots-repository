import util from './utilites';
import axios from "axios";

const BASE_CDEK_LINK = '/bot-client/cdek'

let state = {}

const getters = {}

const actions = {


    async calcCdekTariffFromCart(context, payload) {
        let link = `${BASE_CDEK_LINK}/calc-basket-tariff`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCdekOffices(context, payload) {
        let link = `${BASE_CDEK_LINK}/get-offices`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCdekCities(context, payload) {
        let link = `${BASE_CDEK_LINK}/get-cities`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCdekRegions(context, payload) {
        let link = `${BASE_CDEK_LINK}/get-regions`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storeCdekOrder(context, payload) {
        let link = `${BASE_CDEK_LINK}/make-order`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.cdekForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async calcCdekTariff(context, payload) {
        let link = `${BASE_CDEK_LINK}/calc-tariff`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.cdekForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async checkCdekURL(context, payload) {
        let link = `${BASE_CDEK_LINK}/check`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.cdekForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeCdek(context, payload) {
        let link = `${BASE_CDEK_LINK}/store`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.cdekForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {}

const cdekModule = {
    state,
    mutations,
    actions,
    getters
}
export default cdekModule;
