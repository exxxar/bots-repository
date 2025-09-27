import util from '../utilites';
import axios from "axios";

const BASE_PRODUCTS_LINK = '/bot-client/shop/products'

let state = {
    products: [],
    categories: [],
    products_paginate_object: null,
    categories_paginate_object: null,
}

const getters = {
    getProducts: state => state.products || [],
    getCategories: state => state.categories || [],
    getProductById: (state) => (id) => {
        return state.products.find(item => item.id === id)
    },
    getProductsPaginateObject: state => state.products_paginate_object || null,
    getCategoriesPaginateObject: state => state.categories_paginate_object || null,
}

const actions = {

    async loadShopModuleData(context) {


        let link = `${BASE_PRODUCTS_LINK}/load-data`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async exportAllProducts(context) {


        let link = `${BASE_PRODUCTS_LINK}/export-all-products`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeProductCategory(context, payload = {category_id: null}) {
        let link = `${BASE_PRODUCTS_LINK}/remove-category/${payload.category_id}`

        let _axios = util.makeAxiosFactory(link, "DELETE")

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
    async addProductCategory(context, payload = {category: null}) {
        let link = `${BASE_PRODUCTS_LINK}/add-category`

        let _axios = util.makeAxiosFactory(link, "POST", payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async saveProduct(context, payload = {productForm: null}) {
        let link = `${BASE_PRODUCTS_LINK}/add-product`

        let _axios = util.makeAxiosFactory(link, "POST", payload.productForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeAllProducts(context) {
        let link = `${BASE_PRODUCTS_LINK}/remove-all-products`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeShopProduct(context, id) {
        let link = `${BASE_PRODUCTS_LINK}/${id}`
        let method = 'DELETE'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadProductsByCategory(context) {

        let link = `${BASE_PRODUCTS_LINK}-by-category`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadProduct(context, payload = {dataObject: {productId: null}}) {

        let link = `${BASE_PRODUCTS_LINK}/${payload.dataObject.productId}`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async addToStopListProduct(context, payload) {


        let link = `${BASE_PRODUCTS_LINK}/stop-list-product/${payload}`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async restoreProduct(context, payload) {


        let link = `${BASE_PRODUCTS_LINK}/restore-product/${payload}`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCategory(context, payload = {dataObject: {categoryId: null}}) {


        let link = `${BASE_PRODUCTS_LINK}/category/${payload.dataObject.categoryId}`
        let method = 'POST'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadProducts(context, payload = {dataObject: {search: null, categories:null}, page: 0, size: 12}) {
        let data = {
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = 20

        let link = `${BASE_PRODUCTS_LINK}?page=${page}&size=${size}`
        let method = 'POST'

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
    async loadProductsInCategory(context, payload = {dataObject: {search: null, category_id: null}, page: 0, size: 12}) {
        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain,
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = 12

        let link = `${BASE_PRODUCTS_LINK}/in-category?page=${page}&size=${size}`
        let method = 'POST'

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
    async loadCategories(context, payload = {page: 0, size: 100,dataObject:null}) {

        let page = payload.page || 0
        let size = payload.size || 5

        let link = `${BASE_PRODUCTS_LINK}/categories?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            const dataObject = response.data

            context.commit("setCategories", dataObject.data)
            delete dataObject.data
            context.commit('setCategoriesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadRandomProducts(context) {

        let tgData = window.Telegram.WebApp.initData || null
        let botDomain = window.currentBot.bot_domain || null
        let slugId = window.currentScript || null

        let data = {
            tgData: tgData,
            slug_id: slugId,
            botDomain: botDomain
        }

        let link = `${BASE_PRODUCTS_LINK}/random`
        let method = 'POST'

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
    setCategories(state, payload) {
        state.categories = payload || [];
        localStorage.setItem('cashman_categories', JSON.stringify(payload));
    },
    setCategoriesPaginateObject(state, payload) {
        state.categories_paginate_object = payload || [];
        localStorage.setItem('cashman_categories_paginate_object', JSON.stringify(payload));
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
