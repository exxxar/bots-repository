import util from './utilites';

const BASE_PROMOCODES_LINK = '/bot-client/promocodes'

let state = {

}

const getters = {

}

const actions = {
    async activatePromocode(context, payload= {promocodeForm: null}){
        let link = `${BASE_PROMOCODES_LINK}/activate`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.promocodeForm)
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async activateShopDiscountPromocode(context, payload= {promocodeForm: null}){
        let link = `${BASE_PROMOCODES_LINK}/activate-shop-discount`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.promocodeForm)
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },



}
const mutations = {


}

const promocodesModule = {
    state,
    mutations,
    actions,
    getters
}
export default promocodesModule;
