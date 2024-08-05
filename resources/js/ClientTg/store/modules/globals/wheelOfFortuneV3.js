import util from '../utilites';
import axios from "axios";

const BASE_WHEEL_OF_FORTUNE_V3_LINK = '/bot-client/shop/wheel-of-fortune-v3'

let state = {}

const getters = {}

const actions = {
    async wheelOfFortuneV3Prepare(context) {
        let link = `${BASE_WHEEL_OF_FORTUNE_V3_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortuneV3Win(context, payload = {winForm: null}) {
        let link = `${BASE_WHEEL_OF_FORTUNE_V3_LINK}/callback`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.winForm)

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
