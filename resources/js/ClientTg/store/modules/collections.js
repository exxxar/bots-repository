import util from './utilites';

const BASE_COLLECTIONS_LINK = '/bot-client/product-collections'

let state = {
    collections: [],
    collections_paginate_object: null,
}

const getters = {
    getCollections: state => state.collections || [],
    getCollectionsPaginateObject: state => state.collections_paginate_object || null,
}

const actions = {

    async loadGlobalCollections(context, payload = {dataObject: { search:null}, page: 0, size: 12}) {


        let data = {
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_COLLECTIONS_LINK}/global?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setCollections", dataObject.data)
            delete dataObject.data
            context.commit('setCollectionsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCollections(context, payload = {dataObject: { search:null}, page: 0, size: 12}) {


        let data = {
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_COLLECTIONS_LINK}?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setCollections", dataObject.data)
            delete dataObject.data
            context.commit('setCollectionsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeCollection(context, payload= {collectionForm: null}){

        let link = `${BASE_COLLECTIONS_LINK}/store`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.collectionForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async duplicateCollection(context, payload= {collectionId: null}){



        let link = `${BASE_COLLECTIONS_LINK}/duplicate/${payload.dataObject.collectionId}`
        let _axios = util.makeAxiosFactory(link, 'POST')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeProductCollection(context, payload= {collectionId: null}){


        let link = `${BASE_COLLECTIONS_LINK}/remove/${payload.collectionId}`
        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setCollections(state, payload) {
        state.collections = payload || [];
        localStorage.setItem('cashman_collections', JSON.stringify(payload));
    },
    setCollectionsPaginateObject(state, payload) {
        state.collections_paginate_object = payload || [];
        localStorage.setItem('cashman_collections_paginate_object', JSON.stringify(payload));
    }
}

const collectionsModule = {
    state,
    mutations,
    actions,
    getters
}
export default collectionsModule;
