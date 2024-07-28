import util from './utilites';

const BASE_PROMOCODES_LINK = '/bot-client/promocodes'

let state = {
    promo_codes: [],
    promo_codes_paginate_object: null,
}

const getters = {
    getPromoCodes: state => state.promo_codes || [],
    getPromoCodesPaginateObject: state => state.promo_codes_paginate_object || null,
}

const actions = {
    async activatePromocode(context, payload= {promocodeForm: null}){
        let link = `${BASE_PROMOCODES_LINK}/activate`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.promocodeForm)
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async activateShopDiscountPromocode(context, payload= {promocodeForm: null}){
        let link = `${BASE_PROMOCODES_LINK}/activate-shop-discount`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.promocodeForm)
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async loadPromoCodes(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_PROMOCODES_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setPromoCodes", dataObject.data)
            delete dataObject.data
            context.commit('setPromoCodesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storePromoCodes(context, payload = {promoCodeForm: null}) {
        let link = `${BASE_PROMOCODES_LINK}/store`

        let _axios = util.makeAxiosFactory(link,"POST", payload.promoCodeForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removePromoCodes(context, payload= {promoCodeId: null}){
        let link = `${BASE_PROMOCODES_LINK}/${payload.promoCodeId}`

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

    setPromoCodes(state, payload) {
        state.promo_codes = payload || [];
        localStorage.setItem('cashman_promo_codes', JSON.stringify(payload));
    },

    setPromoCodesPaginateObject(state, payload) {
        state.promo_codes_paginate_object = payload || [];
        localStorage.setItem('cashman_promo_codes_paginate_object', JSON.stringify(payload));
    },

}

const promocodesModule = {
    state,
    mutations,
    actions,
    getters
}
export default promocodesModule;
