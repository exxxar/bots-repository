import util from '../utilites';
import axios from "axios";

const BASE_REQUEST_PHOTO_LINK = '/bot-client/request-photo'

let state = {}

const getters = {}

const actions = {
    async requestPhotoLoadData(context) {
        let link = `${BASE_REQUEST_PHOTO_LINK}/load-data`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async requestPhotoResult(context, payload = {form: null}) {

        let link = `${BASE_REQUEST_PHOTO_LINK}/callback`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.form)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {}

const requestPhotoModule = {
    state,
    mutations,
    actions,
    getters
}
export default requestPhotoModule;
