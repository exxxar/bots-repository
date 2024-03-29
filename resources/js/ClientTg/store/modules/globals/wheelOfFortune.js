import util from '../utilites';
import axios from "axios";

const BASE_WHEEL_OF_FORTUNE_LINK = '/bot-client/wheel-of-fortune'

let state = {}

const getters = {}

const actions = {
    async wheelOfFortuneLoadData(context) {


        let link = `${ BASE_WHEEL_OF_FORTUNE_LINK}/load-data`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortunePrepare(context) {

        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async wheelOfFortuneWin(context, payload = {winForm: null}) {
        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/callback`

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
