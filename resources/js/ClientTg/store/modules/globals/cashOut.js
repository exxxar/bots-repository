import util from '../utilites';
import axios from "axios";

const BASE_WHEEL_OF_FORTUNE_LINK = '/bot-client/cash-out'

let state = {}

const getters = {}

const actions = {
    async withDrawMoney(context, payload = {withDrawMoneyForm: null}) {

        let link = `${BASE_WHEEL_OF_FORTUNE_LINK}/withdraw-money`

        let _axios = util.makeAxiosFactory(link, 'POST', payload.withDrawMoneyForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {}

const wheelOfFortuneModule = {
    state,
    mutations,
    actions,
    getters
}
export default wheelOfFortuneModule;
