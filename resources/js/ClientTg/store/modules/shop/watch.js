import util from "../utilites";

const state = {
    watches: localStorage.getItem('cashman_watches') == null ? [] : JSON.parse(localStorage.getItem('cashman_watches')),
}

// getters
const getters = {
    inWatch: (state) => (id) => {
        let item = state.watches.find(item => item.id === id)

        return item != null
    },
    getWatches: (state, getters, rootState) => {
        return state.watches;
    },
    watchesCount: (state, getters) => {
        return state.watches.length || 0
    },
}

// actions
const actions = {
    addToWatch({state, commit}, product) {

        commit('pushProductToWatch', product);
    },
}

// mutations
const mutations = {
    pushProductToWatch(state, product) {
        const fatItem = state.watches.find(item => item.id === product.id)
        if (!fatItem)
            state.watches.push(product)

        localStorage.setItem('cashman_watches', JSON.stringify(state.watches));
    },


}


const cardModule = {
    state,
    mutations,
    actions,
    getters
}
export default cardModule;

