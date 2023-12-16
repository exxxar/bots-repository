import {createStore} from 'vuex'


import self from "./modules/self";
import bots from "./modules/bots";


import util from "./modules/utilites";



export default createStore({
    state: {
        errors: [],
    },
    getters: {
        getErrors: state => state.errors || [],
    },
    actions: {
        async sendToChannel(context, payload = {mailForm: null}) {

            let link = `/landing/send-to-channel`

            let _axios = util.makeAxiosFactory(link, 'POST', payload.mailForm)

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        }
    },
    mutations: {
        setErrors(state, payload) {
            state.errors = payload || [];
        },
    },
    modules: {
        self,
        bots
    }
})
