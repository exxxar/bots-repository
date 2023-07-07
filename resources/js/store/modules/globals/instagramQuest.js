import util from '../utilites';
import axios from "axios";

const BASE_INSTAGRAM_QUEST_LINK = '/global-scripts/instagram-quest'

let state = {}

const getters = {}

const actions = {
    async instagramQuestPrepare(context, payload = {prepareForm: null, botDomain:null}) {
        let link = `${BASE_INSTAGRAM_QUEST_LINK}/prepare/${payload.botDomain}`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.prepareForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async instagramQuestResult(context, payload = {instaForm: null, botDomain:null}) {
        let link = `${BASE_INSTAGRAM_QUEST_LINK}/${payload.botDomain}`

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
