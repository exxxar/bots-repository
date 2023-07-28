import products from './products'
import util from "@/store/modules/utilites";

const BASE_SHOP_LINK = '/global-scripts/shop'
const BASE_CASHMAN_ADMIN_LINK = '/global-scripts/admin'

let state = {
    ...products.state
}

const getters = {
    ...products.getters
}

const actions = {
    ...products.actions,
    async testCallback(context) {
        let tgData = window.Telegram.WebApp.initData
        let botDomain = window.currentBot.bot_domain || null

        let link = `/test-auth`

        let _axios = util.makeAxiosFactory(link, 'POST', {
            tgData: tgData,
            botDomain: botDomain
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })

    },
    async cashmanAdminStatisticPrepare(context, payload = {telegram_chat_id: null}) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let link = `${BASE_CASHMAN_ADMIN_LINK}/load-statistic/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'POST', {
            telegram_chat_id: payload.telegram_chat_id
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async cashmanAdminLoadData(context) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let link = `${BASE_CASHMAN_ADMIN_LINK}/load-data/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'GET')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async cashmanAdminUserDataPrepare(context) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null
        let telegramChatId = window.self.telegram_chat_id || null

        let link = `${BASE_CASHMAN_ADMIN_LINK}/prepare/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'POST', {
            telegram_chat_id: telegramChatId
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async updateProductsFromVk(context, payload = {dataObject: {botDomain: null, url: null}}) {
        let link = `${BASE_SHOP_LINK}/vk-auth-link`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}

const mutations = {
    ...products.mutations
}

const shopAdminModule = {
    state,
    mutations,
    actions,
    getters
}
export default shopAdminModule;
