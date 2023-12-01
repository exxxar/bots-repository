import util from '../utilites';
import axios from "axios";

const BASE_SCHEDULE_LINK = '/bot-client/schedule'

let state = {}

const getters = {}

const actions = {
    async scheduleLoadData(context) {
        let link = `${BASE_SCHEDULE_LINK}/load-data`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {}

const scheduleModule = {
    state,
    mutations,
    actions,
    getters
}
export default scheduleModule;
