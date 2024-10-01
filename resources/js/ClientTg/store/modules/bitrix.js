import util from './utilites';
import axios from "axios";

const BASE_BITRIX_LINK = '/bot-client/bitrix'

let state = {

}

const getters = {

}

const actions = {
    async loadBitrix(context, payload) {
        let link = `${ BASE_BITRIX_LINK}/load-connections`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeBitrix(context, payload) {
        let link = `${BASE_BITRIX_LINK}/store`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.bitrixForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {

}

const bitrixModule = {
    state,
    mutations,
    actions,
    getters
}
export default bitrixModule;
