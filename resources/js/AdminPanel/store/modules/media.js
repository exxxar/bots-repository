import util from './utilites';

const BASE_MEDIA_LINK = '/admin/media'

let state = {
    media: [],
    media_paginate_object: null,
}

const getters = {
    getMedia: state => state.media || [],
    getMediaPaginateObject: state => state.media_paginate_object || null,
}

const actions = {

    async showMediaPreview(context, payload= {dataObject: {mediaId: null}}){
        let link = `${BASE_MEDIA_LINK}/preview/${payload.dataObject.mediaId}`
        let _axios = util.makeAxiosFactory(link, 'GET')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadMedia(context, payload = {dataObject: {
            bot_id:null,
            search:null,
            needPhoto:false,
            needVideo:false,
            needVideoNote:false,
            needAudio:false,
            needDocument:false,
        }, page: 0, size: 12})
    {

        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_MEDIA_LINK}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data

            context.commit("setMedia", dataObject.data)
            delete dataObject.data
            context.commit('setMediaPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async removeMedia(context, payload= {dataObject: {mediaId: null}}){
        let link = `${BASE_MEDIA_LINK}/remove/${payload.dataObject.mediaId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setMedia(state, payload) {
        state.media = payload || [];
        localStorage.setItem('cashman_media', JSON.stringify(payload));
    },
    setMediaPaginateObject(state, payload) {
        state.media_paginate_object = payload || [];
        localStorage.setItem('cashman_media_paginate_object', JSON.stringify(payload));
    },
}

const mediaModule = {
    state,
    mutations,
    actions,
    getters
}
export default mediaModule;
