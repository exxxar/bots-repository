import util from './utilites';
import axios from "axios";

const BASE_CASHBACK_LINK = '/bot/referrals'

let state = {
    referrals: [],
    referrals_paginate_object: null,
}

const getters = {
    getReferrals: state => state.referrals || [],
    getReferralsPaginateObject: state => state.referrals_paginate_object || null,
}

const actions = {
    async loadReferrals(context, payload = {dataObject: null, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_CASHBACK_LINK}/history?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setReferrals", dataObject.data)
            delete dataObject.data
            context.commit('setReferralsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setReferrals(state, payload) {
        state.referrals = payload || [];
        localStorage.setItem('cashman_referrals', JSON.stringify(payload));
    },
    setReferralsPaginateObject(state, payload) {
        state.referrals_paginate_object = payload || [];
        localStorage.setItem('cashman_referrals_paginate_object', JSON.stringify(payload));
    }
}

const referralsModule = {
    state,
    mutations,
    actions,
    getters
}
export default referralsModule;
