import util from './utilites'

const BASE_STORY_LINK = '/bot-client/stories'

let state = {
    stories: [],
    stories_paginate_object: null,
}

const getters = {
    getStories: state => state.stories || [],
    getStoryById: (state) => (id) => {
        return state.stories.find(item => item.id === id)
    },
    getStoriesPaginateObject: state => state.stories_paginate_object || null,
}

const actions = {
    async loadStories(context, payload = { page: 1, size: 20 }) {
        let page = payload.page || 1
        let size = payload.size || 20

        let link = `${BASE_STORY_LINK}?page=${page}&size=${size}`
        let _axios = util.makeAxiosFactory(link, 'GET')

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setStories", dataObject.data)
            delete dataObject.data
            context.commit('setStoriesPaginateObject', dataObject)
            return Promise.resolve()
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err)
        })
    },

    async fetchStory(context, payload = { id: null }) {
        let link = `${BASE_STORY_LINK}/${payload.id}`
        let _axios = util.makeAxiosFactory(link, 'GET')
        return _axios.then(response => {
            return Promise.resolve(response.data)
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err)
        })
    },

    async saveStory(context, payload = { storyForm: {} }) {
        let _axios = util.makeAxiosFactory(BASE_STORY_LINK, 'POST', payload.storyForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data)
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err)
        })
    },

    async deleteStory(context, payload = { id: null }) {
        let link = `${BASE_STORY_LINK}/${payload.id}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then(response => {
            return Promise.resolve(response.data)
        }).catch(err => {
            context.commit("setErrors", err.response?.data?.errors || [])
            return Promise.reject(err)
        })
    },
}

const mutations = {
    setStories(state, payload) {
        state.stories = payload || []
        localStorage.setItem('cashman_stories', JSON.stringify(payload))
    },
    setStoriesPaginateObject(state, payload) {
        state.stories_paginate_object = payload || null
        localStorage.setItem('cashman_stories_paginate_object', JSON.stringify(payload))
    }
}

const storiesModule = {

    state,
    getters,
    actions,
    mutations
}

export default storiesModule
