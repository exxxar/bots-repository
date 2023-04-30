import util from './utilites';
import axios from "axios";

const BASE_CASHBACK_LINK = '/bot/cashback'

let state = {
    cashback: [],
    cashback_paginate_object: null,
}

const getters = {
    getCashBack: state => state.cashback || [],
    getCashBackPaginateObject: state => state.cashback_paginate_object || null,
}

const actions = {
    async loadCashBack(context, payload = {dataObject: null, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_CASHBACK_LINK}/history?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setCashBack", dataObject.data)
            delete dataObject.data
            context.commit('setCashBackPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeCashBack(context, payload){
        let link = `${BASE_CASHBACK_LINK}/remove`

        let _axios = util.makeAxiosFactory(link, 'POST',  payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async addCashBack(context, payload){
        let link = `${BASE_CASHBACK_LINK}/add`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async acceptUserInLocation(context, payload){
        let link = `${BASE_CASHBACK_LINK}/user-in-location`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveDeliveryman(context, payload){
        let link = `${BASE_CASHBACK_LINK}/deliveryman`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveVip(context, payload){
        let link = `${BASE_CASHBACK_LINK}/vip`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setCashBack(state, payload) {
        state.cashback = payload || [];
        localStorage.setItem('cashman_cashback', JSON.stringify(payload));
    },
    setCashBackPaginateObject(state, payload) {
        state.cashback_paginate_object = payload || [];
        localStorage.setItem('cashman_cashback_paginate_object', JSON.stringify(payload));
    }
}

const cashbackModule = {
    state,
    mutations,
    actions,
    getters
}
export default cashbackModule;
