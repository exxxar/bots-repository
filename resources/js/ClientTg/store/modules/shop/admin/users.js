import util from '../../utilites';
import axios from "axios";

const BASE_USERS_LINK = '/bot-client/users'

let state = {
    users: [],
    users_paginate_object: null,
}

const getters = {
    getUsers: state => state.users || [],
    getUsersPaginateObject: state => state.users_paginate_object || null,
}

const actions = {

    async loadUsers(context, payload = { dataObject:{ search:null } ,page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let tgData = window.Telegram.WebApp.initData
        let botDomain = window.currentBot.bot_domain || null

        let link = `${BASE_USERS_LINK}/search?page=${page}&size=${size}`

        let _axios = util.makeAxiosFactory(link, 'POST', {
            tgData: tgData,
            botDomain: botDomain,
            ...payload.dataObject
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setUsers", dataObject.data)
            delete dataObject.data
            context.commit('setUsersPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeUsers(context, payload = {dataObject: null}) {
        let link = `${BASE_USERS_LINK}/remove`

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
    async addUsers(context, payload = {dataObject: null}) {
        let link = `${BASE_USERS_LINK}/add`

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
    setUsers(state, payload) {
        state.users = payload || [];
        localStorage.setItem('cashman_users', JSON.stringify(payload));
    },
    setUsersPaginateObject(state, payload) {
        state.users_paginate_object = payload || [];
        localStorage.setItem('cashman_users_paginate_object', JSON.stringify(payload));
    }
}

const usersModule = {
    state,
    mutations,
    actions,
    getters
}
export default usersModule;
