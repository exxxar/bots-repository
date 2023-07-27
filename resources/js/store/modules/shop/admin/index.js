import products from './products'
import util from "@/store/modules/utilites";

const BASE_SHOP_LINK = '/global-scripts/shop'

let state = {
...products.state
}

const getters = {
    ...products.getters
}

const actions = {
    ...products.actions,
    async updateProductsFromVk(context, payload = {dataObject: {botDomain: null, url: null}}) {
        let link = `${BASE_SHOP_LINK}/vk-auth-link`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}

const mutations = {
    ...products.mutations
}

const shopAdminModule = {
    state,
    mutations,
    actions,
    getters
}
export default shopAdminModule;
