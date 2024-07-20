import util from '../utilites';
import axios from "axios";

const BASE_REVIEWS_LINK = '/bot-client/shop/reviews'

let state = {
    reviews: [],

    reviews_paginate_object: null,

}

const getters = {
    getReviews: state => state.reviews || [],

    getReviewById: (state) => (id) => {
        return state.reviews.find(item => item.id === id)
    },
    getReviewsPaginateObject: state => state.reviews_paginate_object || null,

}

const actions = {


    async storeReview(context, payload = {reviewForm: null}) {
        let link = `${BASE_REVIEWS_LINK}/store-review`

        let _axios = util.makeAxiosFactory(link, "POST", payload.reviewForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


    async loadReviews(context, payload = {dataObject: null, page: 0, size: 12}) {
        let data = {
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = payload.size || 20


        let link = `${BASE_REVIEWS_LINK}?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setReviews", dataObject.data)
            delete dataObject.data
            context.commit('setReviewsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


    async loadReviewsByProductId(context, payload = {dataObject: {product_id: null}, page: 0, size: 12}) {
        let data = {
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = payload.size || 20


        let link = `${BASE_REVIEWS_LINK}/by-product-id?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setReviews", dataObject.data)
            delete dataObject.data
            context.commit('setReviewsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}

const mutations = {
    setReviews(state, payload) {
        state.reviews = payload || [];
        localStorage.setItem('cashman_reviews', JSON.stringify(payload));
    },
    setReviewsPaginateObject(state, payload) {
        state.reviews_paginate_object = payload || [];
        localStorage.setItem('cashman_reviews_paginate_object', JSON.stringify(payload));
    }
}

const reviewsModule = {
    state,
    mutations,
    actions,
    getters
}
export default reviewsModule;
