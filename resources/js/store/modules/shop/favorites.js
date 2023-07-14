import util from '../utilites';
import axios from "axios";

const BASE_FAVORITES_LINK = '/global-scripts/shop/favorites'

let state = {
    favorites: [],
    favorites_paginate_object: null,
}

const getters = {
    getFavorites: state => state.favorites || [],
    getProductById: (state) => (id) => {
        return state.favorites.find(item => item.id === id)
    },
    getFavoritesPaginateObject: state => state.favorites_paginate_object || null,
}

const actions = {
    async loadFavorites(context, payload = {dataObject: {bot_id: null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_FAVORITES_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setFavorites", dataObject.data)
            delete dataObject.data
            context.commit('setFavoritesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async addToFavorites(context, payload= {dataObject: {bot_id: null, tg_id:null, product_id:null}}){
        let link = `${BASE_FAVORITES_LINK}/add`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeFromFavorites(context, payload= {dataObject: {bot_id: null, tg_id:null, product_id:null}}){
        let link = `${BASE_FAVORITES_LINK}/remove`

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
    setFavorites(state, payload) {
        state.favorites = payload || [];
        localStorage.setItem('cashman_favorites', JSON.stringify(payload));
    },
    setFavoritesPaginateObject(state, payload) {
        state.favorites_paginate_object = payload || [];
        localStorage.setItem('cashman_favorites_paginate_object', JSON.stringify(payload));
    }
}

const favoritesModule = {
    state,
    mutations,
    actions,
    getters
}
export default favoritesModule;
