import util from '../../utilites';
import axios from "axios";

const BASE_ACTIONS_LINK = '/bot-client/actions'

let state = {
    actions: [],
    actions_paginate_object: null,
}

const getters = {
    getActions: state => state.actions || [],
    getActionsPaginateObject: state => state.actions_paginate_object || null,
}

const actions = {

    async loadActions(context, payload = { dataObject:{ search:null } ,page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12
        let link = `${BASE_ACTIONS_LINK}/history?page=${page}&size=${size}`

        let _axios = util.makeAxiosFactory(link, 'POST', {
            ...payload.dataObject
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setActions", dataObject.data)
            delete dataObject.data
            context.commit('setActionsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeActions(context, payload = {dataObject: null}) {
        let link = `${BASE_ACTIONS_LINK}/remove`

        let _axios = util.makeAxiosFactory(link, 'POST', {
            ...payload.dataObject
        })

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },



}
const mutations = {
    setActions(state, payload) {
        state.actions = payload || [];
        localStorage.setItem('cashman_actions', JSON.stringify(payload));
    },
    setActionsPaginateObject(state, payload) {
        state.actions_paginate_object = payload || [];
        localStorage.setItem('cashman_actions_paginate_object', JSON.stringify(payload));
    }
}

const actionsModule = {
    state,
    mutations,
    actions,
    getters
}
export default actionsModule;
