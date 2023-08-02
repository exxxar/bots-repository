import util from "../utilites";

const state = {
    favorites: localStorage.getItem('cashman_favorites') == null ? [] : JSON.parse(localStorage.getItem('cashman_favorites')),
}

// getters
const getters = {
    inFav: (state) => (id) => {
        let item = state.favorites.find(item => item.id === id)

        return item != null
    },
    getFavorites: (state, getters, rootState) => {
        return state.favorites;
    },
    favoritesCount: (state, getters) => {
        return state.favorites.length || 0
    },
}

// actions
const actions = {
    async loadActualPriceInFav({state, commit}) {

        let ids = []
        state.favorites.forEach(item => {
            ids.push(item.id)
        })

        let data = util.loadActualProducts(ids)

        return data.then((response) => {
            let products = response;

            let tmp = []

            const favorites = state.favorites

            favorites.forEach(fav => {
                tmp.push(products.find(sub => sub.id === fav.id))
            })

            commit("setFavoritesItems", tmp)
        }).catch(err => {
            commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    addToFavorites({state, commit}, product) {
        commit('pushProductToFav', product);
    },
    removeFromFavorites({state, commit}, id) {
        commit('removeProductFromFav', id);
    },
    clearFavorites({state, commit}) {
        commit('clearAllFavorites');
    }
}

// mutations
const mutations = {

    pushProductToFav(state, product) {
        const fatItem = state.favorites.find(item => item.id === product.id)
        if (!fatItem)
            state.favorites.push(product)

        localStorage.setItem('cashman_favorites', JSON.stringify(state.favorites));
    },
    removeProductFromFav(state, id) {
        let tmp = state.favorites.filter((item) => item.id !== id);
        state.favorites = tmp

        localStorage.setItem('cashman_favorites', JSON.stringify(state.favorites));
    },

    clearAllFavorites(state) {
        state.favorites = []
        localStorage.setItem('cashman_favorites', JSON.stringify(state.favorites));
    },
    setFavoritesItems(state, favorites) {
        state.favorites = favorites

        localStorage.setItem('cashman_favorites', JSON.stringify(state.favorites));
    },

}


const cardModule = {
    state,
    mutations,
    actions,
    getters
}
export default cardModule;

