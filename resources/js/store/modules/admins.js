import util from './utilites';
import axios from "axios";

const BASE_ADMINS_LINK = '/bot/admins'

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
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

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
    async requestAdmin(context, payload = {dataObject: null}){
        let link = `${BASE_ADMINS_LINK}/request`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async sendApproveToUser(context, payload){
        let link = `${BASE_ADMINS_LINK}/send-approve`

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
    async addAdmin(context, payload){
        let link = `${BASE_ADMINS_LINK}/add`

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
    async removeAdmin(context, payload){
        let link = `${BASE_ADMINS_LINK}/remove`

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
    async selfRemove(context, payload) {

        let link = `${BASE_ADMINS_LINK}/self-remove`

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
    async workStateChange(context, payload = {
        dataObject: null
    }) {

        let link = `${BASE_ADMINS_LINK}/work-status`

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
