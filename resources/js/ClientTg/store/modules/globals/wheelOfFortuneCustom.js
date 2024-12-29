import util from '../utilites';
import axios from "axios";

const BASE_WHEEL_OF_FORTUNE_CUSTOM_LINK = '/bot-client/wheel-of-fortune-custom'

let state = {}

const getters = {}

const actions = {

    async getClientNotUsedPrizesFromWheelOfFortune(context, payload) {
        let link = `${ BASE_WHEEL_OF_FORTUNE_CUSTOM_LINK}/load-prizes-variants`

        let _axios = util.makeAxiosFactory(link, 'POST',payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async wheelOfFortuneLoadScriptVariants(context) {
        let link = `${ BASE_WHEEL_OF_FORTUNE_CUSTOM_LINK}/load-script-variants`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async updateWheelCustomScriptParams(context, payload = {slugForm: null}) {
        let link = `${BASE_WHEEL_OF_FORTUNE_CUSTOM_LINK}/store-params`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.slugForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortuneCustomLoadData(context) {
        let link = `${ BASE_WHEEL_OF_FORTUNE_CUSTOM_LINK}/load-data`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortuneCustomPrepare(context) {

        let link = `${BASE_WHEEL_OF_FORTUNE_CUSTOM_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortuneCustomWin(context, payload = {winForm: null}) {
        let link = `${BASE_WHEEL_OF_FORTUNE_CUSTOM_LINK}/callback`

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
