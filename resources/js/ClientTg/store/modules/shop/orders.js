import util from '../utilites';
import axios from "axios";

const BASE_ORDERS_LINK = '/bot-client/shop/orders'

let state = {
    orders: [],
    orders_paginate_object: null,

}

const getters = {
    getOrders: state => state.orders || [],
    getCategories: state => state.categories || [],
    getOrderById: (state) => (id) => {
        return state.orders.find(item => item.id === id)
    },
    getOrdersPaginateObject: state => state.orders_paginate_object || null,
    getCategoriesPaginateObject: state => state.categories_paginate_object || null,
}

const actions = {

    async loadOrders(context, payload = {dataObject: {search: null, categories:null}, page: 0, size: 12}) {
        let data = {
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = 20

        let link = `${BASE_ORDERS_LINK}?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setOrders", dataObject.data)
            delete dataObject.data
            context.commit('setOrdersPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async repeatOrder(context, payload) {
        let link = `${BASE_ORDERS_LINK}/repeat-order`

        let _axios = util.makeAxiosFactory(link, "POST", payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}

const mutations = {
    setOrders(state, payload) {
        state.orders = payload || [];
        localStorage.setItem('cashman_orders', JSON.stringify(payload));
    },
    setOrdersPaginateObject(state, payload) {
        state.orders_paginate_object = payload || [];
        localStorage.setItem('cashman_orders_paginate_object', JSON.stringify(payload));
    }
}

const ordersModule = {
    state,
    mutations,
    actions,
    getters
}
export default ordersModule;
