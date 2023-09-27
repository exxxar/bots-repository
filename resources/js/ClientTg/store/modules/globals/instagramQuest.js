import util from '../utilites';
import axios from "axios";

const BASE_INSTAGRAM_QUEST_LINK = '/bot-client/instagram-quest'

let state = {}

const getters = {}

const actions = {
    async instagramQuestLoadData(context) {


        let link = `${BASE_INSTAGRAM_QUEST_LINK}/load-data`


        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async instagramQuestPrepare(context, payload = {prepareForm: null}) {

        let link = `${BASE_INSTAGRAM_QUEST_LINK}/prepare`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async instagramQuestResult(context, payload = {instaForm: null}) {

        let link = `${BASE_INSTAGRAM_QUEST_LINK}/callback`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.instaForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {}

const instagramQuestModule = {
    state,
    mutations,
    actions,
    getters
}
export default instagramQuestModule;
