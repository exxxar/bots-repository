import util from './utilites';
import axios from "axios";

const BASE_PRODUCTS_LINK = '/bot/products'

let state = {
    products: [],
    products_paginate_object: null,
}

const getters = {
    getProducts: state => state.products || [],
    getProductById: (state) => (id) => {
        return state.products.find(item => item.id === id)
    },
    getProductsPaginateObject: state => state.products_paginate_object || null,
}

const actions = {
    async loadProducts(context, payload = {dataObject: null, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_PRODUCTS_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setProducts", dataObject.data)
            delete dataObject.data
            context.commit('setProductsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}

const mutations = {
    setProducts(state, payload) {
        state.products = payload || [];
        localStorage.setItem('cashman_products', JSON.stringify(payload));
    },
    setProductsPaginateObject(state, payload) {
        state.products_paginate_object = payload || [];
        localStorage.setItem('cashman_products_paginate_object', JSON.stringify(payload));
    }
}

const productsModule = {
    state,
    mutations,
    actions,
    getters
}
export default productsModule;
