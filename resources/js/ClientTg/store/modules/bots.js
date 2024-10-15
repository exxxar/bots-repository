import util from './utilites';

const BASE_BOTS_LINK = '/bot-client/bots'

let state = {
    bots: [],
    bots_paginate_object: null,
}

const getters = {
    getBots: state => state.bots || [],
    getBotsPaginateObject: state => state.bots_paginate_object || null,
}

const actions = {

    async loadCurrentBotFields(context){
        let link = `${BASE_BOTS_LINK}/load-fields`
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
    async createBotLazy(context, payload = {botForm: null}) {
        let link = `${BASE_BOTS_LINK}/bot-lazy`

        let _axios = util.makeAxiosFactory(link,"POST", payload.botForm)

        return _axios.then((response) => {
            console.log("response from state=>", response)
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    updateShopLink(context, payload = {botForm:null}){
        let link = `${BASE_BOTS_LINK}/update-shop-link`

        let _axios = util.makeAxiosFactory(link,"POST", payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async switchBotStatusManager(context, payload = {botId:null}) {
        let link = `${BASE_BOTS_LINK}/manager-switch-status`
        let _axios = util.makeAxiosFactory(link,"POST", payload)
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async switchBotStatus(context) {
        let link = `${BASE_BOTS_LINK}/switch-status`
        let _axios = util.makeAxiosFactory(link,"POST")
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeKeyboardTemplate(context, payload = {templateId: null}) {

        let link = `${BASE_BOTS_LINK}/remove-keyboard-template/${payload.templateId}`

        let _axios = util.makeAxiosFactory(link,"POST")

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createKeyboardTemplate(context, payload = {keyboardForm: null}) {
        let link = `${BASE_BOTS_LINK}/keyboard-template`

        let _axios = util.makeAxiosFactory(link,"POST", payload.keyboardForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async editKeyboardTemplate(context, payload = {keyboardForm: null}) {

        let link = `${BASE_BOTS_LINK}/edit-keyboard-template`

        let _axios = util.makeAxiosFactory(link,"POST", payload.keyboardForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBotKeyboards(context) {


        let link = `${BASE_BOTS_LINK}/keyboards`

        let _axios = util.makeAxiosFactory(link,'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadSimpleBots(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_BOTS_LINK}/simple-bot-list?page=${page}&size=${size}`
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
    async updateBotByManager(context, payload = {botForm: null}) {
        let link = `${BASE_BOTS_LINK}/manager-bot-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async updateBotParams(context, payload = {botForm: null}) {
        let link = `${BASE_BOTS_LINK}/bot-params-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
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
    async syncAmo(context, payload = {dataObject: null}) {
        let link = `${BASE_BOTS_LINK}/sync-amo`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadAmoFields(context, payload = {dataObject: null}) {
        let link = `${BASE_BOTS_LINK}/load-amo-fields`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

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

    async duplicateBot(context, payload = {dataObject:{bot_id:null, company_id: null}}) {
        let link = `${BASE_BOTS_LINK}/duplicate`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeBotByManager(context, payload = {botId: null}) {
        let link = `${BASE_BOTS_LINK}/remove-my-manager/${payload.botId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

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

   /* async loadBotUsers(context, payload = {dataObject: {botId: null, search: null}, page: 0, size: 12}) {

        let page = payload.page || 0
        let size = 12

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
    },*/
    async loadImageMenus(context) {


        let link = `${BASE_BOTS_LINK}/image-menu`

        let _axios = util.makeAxiosFactory(link,"POST")

        return _axios.then((response) => {
            return Promise.resolve(response.data.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBotSlugs(context, payload = { isGlobal:false}) {


        let data = {
            is_global: payload.isGlobal || false
        }

        let link = `${BASE_BOTS_LINK}/slugs`

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
    setBots(state, payload) {
        state.bots = payload || [];
        localStorage.setItem('cashman_bots', JSON.stringify(payload));
    },
    setBotsPaginateObject(state, payload) {
        state.bots_paginate_object = payload || [];
        localStorage.setItem('cashman_bots_paginate_object', JSON.stringify(payload));
    }
   /* setBotUsers(state, payload) {
        state.bot_users = payload || [];
        localStorage.setItem('cashman_bot_users', JSON.stringify(payload));
    },
    setBotUsersPaginateObject(state, payload) {
        state.bot_users_paginate_object = payload || [];
        localStorage.setItem('cashman_bot_users_paginate_object', JSON.stringify(payload));
    },*/

}

const botsModule = {
    state,
    mutations,
    actions,
    getters
}
export default botsModule;
