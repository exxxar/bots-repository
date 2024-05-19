import util from './utilites';

const BASE_PROVIDERS_LINK = '/bot-client/payments'

let state = {
    providers: [],
}

const getters = {
    getProviders: state => state.providers || [],
}

const actions = {

    async loadProviders(context) {
        let link = `${BASE_PROVIDERS_LINK}/providers`
        let method = 'GET'
        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setProviders", dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}
const mutations = {
    setProviders(state, payload) {
        state.providers = payload || [];
        localStorage.setItem('cashman_providers', JSON.stringify(payload));
    },

}

const paymentsModule = {
    state,
    mutations,
    actions,
    getters
}
export default paymentsModule;
