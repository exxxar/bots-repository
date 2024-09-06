import util from "../utilites";

const state = {
    items: localStorage.getItem('cashman_basket') == null ? [] : JSON.parse(localStorage.getItem('cashman_basket')),
}

// getters
const getters = {
    inCart: (state) => (id) => {
        let item = state.items.find(item => item.product.id === id)

        return item ? (state.items.find(item => item.product.id === id)).quantity || 0 : 0
    },
    cartProducts: (state, getters, rootState) => {
        return state.items;
    },
    cartTotalCount: (state, getters) => {

        if (state.items == null)
            return 0;

        if (state.items.length === 0)
            return 0;

        let sum = 0;
        state.items.forEach((item) => {
            sum += item.quantity
        });
        return sum
    },
    cartTotalPrice: (state, getters) => {
        if (state.items == null)
            return 0;

        if (state.items.length === 0)
            return 0;

        let sum = 0;

        state.items.forEach((item) => {
            if (item.product)
                sum += item.product.current_price * item.quantity
        });
        return sum
    }
}

// actions
const actions = {
    async createCheckoutLink(context, payload = {deliveryForm: null}) {

        let products = []
        context.state.items.forEach(item => {
            if (item.product && (item.type || 'product') === 'product')
                products.push({
                    id: item.product.id,
                    type: item.type || 'product',
                    count: item.quantity || 0
                })
        })

        payload.deliveryForm.append("products", JSON.stringify(products))
        let link = `/bot-client/shop/checkout-link`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.deliveryForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async startCheckout(context, payload = {deliveryForm: null}) {

        let products = []
        let collections = []
        context.state.items.forEach(item => {
            if (item.product && (item.type || 'product') === 'product')
                products.push({
                    id: item.product.id,
                    type: item.type || 'product',
                    count: item.quantity || 0
                })

            if (item.product && (item.type || 'product') === 'collection')
                collections.push({
                    data: item.product,
                    type: item.type || 'collection',
                    count: item.quantity || 0
                })
        })

        payload.deliveryForm.append("products", JSON.stringify(products))
        payload.deliveryForm.append("collections", JSON.stringify(collections))


        let link = `/bot-client/shop/checkout-instruction`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.deliveryForm)

        return _axios.then((response) => {
            context.commit("setCartItems", [])
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadActualPriceInCart(context) {

        let ids = []
        context.state.items.forEach(item => {
            if (item.product && (item.type || 'product') === 'product')
                ids.push(item.product.id)

            if (item.product && (item.type || 'product') === 'collection') {
                const productCollection = item.product.products || []
                productCollection.forEach(p => {
                    if (ids.indexOf(p.id) === -1)
                        ids.push(p.id)
                })
            }
        })

        if (ids.length === 0)
            return

        let data = util.loadActualProducts(ids)

        return data.then((response) => {
            let products = response;

            context.state.items.forEach(item => {
                if (item.product && (item.type || 'product') === 'product')
                    item.product = products.find(sub => sub.id === item.product.id)

                if (item.product && (item.type || 'product') === 'collection') {
                    item.product.products.forEach(p=>{
                        const tmpP = products.find(sub => sub.id === p.id)
                        p.current_price = tmpP.current_price || 0
                        p.old_price = tmpP.old_price || 0
                    })
                }
            })

        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    getProductList({state, commit}) {
        state.items = localStorage.getItem('vuejs__store') == null ? [] : JSON.parse(localStorage.getItem('vuejs__store'))
        return state.items
    },
    addCollectionToCart({state, commit}, collection) {
        commit('pushCollectionToCart', collection);
    },
    addProductToCart({state, commit}, product) {

        commit('pushProductToCart', product);
    },
    setQuantity({state, commit}, prod) {
        commit('setItemQuantity', prod);
    },
    incQuantity({state, commit}, id) {
        console.log("incQuantity", id)
        commit('incrementItemQuantity', id);
    },
    decQuantity({state, commit}, id) {
        commit('decrementItemQuantity', id);
    },
    removeCollectionFromCart({state, commit}, id) {
        commit('removeItem', id);
    },

    removeProduct({state, commit}, id) {
        commit('removeItem', id);
    },
    clearCart({state, commit}) {
        commit('clearAllItems');
    }
}

// mutations
const mutations = {

    pushCollectionToCart(state, collection) {

        const cartItem = state.items.find(item => item.product.id === collection.id)
        if (!cartItem)
            state.items.push({
                product: collection,
                collection_id: collection.collection_id,
                type: 'collection',
                quantity: 1
            })
        else
            cartItem.quantity++;


        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },

    pushProductToCart(state, product) {
        const cartItem = state.items.find(item => item.product.id === product.id)
        if (!cartItem)
            state.items.push({
                product,
                type: 'product',
                quantity: 1
            })
        else
            cartItem.quantity++;


        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },


    incrementItemQuantity(state, id) {
        console.log("increment", id)
        const cartItem = state.items.find(item => item.product.id === id)
        cartItem.quantity++

        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },
    setItemQuantity(state, payload) {
        const cartItem = state.items.find(item => item.product.id === payload.id)
        cartItem.quantity = payload.quantity;
        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },

    decrementItemQuantity(state, id) {
        const cartItem = state.items.find(item => item.product.id === id)
        if (cartItem.quantity > 1)
            cartItem.quantity--;

        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },
    removeItem(state, id) {
        let tmp = state.items.filter((item) => item.product.id !== id);
        state.items = tmp

        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },

    clearAllItems(state) {
        state.items = []
        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },
    setCartItems(state, items) {
        state.items = items

        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },

}


const cardModule = {
    state,
    mutations,
    actions,
    getters
}
export default cardModule;

