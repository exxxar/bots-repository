import {createStore} from 'vuex'

import util from "./modules/utilites";



export default createStore({
    state: {
        errors: [],
    },
    getters: {
        getErrors: state => state.errors || [],
    },
    actions: {
        async loadPageById(context, payload = {
            id: null
        }) {

            let link = `/s/pages/${payload.id}`
            let _axios = util.makeAxiosFactory(link, 'POST')

            return _axios.then((response) => {
                let dataObject = response.data
                return Promise.resolve(dataObject);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
    },
    mutations: {
        setErrors(state, payload) {
            state.errors = payload || [];
        },
    },
    modules: {

    }
})
