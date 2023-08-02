import util from '../utilites';
import axios from "axios";

const BASE_INSTAGRAM_QUEST_LINK = '/bot-client/{scriptId}/instagram-quest'

let state = {}

const getters = {}

const actions = {

    async instagramQuestLoadData(context) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let link = `${BASE_INSTAGRAM_QUEST_LINK}/load-data/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'GET')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async instagramQuestPrepare(context, payload = {prepareForm: null}) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null
        let telegramChatId = window.self.telegram_chat_id || null

        let link = `${BASE_INSTAGRAM_QUEST_LINK}/prepare/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'POST', {
            telegram_chat_id:telegramChatId
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async instagramQuestResult(context, payload = {instaForm: null}) {
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let link = `${BASE_INSTAGRAM_QUEST_LINK}/${botDomain}`
            .replace('{scriptId}', slugId)

        let telegramChatId = window.self.telegram_chat_id || null
        let instaForm = payload.instaForm

        instaForm.append("telegram_chat_id", telegramChatId)

        let _axios = util.makeAxiosFactory(link, 'POST',instaForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {}

const instagramQuestModule = {
    state,
    mutations,
    actions,
    getters
}
export default instagramQuestModule;
