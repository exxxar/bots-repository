import util from '../utilites';
import axios from "axios";

const BASE_WHEEL_OF_FORTUNE_LINK = '/global-scripts/wheel-of-fortune'

let state = {}

const getters = {}

const actions = {
    async wheelOfFortunePrepare(context, payload = {prepareForm: null, bodDomain:null}) {
        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/prepare/${payload.bodDomain}`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.prepareForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortuneWin(context, payload = {winForm: null, bodDomain:null}) {
        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/${payload.bodDomain}`

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
