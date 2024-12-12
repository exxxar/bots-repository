import util from './utilites';
import axios from "axios";

const BASE_FRIENDS_LINK = '/bot-client/friends'

let state = {
    friends: [],
    friends_paginate_object: null,
}

const getters = {
    getFriends: state => state.friends || [],
    getFriendsPaginateObject: state => state.friends_paginate_object || null,
}

const actions = {

    async loadFriends(context, payload ={ page: 0, size: 12}) {
        let page = payload.page || 0
        let size = 12

        let link = `${BASE_FRIENDS_LINK}/?page=${page}&size=${size}`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setFriends", dataObject.data)
            delete dataObject.data
            context.commit('setFriendsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}
const mutations = {
    setFriends(state, payload) {
        state.friends = payload || [];
        localStorage.setItem('cashman_friends', JSON.stringify(payload));
    },
    setFriendsPaginateObject(state, payload) {
        state.friends_paginate_object = payload || [];
        localStorage.setItem('cashman_friends_paginate_object', JSON.stringify(payload));
    }
}

const myFriendsModule = {
    state,
    mutations,
    actions,
    getters
}
export default myFriendsModule;
