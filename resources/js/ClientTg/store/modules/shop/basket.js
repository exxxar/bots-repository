import util from '../utilites';
import axios from "axios";
import {forEach} from "vue-circular-count-down-timer";

const BASE_BASKET_LINK = '/bot-client/basket'

let state = {
    basket_items: [],
    basket_items_paginate_object: null,
}

const getters = {
    getProductsInBasket: state => state.basket_items || [],
    getBasketPaginateObject: state => state.basket_items_paginate_object || null,
    inCollectionCart: (state) => (id, variantId) => {
        return state.basket_items.find(bItem => bItem.collection?.id === id &&
            (bItem.params?.variant_id === variantId || variantId == null))?.count || 0
    },
    inCart: (state) => (id) => {
        return (state.basket_items.find(item => item.product ? item.product.id === id : false))?.count || 0
    },
    cartProducts: (state, getters, rootState) => {
        return state.basket_items || [];
    },
    cartCollections: (state, getters, rootState) => {
        return (state.basket_items || []).filter(item => item.collection);
    },
    cartTotalCount: (state, getters) => {

        if (state.basket_items == null)
            return 0;

        if (state.basket_items.length === 0)
            return 0;

        let sum = 0;
        state.basket_items.forEach((item) => {
            sum += item.product?.is_weight_product || false ? 1 : item.count
        });
        return sum
    },
    cartTotalPrice: (state, getters) => {
        if (state.basket_items == null)
            return 0;

        if (state.basket_items.length === 0)
            return 0;

        let sum = 0;

        console.log(state.basket_items)
        state.basket_items.forEach((item) => {
            if (item.product) {
                let count =  item.product?.is_weight_product || false ? 1 : item.count
                let price = item.product?.is_weight_product || false ? (item.product.current_price * item.count) / (item.product.weight_config?.step || 100): item.product.current_price
                sum += price * count
            }

            if (item.collection) {
                let collectionPrice = 0;
                let selected = item.params.ids || []
                item.collection.products.forEach((sub) => {
                    collectionPrice += selected.indexOf(sub.id) !== -1 ? sub.current_price : 0
                })
                sum += (collectionPrice - collectionPrice * (item.collection.discount / 100)) * item.count
            }
        });
        return sum
    }
}

const actions = {
    async createCheckoutLink(context, payload = {deliveryForm: null}) {
        let link = `${BASE_BASKET_LINK}/checkout-link`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.deliveryForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    async startCheckout(context, payload = {deliveryForm: null}) {

        let link = `${BASE_BASKET_LINK}/checkout`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.deliveryForm)

        return _axios.then((response) => {
            context.commit("setBasket", [])
            context.commit('setBasketPaginateObject', null)
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    async loadProductsInBasket(context, payload = {dataObject: {search: null, categories: null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 20

        let link = `${BASE_BASKET_LINK}?page=${page}&size=${size}`

        let storedTable = localStorage.getItem("cashman_current_active_table") || null

        if (storedTable)
            storedTable = JSON.parse(storedTable)

        let _axios = util.makeAxiosFactory(link, "POST",{
            table_id: storedTable?.id || null
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    async addCommentToProduct(context, payload) {
        let link = `${BASE_BASKET_LINK}/comment-product`

        let _axios = util.makeAxiosFactory(link, "POST", {
            product_id: payload.id,
            comment: payload.comment || null
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    async addProductToCart(context, payload) {
        let link = `${BASE_BASKET_LINK}/inc-product`

        let storedTable = localStorage.getItem("cashman_current_active_table") || null

        if (storedTable)
            storedTable = JSON.parse(storedTable)

        let _axios = util.makeAxiosFactory(link, "POST", {
            product_id: payload.id,
            table_id:storedTable?.id || null
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },


    async incCollectionQuantity(context, payload) {
        let link = `${BASE_BASKET_LINK}/inc-collection`

        //  context.commit('incrementItemQuantity', id);
        let _axios = util.makeAxiosFactory(link, "POST", payload)
        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    async decCollectionQuantity(context, payload) {
        let link = `${BASE_BASKET_LINK}/dec-collection`

        //  context.commit('incrementItemQuantity', id);
        let _axios = util.makeAxiosFactory(link, "POST", payload)
        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    async incQuantity(context, id) {
        let link = `${BASE_BASKET_LINK}/increment/${id}`

        //  context.commit('incrementItemQuantity', id);

        let _axios = util.makeAxiosFactory(link, "POST")

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    async decQuantity(context, id) {
        let link = `${BASE_BASKET_LINK}/decrement/${id}`

        //  context.commit('decrementItemQuantity', id);

        let _axios = util.makeAxiosFactory(link, "POST")

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    removeProduct(context, id) {
        let link = `${BASE_BASKET_LINK}/remove/${id}`

        let _axios = util.makeAxiosFactory(link, "DELETE")

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    removeCollectionFromCart(context, payload) {
        let link = `${BASE_BASKET_LINK}/dec-collection`

        let _axios = util.makeAxiosFactory(link, "POST", payload)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },
    addCollectionToCart(context, collection) {
        let link = `${BASE_BASKET_LINK}/inc-collection`

        let _axios = util.makeAxiosFactory(link, "POST", {
            product_collection: collection
        })

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setBasket", dataObject.data)
            delete dataObject.data
            context.commit('setBasketPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    },

    clearCart(context) {
        let link = `${BASE_BASKET_LINK}/clear`

        context.commit("setBasket", [])
        context.commit('setBasketPaginateObject', null)

        let _axios = util.makeAxiosFactory(link, "DELETE")

        return _axios.then((response) => {
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err);
        })
    }

}

const mutations = {
    decrementItemQuantity(state, id) {
        const cartItem = state.basket_items
            .find(item => item.product?.id === id || item.collection?.id === id)

        if (cartItem.count > 1)
            cartItem.count--;

        localStorage.setItem('cashman_basket_items', JSON.stringify(state.basket_items));
    },
    incrementItemQuantity(state, id) {
        const cartItem = state.basket_items
            .find(item => item.product?.id === id || item.collection?.id === id)
        cartItem.count++

        localStorage.setItem('cashman_basket_items', JSON.stringify(state.basket_items));
    },
    setBasket(state, payload) {
        state.basket_items = payload || [];

        localStorage.setItem('cashman_basket_items', JSON.stringify(payload));
    },
    setBasketPaginateObject(state, payload) {
        state.basket_items_paginate_object = payload || [];
        localStorage.setItem('cashman_basket_items_paginate_object', JSON.stringify(payload));
    }
}

const basketItemsModule = {
    state,
    mutations,
    actions,
    getters
}
export default basketItemsModule;
