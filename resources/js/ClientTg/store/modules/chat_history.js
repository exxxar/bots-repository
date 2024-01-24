import util from './utilites';
import axios from "axios";

const BASE_CHAT_HISTORY_LINK = '/bot-client/chat-history'

let state = {
    chat_history: [],
    chat_history_paginate_object: null,
}

const getters = {
    getChatHistory: state => state.chat_history || [],
    getChatHistoryPaginateObject: state => state.chat_history_paginate_object || null,
}

const actions = {

    async loadChatHistory(context, payload = { dataObject:{ bot_user_id:null } ,page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12



        let link = `${BASE_CHAT_HISTORY_LINK}?page=${page}&size=${size}`

        let _axios = util.makeAxiosFactory(link, 'POST', {
            ...payload.dataObject
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setChatHistory", dataObject.data)
            delete dataObject.data
            context.commit('setChatHistoryPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },




}
const mutations = {
    setChatHistory(state, payload) {
        state.chat_history = payload || [];
        localStorage.setItem('cashman_chat_history', JSON.stringify(payload));
    },
    setChatHistoryPaginateObject(state, payload) {
        state.chat_history_paginate_object = payload || [];
        localStorage.setItem('cashman_chat_history_paginate_object', JSON.stringify(payload));
    }
}

const chat_historyModule = {
    state,
    mutations,
    actions,
    getters
}
export default chat_historyModule;
