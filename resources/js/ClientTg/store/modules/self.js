import util from './utilites';
import axios from "axios";

const BASE_SELF_LINK = '/bot-client'

let state = {
    self: null,
}

const getters = {
    getSelf: state => state.self || null,
}

const actions = {
    async loadSelf(context) {

        let link = `${BASE_SELF_LINK}/self`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setSelf", dataObject.data)
            return Promise.resolve(dataObject);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadManagerData(context) {


        let link = `${BASE_SELF_LINK}/manager/load-data`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadFriendsWeb(context) {


        let link = `${BASE_SELF_LINK}/manager/friends-web`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveManager(context, payload) {

        let data = {
            ...payload
        }

        let link = `${BASE_SELF_LINK}/manager/register`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBotManagerConfig(context, payload = {botId: null}) {

console.log("payload", payload)
        let link = `${BASE_SELF_LINK}/manage-bot`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method, payload)

        return _axios.then((response) => {
            let dataObject = response.data
            return Promise.resolve(dataObject);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBotAdminConfig(context) {


        let link = `${BASE_SELF_LINK}/bot`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            let dataObject = response.data
            return Promise.resolve(dataObject);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}

const mutations = {
    setSelf(state, payload) {
        state.self = payload || [];
        localStorage.setItem('cashman_self', JSON.stringify(payload));
    },

}

const selfModule = {
    state,
    mutations,
    actions,
    getters
}
export default selfModule;
