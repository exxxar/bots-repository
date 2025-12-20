import products from './products'
import users from './users'
import events from './actions'
import util from "@/AdminPanel/store/modules/utilites";

let state = {
    ...products.state,
    ...users.state,
    ...events.state,
}

const getters = {
    ...products.getters,
    ...users.getters,
    ...events.getters,
}

const actions = {
    ...products.actions,
    ...users.actions,
    ...events.actions,



    async updateProductsFromVk(context) {
        let link = `/bot-client/vk-auth-link`

        let _axios = util.makeAxiosFactory(link, 'POST')

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}

const mutations = {
    ...products.mutations,
    ...users.mutations,
    ...events.mutations,
}

const shopAdminModule = {
    state,
    mutations,
    actions,
    getters
}
export default shopAdminModule;
