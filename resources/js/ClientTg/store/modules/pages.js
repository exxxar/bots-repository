import util from './utilites';

const BASE_PAGES_LINK = '/bot-client/pages'

let state = {
    pages: [],
    pages_paginate_object: null,
}

const getters = {
    getPages: state => state.pages || [],
    getPagesPaginateObject: state => state.pages_paginate_object || null,
}

const actions = {
    async loadPages(context, payload = {dataObject: { search:null}, page: 0, size: 12}) {

        let tgData =   window.Telegram ? (window.Telegram.WebApp.initData || null ) : null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_PAGES_LINK}?page=${page}&size=${size}`
        let method = 'POST'

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

        let tgData =  window.Telegram ? (window.Telegram.WebApp.initData || null ) : null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        payload.pageForm.append("tgData", tgData)
        payload.pageForm.append("botDomain", botDomain)
        payload.pageForm.append("slug_id", slugId)

        let link = `${BASE_PAGES_LINK}/page-update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.pageForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async duplicatePage(context, payload= {dataObject: {pageId: null}}){
        let link = `${BASE_PAGES_LINK}/duplicate/${payload.dataObject.pageId}`
        let _axios = util.makeAxiosFactory(link, 'POST')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removePage(context, payload= {dataObject: {pageId: null}}){

        let tgData =  window.Telegram ? (window.Telegram.WebApp.initData || null ) : null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
        }

        let link = `${BASE_PAGES_LINK}/remove/${payload.dataObject.pageId}`
        let _axios = util.makeAxiosFactory(link, 'POST', data)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createPage(context, payload = {pageForm: null}) {
        let tgData =  window.Telegram ? (window.Telegram.WebApp.initData || null ) : null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        payload.pageForm.append("tgData", tgData)
        payload.pageForm.append("botDomain", botDomain)
        payload.pageForm.append("slug_id", slugId)

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
