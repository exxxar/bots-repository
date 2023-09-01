import util from './utilites';

const BASE_SLUGS_LINK = '/bot-client/slugs'

let state = {
    slugs: [],
    slugs_paginate_object: null,
}

const getters = {
    getSlugs: state => state.slugs || [],
    getSlugById: (state) => (id) => {
        return state.slugs.find(item => item.id === id)
    },
    getSlugsPaginateObject: state => state.slugs_paginate_object || null,
}

const actions = {
 /*   async loadBotSlugs(context, payload = {botId: null, isGlobal:false}) {
        let link = `${BASE_TEMPLATES_LINK}/slugs/${payload.botId}?isGlobal=${payload.isGlobal || false}`

        let _axios = util.makeAxiosFactory(link)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },*/



    async loadGlobalSlugs(context, payload = {dataObject: { search:null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_SLUGS_LINK}/global-list?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setSlugs", dataObject.data)
            delete dataObject.data
            context.commit('setSlugsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadSlugs(context, payload = {dataObject: {botId: null, search:null, needGlobal:false}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_SLUGS_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setSlugs", dataObject.data)
            delete dataObject.data
            context.commit('setSlugsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateSlug(context, payload= {slugForm: null}){
        let link = `${BASE_SLUGS_LINK}/slug-update`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.slugForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async duplicateSlug(context, payload= {dataObject: {slugId: null}}){
        let link = `${BASE_SLUGS_LINK}/duplicate/${payload.dataObject.slugId}`
        let _axios = util.makeAxiosFactory(link, 'POST')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeSlug(context, payload= {dataObject: {slugId: null}}){
        let link = `${BASE_SLUGS_LINK}/${payload.dataObject.slugId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async refreshSlugParams(context, payload= {dataObject: {slugId: null}}){
        let link = `${BASE_SLUGS_LINK}/reload-params/${payload.dataObject.slugId}`
        let _axios = util.makeAxiosFactory(link, 'GET')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createSlug(context, payload = {slugForm: null}) {
        let link = `${BASE_SLUGS_LINK}/slug`

        let _axios = util.makeAxiosFactory(link,"POST", payload.slugForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setSlugs(state, payload) {
        state.slugs = payload || [];
        localStorage.setItem('cashman_slugs', JSON.stringify(payload));
    },
    setSlugsPaginateObject(state, payload) {
        state.slugs_paginate_object = payload || [];
        localStorage.setItem('cashman_slugs_paginate_object', JSON.stringify(payload));
    }
}

const slugsModule = {
    state,
    mutations,
    actions,
    getters
}
export default slugsModule;
