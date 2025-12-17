import util from './utilites';
import axios from "axios";

const BASE_PARTNERS_LINK = '/bot-client/partners'

let state = {
    partners: [],
    partners_paginate_object: null,
}

const getters = {
    getPartners: state => state.partners || [],
    getPartnersPaginateObject: state => state.partners_paginate_object || null,
}

const actions = {
    async loadPartners(context, payload) {
        let link = `${ BASE_PARTNERS_LINK}`
        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setPartners", dataObject.data)
            delete dataObject.data
            context.commit('setPartnersPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updatePartnersSettings(context, payload) {
        let link = `${BASE_PARTNERS_LINK}/update-settings`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async updateSelfPartner(context, payload) {
        let link = `${BASE_PARTNERS_LINK}/update-self`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.form)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async updatePartner(context, payload) {
        let link = `${BASE_PARTNERS_LINK}/update`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.form)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


    async listOfPartnersCategories(context, payload) {
        let link = `${BASE_PARTNERS_LINK}/partners-categories`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async changePartnerProductStatus(context, payload) {
        let link = `${BASE_PARTNERS_LINK}/change-status`

        let _axios = util.makeAxiosFactory(link, 'POST', payload)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storePartner(context, payload) {
        let link = `${BASE_PARTNERS_LINK}/store`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.form)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async removePartner(context, payload = {partnerId:null}) {
        let link = `${BASE_PARTNERS_LINK}/${payload.partnerId}`

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
    setPartners(state, payload) {
        state.partners = payload || [];
        localStorage.setItem('cashman_partners', JSON.stringify(payload));
    },
    setPartnersPaginateObject(state, payload) {
        state.partners_paginate_object = payload || [];
        localStorage.setItem('cashman_partners_paginate_object', JSON.stringify(payload));
    }
}

const partnersModule = {
    state,
    mutations,
    actions,
    getters
}
export default partnersModule;
