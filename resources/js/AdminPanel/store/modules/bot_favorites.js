import util from "./utilites";

const state = {
    bot_favorites: localStorage.getItem('cashman_bot_favorites') == null ? [] : JSON.parse(localStorage.getItem('cashman_bot_favorites')),
}

// getters
const getters = {
    inBotFav: (state) => (id) => {
        let item = state.bot_favorites.find(item => item == id)

        return item != null
    },
    getBotFavorites: (state, getters, rootState) => {
        return state.bot_favorites || [];
    },

}

// actions
const actions = {
    swapBotInFav({state, commit}, payload = {id: null, direction: 0}) {
        let favorites = state.bot_favorites;

        let itemIndex = favorites.findIndex(item => item == payload.id)

        console.log(favorites)

        let maxRows = favorites.length

        let index = payload.direction === 0 ?
            itemIndex - 1 >= 0 ? itemIndex - 1 : maxRows - 1 :
            itemIndex < maxRows - 1 ? itemIndex + 1 : 0

        let tmpRow = favorites[itemIndex]

        favorites[itemIndex] = favorites[index]
        favorites[index] = tmpRow

        commit('setBotFavoritesItems', favorites);

        return Promise.resolve();

    },
    loadBotFavs({state, commit}) {
        let favs = localStorage.getItem('cashman_bot_favorites') == null ? [] : JSON.parse(localStorage.getItem('cashman_bot_favorites'))
        commit('setBotFavoritesItems', favs);
    },
    addBotToFavorites({state, commit}, botId) {
        commit('pushBotToFav', botId);
    },
    removeFromBotFavorites({state, commit}, botId) {
        commit('removeBotFromFav', botId);
    },
    clearBotFavorites({state, commit}) {
        commit('clearAllBotFavorites');
    }
}

// mutations
const mutations = {

    pushBotToFav(state, botId) {
        const fatItem = state.bot_favorites.find(item => item === botId)
        if (!fatItem)
            state.bot_favorites.push(botId)

        localStorage.setItem('cashman_bot_favorites', JSON.stringify(state.bot_favorites));
    },
    removeBotFromFav(state, botId) {
        let tmp = state.bot_favorites.filter((item) => item !== botId);
        state.bot_favorites = tmp

        localStorage.setItem('cashman_bot_favorites', JSON.stringify(state.bot_favorites));
    },

    clearAllBotFavorites(state) {
        state.bot_favorites = []
        localStorage.setItem('cashman_bot_favorites', JSON.stringify(state.bot_favorites));
    },
    setBotFavoritesItems(state, bot_favorites) {
        state.bot_favorites = bot_favorites

        localStorage.setItem('cashman_bot_favorites', JSON.stringify(state.bot_favorites));
    },

}


const cardModule = {
    state,
    mutations,
    actions,
    getters
}
export default cardModule;

