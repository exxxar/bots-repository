import util from './utilites';

const BASE_DIALOGS_LINK = '/bot-client/dialogs'

let state = {
    dialogs: [],
    dialogs_paginate_object: null,
}

const getters = {
    getDialogs: state => state.dialogs || [],
    getDialogsPaginateObject: state => state.dialogs_paginate_object || null,
}

const actions = {
    async loadDialogs(context, payload = {dataObject: {search:null}, page: 0, size: 12}) {
        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = 12

        let link = `${BASE_DIALOGS_LINK}?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setDialogs", dataObject.data)
            delete dataObject.data
            context.commit('setDialogsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}
const mutations = {
    setDialogs(state, payload) {
        state.dialogs = payload || [];
        localStorage.setItem('cashman_dialogs', JSON.stringify(payload));
    },
    setDialogsPaginateObject(state, payload) {
        state.dialogs_paginate_object = payload || [];
        localStorage.setItem('cashman_dialogs_paginate_object', JSON.stringify(payload));
    }
}

const dialogGroupsModule = {
    state,
    mutations,
    actions,
    getters
}
export default dialogGroupsModule;
