import util from './utilites';
import axios from "axios";

const BASE_PROFILE_FORM_LINK = '/bot-client/profile-form'

let state = {

}

const getters = {

}

const actions = {
    async loadProfileFormData(context) {
        let link = `${ BASE_PROFILE_FORM_LINK}/load-profile-data`
        let _axios = util.makeAxiosFactory(link, 'POST')
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async saveProfileFormData(context, payload = {dataObject:null}) {
        let link = `${BASE_PROFILE_FORM_LINK}/store-profile-data`

        let _axios = util.makeAxiosFactory(link, 'POST', data.dataObject)

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

const cashbackModule = {
    state,
    mutations,
    actions,
    getters
}
export default cashbackModule;
