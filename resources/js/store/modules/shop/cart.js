import util from "@/store/modules/utilites";

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
            sum += item.product.current_price * item.quantity
        });
        return sum
    }
}

// actions
const actions = {
    async loadActualPriceInCart(context) {

        let ids = []
        context.state.items.forEach(item => {
            ids.push(item.product.id)
        })

        let data = util.loadActualProducts(ids)

        return data.then((response) => {
            let products = response;

            context.state.items.forEach(item => {
                item.product = products.find(sub => sub.id === item.product.id)
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
    addProductToCart({state, commit}, product) {
        commit('pushProductToCart', product);
    },
    setQuantity({state, commit}, prod) {
        commit('setItemQuantity', prod);
    },
    incQuantity({state, commit}, id) {
        commit('incrementItemQuantity', id);
    },
    decQuantity({state, commit}, id) {
        commit('decrementItemQuantity', id);
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

    pushProductToCart(state, product) {
        const cartItem = state.items.find(item => item.product.id === product.id)
        if (!cartItem)
            state.items.push({
                product,
                quantity: 1
            })
        else
            cartItem.quantity++;

        localStorage.setItem('cashman_basket', JSON.stringify(state.items));
    },


    incrementItemQuantity(state, id) {
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

