import util from './utilites';
import axios from "axios";

const BASE_PRODUCTS_LINK = '/admin/shop/products'

let state = {
    products: [],
    product_categories: [],
    products_paginate_object: null,
    product_categories_paginate_object: null,
}

const getters = {
    getProducts: state => state.products || [],
    getProductCategories: state => state.product_categories || [],
    getProductById: (state) => (id) => {
        return state.products.find(item => item.id === id)
    },
    getProductsPaginateObject: state => state.products_paginate_object || null,
    getProductCategoriesPaginateObject: state => state.product_categories_paginate_object || null,
}

const actions = {

    async storeProductCategory(context, payload = {category: null}) {
        let link = `${BASE_PRODUCTS_LINK}/store-category`

        let _axios = util.makeAxiosFactory(link, "POST", payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadProduct(context, payload = {dataObject: { productId: null}}) {

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
    async loadProductCategories(context, payload = {dataObject: {bot_id: null}}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_PRODUCTS_LINK}/categories?page=${page}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setProductCategories", dataObject.data)
            delete dataObject.data
            context.commit('setProductCategoriesPaginateObject', dataObject)
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
    },

    async saveProduct(context, payload = {productForm: null}) {
        let link = `${BASE_PRODUCTS_LINK}/save`

        let _axios = util.makeAxiosFactory(link,"POST", payload.productForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async changeProductCategoryStatus(context, payload) {
        let link = `${BASE_PRODUCTS_LINK}/categories/status/${payload}`

        let _axios = util.makeAxiosFactory(link,"POST")

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeProductCategory(context, payload) {
        let link = `${BASE_PRODUCTS_LINK}/categories/remove/${payload}`

        let _axios = util.makeAxiosFactory(link,"DELETE")

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeProduct(context, payload) {
        let link = `${BASE_PRODUCTS_LINK}/remove/${payload}`

        let _axios = util.makeAxiosFactory(link,"DELETE")

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async duplicateProduct(context, payload) {
        let link = `${BASE_PRODUCTS_LINK}/duplicate/${payload}`

        let _axios = util.makeAxiosFactory(link,"POST")

        return _axios.then((response) => {
            return Promise.resolve(response.data);
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
    setProductCategories(state, payload) {
        state.product_categories = payload || [];
        localStorage.setItem('cashman_product_categories', JSON.stringify(payload));
    },
    setProductsPaginateObject(state, payload) {
        state.products_paginate_object = payload || [];
        localStorage.setItem('cashman_products_paginate_object', JSON.stringify(payload));
    },
    setProductCategoriesPaginateObject(state, payload) {
        state.product_categories_paginate_object = payload || [];
        localStorage.setItem('cashman_product_categories_paginate_object', JSON.stringify(payload));
    }
}

const productsModule = {
    state,
    mutations,
    actions,
    getters
}
export default productsModule;
