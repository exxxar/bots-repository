import util from '../../utilites';
import axios from "axios";

const BASE_PRODUCTS_LINK = '/global-scripts/shop/products'

let state = {
  /*  products: [],
    products_paginate_object: null,*/
}

const getters = {
   /* getProducts: state => state.products || [],
    getProductById: (state) => (id) => {
        return state.products.find(item => item.id === id)
    },
    getProductsPaginateObject: state => state.products_paginate_object || null,*/
}

const actions = {

    /*async loadProduct(context, payload = {dataObject: { productId: null}}) {

        let link = `${BASE_PRODUCTS_LINK}/${payload.dataObject.productId}`
        let method = 'GET'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadProducts(context, payload = {dataObject: {bot_id: null}, page: 0, size: 12}) {
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
    async loadRandomProducts(context, payload = {dataObject: {bot_id: null}}) {

        let link = `${BASE_PRODUCTS_LINK}`
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
    },*/
}

const mutations = {
   /* setProducts(state, payload) {
        state.products = payload || [];
        localStorage.setItem('cashman_products', JSON.stringify(payload));
    },
    setProductsPaginateObject(state, payload) {
        state.products_paginate_object = payload || [];
        localStorage.setItem('cashman_products_paginate_object', JSON.stringify(payload));
    }*/
}

const productsModule = {
    state,
    mutations,
    actions,
    getters
}
export default productsModule;
