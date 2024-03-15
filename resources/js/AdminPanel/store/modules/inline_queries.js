import util from './utilites';


const BASE_INLINE_QUERY_LINK = '/admin/inline-queries'

let state = {
    inline_queries: [],
    inline_queries_paginate_object: null,

}

const getters = {
    getInlineQueries: state => state.inline_queries || [],
    getInlineQueriesPaginateObject: state => state.inline_queries_paginate_object || null,
}

const actions = {
    async loadInlineQueries(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_INLINE_QUERY_LINK}/list-of-inline-queries?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setInlineQueries", dataObject.data)
            delete dataObject.data
            context.commit('setInlineQueriesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeInlineQuery(context, payload = {inlineQueryForm: null}) {
        let link = `${BASE_INLINE_QUERY_LINK}/query-store`

        let _axios = util.makeAxiosFactory(link,"POST", payload.inlineQueryForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeInlineQuery(context, payload= {queryId: null}){
        let link = `${BASE_INLINE_QUERY_LINK}/remove-query/${payload.queryId}`

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
    setInlineQueries(state, payload) {
        state.inline_queries = payload || [];
        localStorage.setItem('cashman_inline_queries', JSON.stringify(payload));
    },

    setInlineQueriesPaginateObject(state, payload) {
        state.inline_queries_paginate_object = payload || [];
        localStorage.setItem('cashman_inline_queries_paginate_object', JSON.stringify(payload));
    },

}

const inlineQueryModule = {
    state,
    mutations,
    actions,
    getters
}
export default inlineQueryModule;
