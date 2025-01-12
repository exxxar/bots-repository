import util from './utilites';

const BASE_BOT_USERS_LINK = '/bot-client/bot-users'

let state = {
    bot_users: [],
    bot_users_paginate_object: null,
}

const getters = {
    getBotUsers: state => state.bot_users || [],
    getBotUserById: (state) => (id) => {
        return state.bot_users.find(item => item.id === id)
    },
    getBotUsersPaginateObject: state => state.bot_users_paginate_object || null,
}

const actions = {

    async getUserProfilePhotos(context, payload = null){
        let link = `${BASE_BOT_USERS_LINK}/get-user-profile-photos`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async loadBotUsers(context, payload = {dataObject: { search:null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_BOT_USERS_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBotUsers", dataObject.data)
            delete dataObject.data
            context.commit('setBotUsersPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async updateProfile(context, payload= {botUserForm: null}){
        let link = `${BASE_BOT_USERS_LINK}/update-profile`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.botUserForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBotUser(context, payload= {botUserForm: null}){
        let link = `${BASE_BOT_USERS_LINK}/update-bot-user`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.botUserForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async removeBotUser(context, payload= {dataObject: {dialogGroupId: null}}){
        let link = `${BASE_BOT_USERS_LINK}/remove-group/${payload.dataObject.dialogGroupId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
     async createBotUser(context, payload = {dialogGroupForm: null}) {
        let link = `${BASE_BOT_USERS_LINK}/add-group`

        let _axios = util.makeAxiosFactory(link,"POST", payload.dialogGroupForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {
    setBotUsers(state, payload) {
        state.bot_users = payload || [];
        localStorage.setItem('cashman_bot_users', JSON.stringify(payload));
    },
    setBotUsersPaginateObject(state, payload) {
        state.bot_users_paginate_object = payload || [];
        localStorage.setItem('cashman_bot_users_paginate_object', JSON.stringify(payload));
    }
}

const dialogGroupsModule = {
    state,
    mutations,
    actions,
    getters
}
export default dialogGroupsModule;
