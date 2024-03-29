import util from '../utilites';
import axios from "axios";

const BASE_BONUS_PRODUCT_LINK = '/bot-client/bonus-product'

let state = {}

const getters = {}

const actions = {
    async bonusProductPrepare(context) {


        let link = `${BASE_BONUS_PRODUCT_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async bonusProductCheck(context, payload = {dataObject:null}) {

        let link = `${BASE_BONUS_PRODUCT_LINK}/check`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async bonusProductExchange(context, payload = {dataObject:null}) {

        let link = `${BASE_BONUS_PRODUCT_LINK}/exchange`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadActionData(context, payload = {dataObject: null}) {
        let link = `${BASE_BONUS_PRODUCT_LINK}/load-action-data`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {}

const bonusProductModule = {
    state,
    mutations,
    actions,
    getters
}
export default bonusProductModule;
