import util from './utilites';

const BASE_MESSAGES_LINK = '/admin/system-messages'

let state = {
    messages: [],
    message_dictionary: [],

}

const getters = {
    getMessages: state => state.messages || [],
    getMessageDictionary: state => state.message_dictionary || [],


}

const actions = {

    async loadMessages(context, payload = {
        dataObject: {botId: null, search: null},
        page: 0,
        size: 12
    }) {
        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_MESSAGES_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data

            context.commit("setMessages", dataObject.messages)
            context.commit("setMessageDictionary", dataObject.dictionary)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateMessage(context, payload = {messageForm: null}) {
        let link = `${BASE_MESSAGES_LINK}/message-update`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.messageForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}
const mutations = {
    setMessages(state, payload) {
        state.messages = payload || [];
        localStorage.setItem('cashman_messages', JSON.stringify(payload));
    },
    setMessageDictionary(state, payload) {
        state.message_dictionary = payload || [];
        localStorage.setItem('cashman_message_dictionary', JSON.stringify(payload));
    },
}

const messagesModule = {
    state,
    mutations,
    actions,
    getters
}
export default messagesModule;
