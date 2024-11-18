import util from './utilites';
import axios from "axios";

const BASE_CASHBACK_LINK = '/bot-client/cashback'

let state = {
    cashback: [],
    cashback_paginate_object: null,
}

const getters = {
    getCashBack: state => state.cashback || [],
    getCashBackPaginateObject: state => state.cashback_paginate_object || null,
}

const actions = {
    async loadCashBackModuleData(context) {


        let link = `${BASE_CASHBACK_LINK}/load-data`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadReceiverUserData(context, payload = {dataObject: {user_telegram_chat_id: null}}) {


        let link = `${BASE_CASHBACK_LINK}/receiver`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            let dataObject = response.data
            return Promise.resolve(dataObject);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCashBack(context, payload = {dataObject: {user_telegram_chat_id: null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_CASHBACK_LINK}/history?page=${page}&size=${size}`

        let _axios = util.makeAxiosFactory(link, 'POST', {
            ...payload.dataObject
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setCashBack", dataObject.data)
            delete dataObject.data
            context.commit('setCashBackPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeCashBack(context, payload = {dataObject: null}) {
        let link = `${BASE_CASHBACK_LINK}/remove`

        let tgData = window.Telegram.WebApp.initData
        let botDomain = window.currentBot.bot_domain || null

        let _axios = util.makeAxiosFactory(link, 'POST', {
            tgData: tgData,
            botDomain: botDomain,
            ...payload.dataObject
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async addCashBack(context, payload = {dataObject: null}) {
        let link = `${BASE_CASHBACK_LINK}/add`


        let _axios = util.makeAxiosFactory(link, 'POST', {
            ...payload.dataObject
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async requestUserData(context, payload) {
        let link = `${BASE_CASHBACK_LINK}/request-user-data`

        let _axios = util.makeAxiosFactory(link, 'POST', {
            ...payload.dataObject
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async requestRefreshMenu(context, payload) {
        let link = `${BASE_CASHBACK_LINK}/request-refresh-menu`


        let _axios = util.makeAxiosFactory(link, 'POST', {

            ...payload.dataObject
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async userMessage(context, payload) {
        let link = `${BASE_CASHBACK_LINK}/user-message`


        let _axios = util.makeAxiosFactory(link, 'POST', {
            ...payload.dataObject
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveDeliveryman(context, payload) {
        let link = `${BASE_CASHBACK_LINK}/deliveryman`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveSimpleProfileFormData(context, payload) {
        let data = {
            ...payload
        }

        let link = `${BASE_CASHBACK_LINK}/profile-form-data`

        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveVip(context, payload) {


        let data = {
            ...payload
        }

        let link = `${BASE_CASHBACK_LINK}/vip`

        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {
    setCashBack(state, payload) {
        state.cashback = payload || [];
        localStorage.setItem('cashman_cashback', JSON.stringify(payload));
    },
    setCashBackPaginateObject(state, payload) {
        state.cashback_paginate_object = payload || [];
        localStorage.setItem('cashman_cashback_paginate_object', JSON.stringify(payload));
    }
}

const cashbackModule = {
    state,
    mutations,
    actions,
    getters
}
export default cashbackModule;
