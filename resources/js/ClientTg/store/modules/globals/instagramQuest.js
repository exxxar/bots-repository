import util from '../utilites';
import axios from "axios";

const BASE_INSTAGRAM_QUEST_LINK = '/bot-client/nstagram-quest'

let state = {}

const getters = {}

const actions = {
    async instagramQuestLoadData(context) {

        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain
        }

        let link = `${BASE_INSTAGRAM_QUEST_LINK}/load-data`


        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async instagramQuestPrepare(context, payload = {prepareForm: null}) {

        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain
        }

        let link = `${BASE_INSTAGRAM_QUEST_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async instagramQuestResult(context, payload = {instaForm: null}) {
        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        payload.instaForm.append("tgData", tgData)
        payload.instaForm.append("slug_id", slugId)
        payload.instaForm.append("botDomain", botDomain)


        let link = `${BASE_INSTAGRAM_QUEST_LINK}/callback`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.instaForm)

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
