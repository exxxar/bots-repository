import util from './utilites';
import axios from "axios";

const BASE_ADMINS_LINK = '/bot-client/admins'

let state = {
    admins: [],
    admins_paginate_object: null,
}

const getters = {
    getAdmins: state => state.admins || [],
    getAdminsPaginateObject: state => state.admins_paginate_object || null,
}

const actions = {
    async loadAdmins(context, payload = {dataObject: null, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_ADMINS_LINK}?page=${page}&size=${size}`
        let method = 'POST'


        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setAdmins", dataObject.data)
            delete dataObject.data
            context.commit('setAdminsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async requestAdmin(context, payload = {dataObject: null}) {

        let link = `${BASE_ADMINS_LINK}/request`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async sendApproveToUser(context, payload) {
        let link = `${BASE_ADMINS_LINK}/send-approve`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async sendPageToUser(context, payload) {
        let link = `${BASE_ADMINS_LINK}/send-page-to-user`


        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async sendInvoice(context, payload) {
        let link = `${BASE_ADMINS_LINK}/send-invoice`


        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async addAdmin(context, payload) {
        let link = `${BASE_ADMINS_LINK}/add`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeAdmin(context, payload) {
        let link = `${BASE_ADMINS_LINK}/remove`


        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async selfRemove(context, payload) {

        let link = `${BASE_ADMINS_LINK}/self-remove`


        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async workStateChange(context, payload = {dataObject: null}) {

        let link = `${BASE_ADMINS_LINK}/work-status`


        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async statisticLoad(context) {

        let link = `${BASE_ADMINS_LINK}/load-statistic`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async downloadBotStatistic(context) {
        let _axios = util.makeAxiosFactory(`${BASE_ADMINS_LINK}/download-bot-statistic`, 'POST', null, {
            responseType: 'blob'
        })

        return _axios.then((response) => {
            return response
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async downloadBotUsers(context) {
        let _axios = util.makeAxiosFactory(`${BASE_ADMINS_LINK}/download-bot-users`, 'POST', null, {
            responseType: 'blob'
        })

        return _axios.then((response) => {
            return response
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async downloadCashBackHistory(context, payload = {orderBy: null, direction: null}) {
        let _axios = util.makeAxiosFactory(`${BASE_ADMINS_LINK}/download-cashback-history`, 'POST', payload, {
            responseType: 'blob'
        })

        return _axios.then((response) => {
            return response
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setAdmins(state, payload) {
        state.admins = payload || [];
        localStorage.setItem('cashman_admins', JSON.stringify(payload));
    },
    setAdminsPaginateObject(state, payload) {
        state.admins_paginate_object = payload || [];
        localStorage.setItem('cashman_admins_paginate_object', JSON.stringify(payload));
    }
}

const adminsModule = {
    state,
    mutations,
    actions,
    getters
}
export default adminsModule;
