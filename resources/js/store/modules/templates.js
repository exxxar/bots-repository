import util from './utilites';
import axios from "axios";

const BASE_TEMPLATES_LINK = '/bot/templates'

let state = {}

const getters = {}

const actions = {
    async loadTemplates(context) {
        let link = `${BASE_TEMPLATES_LINK}/bots`

        let _axios = util.makeAxiosFactory(link)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadDescription(context) {
        let link = `${BASE_TEMPLATES_LINK}/description`

        let _axios = util.makeAxiosFactory(link)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadKeyboards(context, payload = {botId: null}) {
        let link = `${BASE_TEMPLATES_LINK}/keyboards/${payload.botId}`

        let _axios = util.makeAxiosFactory(link)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async loadMenuByBotId(context, payload = {botId: null}) {
        let link = `${BASE_TEMPLATES_LINK}/image-menu/${payload.botId}`

        let _axios = util.makeAxiosFactory(link,"GET")

        return _axios.then((response) => {
            return Promise.resolve(response.data.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadLocationsByCompany(context, payload = {companyId: null}) {
        let link = `${BASE_TEMPLATES_LINK}/location/${payload.companyId}`

        let _axios = util.makeAxiosFactory(link,"GET")

        return _axios.then((response) => {
            return Promise.resolve(response.data.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createLocation(context, payload = {locationForm: null}) {
        let link = `${BASE_TEMPLATES_LINK}/location`

        let _axios = util.makeAxiosFactory(link,"POST", payload.locationForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createImageMenu(context, payload = {menuForm: null}) {
        let link = `${BASE_TEMPLATES_LINK}/image-menu`

        let _axios = util.makeAxiosFactory(link,"POST", payload.menuForm)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createBot(context, payload = {botForm: null}) {
        let link = `${BASE_TEMPLATES_LINK}/bot`

        let _axios = util.makeAxiosFactory(link,"POST", payload.botForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadAllSlugs(context,) {
        let link = `${BASE_TEMPLATES_LINK}/slugs`

        let _axios = util.makeAxiosFactory(link)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async loadSlugs(context, payload = {botId: null}) {
        let link = `${BASE_TEMPLATES_LINK}/slugs/${payload.botId}`

        let _axios = util.makeAxiosFactory(link)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setTemplates(state, payload) {
        state.templates = payload || [];
        localStorage.setItem('cashman_templates', JSON.stringify(payload));
    },
    setTemplatesPaginateObject(state, payload) {
        state.templates_paginate_object = payload || [];
        localStorage.setItem('cashman_templates_paginate_object', JSON.stringify(payload));
    }
}

const templatesModule = {
    state,
    mutations,
    actions,
    getters
}
export default templatesModule;
