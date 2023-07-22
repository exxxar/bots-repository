import util from '../utilites';
import axios from "axios";

const BASE_WHEEL_OF_FORTUNE_LINK = '/global-scripts/{scriptId}/wheel-of-fortune'

let state = {}

const getters = {}

const actions = {
    async wheelOfFortuneLoadData(context) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let link = `${ BASE_WHEEL_OF_FORTUNE_LINK}/load-data/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'GET')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortunePrepare(context) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null
        let telegramChatId = window.self.telegram_chat_id || null

        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/prepare/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'POST', {
            telegram_chat_id: telegramChatId
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortuneWin(context, payload = {winForm: null}) {

        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null
        let telegramChatId = window.self.telegram_chat_id || null

        let winForm = payload.winForm

        winForm.append("telegram_chat_id", telegramChatId)

        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/${botDomain}`
            .replace('{scriptId}', slugId)

        let _axios = util.makeAxiosFactory(link, 'POST', winForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {}

const wheelOfFortuneModule = {
    state,
    mutations,
    actions,
    getters
}
export default wheelOfFortuneModule;
