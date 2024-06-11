import util from '../utilites';
import axios from "axios";

const BASE_WHEEL_OF_FORTUNE_LINK = '/bot-client/friends-game'

let state = {}

const getters = {}

const actions = {
    async friendsGamePrepare(context) {


        let link = `${ BASE_WHEEL_OF_FORTUNE_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async friendsGameStart(context, payload ) {
        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/start-game`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async friendsGameFinish(context, payload ) {
        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/finish-game`

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

const wheelOfFortuneModule = {
    state,
    mutations,
    actions,
    getters
}
export default wheelOfFortuneModule;
