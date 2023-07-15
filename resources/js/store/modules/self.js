import util from './utilites';
import axios from "axios";

const BASE_PRODUCTS_LINK = '/global-scripts/self'

let state = {
    self: null
}

const getters = {
    getSelf: state => state.self || null,
}

const actions = {
    async loadSelf(context, payload = {dataObject: { telegram_chat_id: null, bot_id:null}}) {

        let link = `${BASE_PRODUCTS_LINK}`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

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
