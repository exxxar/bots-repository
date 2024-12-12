import util from '../utilites';
import axios from "axios";

const BASE_FRIENDS_LINK = '/bot-client/friends-script'

let state = {}

const getters = {}

const actions = {
    async friendsLoadScriptVariants(context) {
        let link = `${ BASE_FRIENDS_LINK}/load-script-variants`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async friendsStoreConfig(context, payload ) {
        let link = `${BASE_FRIENDS_LINK}/store`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}
const mutations = {}

const friendsModule = {
    state,
    mutations,
    actions,
    getters
}
export default friendsModule;
