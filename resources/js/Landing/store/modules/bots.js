import util from './utilites';

const BASE_BOTS_LINK = '/landing/bots'

let state = {
    bots: [],
    bots_paginate_object: null,
}

const getters = {
    getBots: state => state.bots || [],
    getBotsPaginateObject: state => state.bots_paginate_object || null,
}

const actions = {
    async loadSimpleBots(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_BOTS_LINK}/simple-bot-list?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBots", dataObject.data)
            delete dataObject.data
            context.commit('setBotsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setBots(state, payload) {
        state.bots = payload || [];
        localStorage.setItem('cashman_landing_bots', JSON.stringify(payload));
    },
    setBotsPaginateObject(state, payload) {
        state.bots_paginate_object = payload || [];
        localStorage.setItem('cashman_landing_bots_paginate_object', JSON.stringify(payload));
    }


}

const botsModule = {
    state,
    mutations,
    actions,
    getters
}
export default botsModule;
