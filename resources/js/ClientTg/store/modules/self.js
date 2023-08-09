import util from './utilites';
import axios from "axios";

const BASE_PRODUCTS_LINK = '/bot-client/self'

let state = {
    self: null
}

const getters = {
    getSelf: state => state.self || null,
}

const actions = {
    async loadSelf(context) {

        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain
        }

        let link = `${BASE_PRODUCTS_LINK}`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setSelf", dataObject.data)
            return Promise.resolve(dataObject);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}

const mutations = {
    setSelf(state, payload) {
        state.self = payload || [];
        localStorage.setItem('cashman_self', JSON.stringify(payload));
    },

}

const selfModule = {
    state,
    mutations,
    actions,
    getters
}
export default selfModule;
