import util from '../utilites';
import axios from "axios";

const BASE_BONUS_PRODUCT_LINK = '/bot-client/bonus-product'

let state = {}

const getters = {}

const actions = {
    async bonusProductPrepare(context) {
        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain
        }

        let link = `${BASE_BONUS_PRODUCT_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async bonusProductCheck(context, payload = {dataObject:null}) {
        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
            ...payload.dataObject
        }

        let link = `${BASE_BONUS_PRODUCT_LINK}/check`

        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async bonusProductExchange(context, payload = {dataObject:null}) {
        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
            ...payload.dataObject
        }

        let link = `${BASE_BONUS_PRODUCT_LINK}/exchange`

        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadActionData(context, payload = {dataObject: null}) {
        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
            ...payload.dataObject
        }

        let link = `${BASE_BONUS_PRODUCT_LINK}/load-action-data`

        let _axios = util.makeAxiosFactory(link, 'POST', data)

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
