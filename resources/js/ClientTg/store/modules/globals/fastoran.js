import util from './../utilites';

const BASE_FASTORAN_LINK = '/bot-client/fastoran'

let state = {
    fastoran: [],
    fastoran_paginate_object: null,
}

const getters = {
    getFastoran: state => state.fastoran || [],
    getFastoranPaginateObject: state => state.fastoran_paginate_object || null,
}

const actions = {

    async loadCurrentBotFields(context){
        let link = `${BASE_FASTORAN_LINK}/load-fields`
        let method = 'GET'

        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storeMessageSettings(context, payload = {dataObject: null}) {

        let link = `${BASE_FASTORAN_LINK}/store-message-settings`
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
    async storeBotFields(context, payload = {dataObject: null}) {

        let link = `${BASE_FASTORAN_LINK}/store-fields`
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
        let link = `${BASE_FASTORAN_LINK}/bot-lazy`

        let _axios = util.makeAxiosFactory(link,"POST", payload.botForm)

        return _axios.then((response) => {

            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    updateShopLink(context, payload = {botForm:null}){
        let link = `${BASE_FASTORAN_LINK}/update-shop-link`

        let _axios = util.makeAxiosFactory(link,"POST", payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async switchBotStatusManager(context, payload = {botId:null}) {
        let link = `${BASE_FASTORAN_LINK}/manager-switch-status`
        let _axios = util.makeAxiosFactory(link,"POST", payload)
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async switchBotStatus(context) {
        let link = `${BASE_FASTORAN_LINK}/switch-status`
        let _axios = util.makeAxiosFactory(link,"POST")
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeKeyboardTemplate(context, payload = {templateId: null}) {

        let link = `${BASE_FASTORAN_LINK}/remove-keyboard-template/${payload.templateId}`

        let _axios = util.makeAxiosFactory(link,"POST")

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createKeyboardTemplate(context, payload = {keyboardForm: null}) {
        let link = `${BASE_FASTORAN_LINK}/keyboard-template`

        let _axios = util.makeAxiosFactory(link,"POST", payload.keyboardForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async editKeyboardTemplate(context, payload = {keyboardForm: null}) {

        let link = `${BASE_FASTORAN_LINK}/edit-keyboard-template`

        let _axios = util.makeAxiosFactory(link,"POST", payload.keyboardForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadBotKeyboards(context) {


        let link = `${BASE_FASTORAN_LINK}/keyboards`

        let _axios = util.makeAxiosFactory(link,'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadSimpleFastoran(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_FASTORAN_LINK}/simple-bot-list?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setFastoran", dataObject.data)
            delete dataObject.data
            context.commit('setFastoranPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBotByManager(context, payload = {botForm: null}) {
        let link = `${BASE_FASTORAN_LINK}/manager-bot-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBotTheme(context, payload = {theme: null}) {
        let link = `${BASE_FASTORAN_LINK}/bot-theme`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBotMenuItems(context, payload = {iconForm: null}) {
        let link = `${BASE_FASTORAN_LINK}/bot-icons-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.iconForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBotParams(context, payload = {botForm: null}) {
        let link = `${BASE_FASTORAN_LINK}/bot-params-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateBot(context, payload = {botForm: null}) {
        let link = `${BASE_FASTORAN_LINK}/bot-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async syncAmo(context, payload = {dataObject: null}) {
        let link = `${BASE_FASTORAN_LINK}/sync-amo`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadAmoFields(context, payload = {dataObject: null}) {
        let link = `${BASE_FASTORAN_LINK}/load-amo-fields`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async saveYClients(context, payload = {yClientsForm: null}) {

        let link = `${BASE_FASTORAN_LINK}/save-y-clients`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.yClientsForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveAmoCRM(context, payload = {amoForm: null}) {

        let link = `${BASE_FASTORAN_LINK}/save-amo`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.amoForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async duplicateBot(context, payload = {dataObject:{bot_id:null, company_id: null}}) {
        let link = `${BASE_FASTORAN_LINK}/duplicate`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeBotByManager(context, payload = {botId: null}) {
        let link = `${BASE_FASTORAN_LINK}/remove-my-manager/${payload.botId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeBot(context, payload = {botId: null}) {
        let link = `${BASE_FASTORAN_LINK}/${payload.botId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async restoreBot(context, payload = {botId: null}) {
        let link = `${BASE_FASTORAN_LINK}/restore/${payload.botId}`

        let _axios = util.makeAxiosFactory(link, 'GET')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },



}
const mutations = {
    setFastoran(state, payload) {
        state.fastoran = payload || [];
        localStorage.setItem('cashman_fastoran', JSON.stringify(payload));
    },
    setFastoranPaginateObject(state, payload) {
        state.fastoran_paginate_object = payload || [];
        localStorage.setItem('cashman_fastoran_paginate_object', JSON.stringify(payload));
    }


}

const fastoranModule = {
    state,
    mutations,
    actions,
    getters
}
export default fastoranModule;
