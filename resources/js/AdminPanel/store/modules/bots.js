import util from './utilites';

const BASE_BOTS_LINK = '/admin/bots'

let state = {
    bots: [],
    bot_users: [],
    bots_paginate_object: null,
    bot_users_paginate_object: null,
}

const getters = {
    getBots: state => state.bots || [],
    getBotUsers: state => state.bot_users || [],
    getBotById: (state) => (id) => {
        return state.bots.find(item => item.id === id)
    },
    getBotsPaginateObject: state => state.bots_paginate_object || null,
    getBotUsersPaginateObject: state => state.bot_users_paginate_object || null,
}

const actions = {
    async saveYClients(context, payload = {yClientsForm: null}) {

        let link = `${BASE_BOTS_LINK}/save-y-clients`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.yClientsForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async getMe(context, payload = {bot_token: null}) {
        let link = `${BASE_BOTS_LINK}/get-me`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, {
            bot_token: payload.bot_token
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCurrentBotFields(context, payload = {bot_id: null}) {
        let link = `${BASE_BOTS_LINK}/load-fields/${payload.bot_id}`
        let method = 'GET'

        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeBotFields(context, payload = {dataObject: null}) {

        let link = `${BASE_BOTS_LINK}/store-fields`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async loadChatInfo(context, payload = {dataObject: {chat_id: null, bot_id:null}}) {
        let link = `${BASE_BOTS_LINK}/load-chat-info`

        let _axios = util.makeAxiosFactory(link, "POST", payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createBotTopics(context, payload = {dataObject: {bot_id:null, topics: null}}) {
        let link = `${BASE_BOTS_LINK}/create-bot-topics`

        let _axios = util.makeAxiosFactory(link, "POST", payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateShopLink(context, payload = {botForm: null}) {
        let link = `${BASE_BOTS_LINK}/update-shop-link`

        let _axios = util.makeAxiosFactory(link, "POST", payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBots(context, payload = {dataObject: null, page: 0, size: 50}) {

        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_BOTS_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBots", dataObject.data)
            delete dataObject.data
            context.commit('setBotsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBotsByIds(context, payload = {ids:null}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_BOTS_LINK}/ids`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload)

        return _axios.then((response) => {
            let dataObject = response.data
            return Promise.resolve(dataObject.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBot(context, payload = {botForm: null}) {
        let link = `${BASE_BOTS_LINK}/bot-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBotWebhook(context, payload = {dataObject: {bot_id:null}}) {
        let link = `${BASE_BOTS_LINK}/bot-webhook-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async testConnectionAmoCRM(context, payload = {dataObject: null}) {
        let link = `${BASE_BOTS_LINK}/test-amo`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveAmoCRM(context, payload = {amoForm: null}) {
        let link = `${BASE_BOTS_LINK}/save-amo`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.amoForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async saveFrontPad(context, payload = {frontPadForm: null}) {
        let link = `${BASE_BOTS_LINK}/save-front-pad`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.frontPadForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async duplicateBot(context, payload = {dataObject: {bot_id: null, company_id: null}}) {
        let link = `${BASE_BOTS_LINK}/duplicate`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeBot(context, payload = {botId: null}) {
        let link = `${BASE_BOTS_LINK}/${payload.botId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async forceDeleteBot(context, payload = {botId: null}) {
        let link = `${BASE_BOTS_LINK}/force/${payload.botId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async restoreBot(context, payload = {botId: null}) {
        let link = `${BASE_BOTS_LINK}/restore/${payload.botId}`

        let _axios = util.makeAxiosFactory(link, 'GET')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async changeUserStatus(context, payload = {dataObject: {botUserId: null, status: 0}}) {
        let link = `${BASE_BOTS_LINK}/user-status`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBotUsers(context, payload = {dataObject: {botId: null, search: null}, page: 0, size: 100}) {

        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_BOTS_LINK}/users?page=${page}&size=${size}`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

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


}
const mutations = {
    setBots(state, payload) {
        state.bots = payload || [];
       // localStorage.setItem('cashman_bots', JSON.stringify(payload));
    },
    setBotUsers(state, payload) {
        state.bot_users = payload || [];
       // localStorage.setItem('cashman_bot_users', JSON.stringify(payload));
    },
    setBotUsersPaginateObject(state, payload) {
        state.bot_users_paginate_object = payload || [];
     //   localStorage.setItem('cashman_bot_users_paginate_object', JSON.stringify(payload));
    },
    setBotsPaginateObject(state, payload) {
        state.bots_paginate_object = payload || [];
      //  localStorage.setItem('cashman_bots_paginate_object', JSON.stringify(payload));
    }
}

const botsModule = {
    state,
    mutations,
    actions,
    getters
}
export default botsModule;
