import util from './utilites';
import axios from "axios";

const BASE_PAGES_LINK = '/bot/pages'

let state = {
    pages: [],
    pages_paginate_object: null,
}

const getters = {
    getPages: state => state.pages || [],
    getPageById: (state) => (id) => {
        return state.pages.find(item => item.id === id)
    },
    getPagesPaginateObject: state => state.pages_paginate_object || null,
}

const actions = {
    async loadPages(context, payload = {dataObject: {botId: null, search:null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_PAGES_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setPages", dataObject.data)
            delete dataObject.data
            context.commit('setPagesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updatePage(context, payload= {pageForm: null}){
        let link = `${BASE_PAGES_LINK}/page-update`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.pageForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removePage(context, payload= {dataObject: {pageId: null}}){
        let link = `${BASE_PAGES_LINK}/${payload.dataObject.pageId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createPage(context, payload = {pageForm: null}) {
        let link = `${BASE_PAGES_LINK}/page`

        let _axios = util.makeAxiosFactory(link,"POST", payload.pageForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setPages(state, payload) {
        state.pages = payload || [];
        localStorage.setItem('cashman_pages', JSON.stringify(payload));
    },
    setPagesPaginateObject(state, payload) {
        state.pages_paginate_object = payload || [];
        localStorage.setItem('cashman_pages_paginate_object', JSON.stringify(payload));
    }
}

const pagesModule = {
    state,
    mutations,
    actions,
    getters
}
export default pagesModule;
