import util from './utilites';


const BASE_QUEUE_LINK = '/bot-client/mailing'

let state = {
    queues: [],
    queues_paginate_object: null,
}

const getters = {
    getQueues: state => state.queues || [],

    getQueuesPaginateObject: state => state.queues_paginate_object || null,

}

const actions = {

    async loadQueues(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_QUEUE_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setQueues", dataObject.data)
            delete dataObject.data
            context.commit('setQueuesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storeQueue(context, payload = {queueForm: null}) {
        let link = `${BASE_QUEUE_LINK}/send-to-queue`

        let _axios = util.makeAxiosFactory(link,"POST", payload.queueForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeQueue(context, payload= {queueId: null}){
        let link = `${BASE_QUEUE_LINK}/remove/${payload.queueId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {
    setQueues(state, payload) {
        state.queues = payload || [];
        localStorage.setItem('cashman_queues', JSON.stringify(payload));
    },

    setQueuesPaginateObject(state, payload) {
        state.queues_paginate_object = payload || [];
        localStorage.setItem('cashman_queues_paginate_object', JSON.stringify(payload));
    },

}

const queueModule = {
    state,
    mutations,
    actions,
    getters
}
export default queueModule;
