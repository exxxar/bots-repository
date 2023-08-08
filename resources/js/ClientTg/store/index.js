import {createStore} from 'vuex'

import cashBack from './modules/cashback';
import admins from './modules/admins';

import products from './modules/shop/products';

import wheelOfFortune from "./modules/globals/wheelOfFortune";
import bonusProduct from "./modules/globals/bonusProduct";
import instagramQuest from "./modules/globals/instagramQuest";
import cart from "./modules/shop/cart";
import self from "./modules/self";
import favorites from "./modules/shop/favorites";
import shopAdmin from "./modules/shop/admin";

import util from "./modules/utilites";



export default createStore({
    state: {
        errors: [],
    },
    getters: {
        getErrors: state => state.errors || [],
    },
    actions: {
        async callbackForm(context, payload = {callbackForm: null}) {

            let botDomain = window.currentBot.bot_domain || null
            let slugId = window.currentScript || null
            let telegramChatId = window.self.telegram_chat_id || null

            let callbackForm = payload.callbackForm

            callbackForm.append("telegram_chat_id", telegramChatId)
            callbackForm.append("slug_id", slugId)
            callbackForm.append("bot_domain", botDomain)

            let link = `/global-scripts/callback`

            let _axios = util.makeAxiosFactory(link, 'POST', callbackForm)

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
        cashBack,
        admins,
        products,
        wheelOfFortune,
        instagramQuest,
        cart,
        self,
        favorites,
        shopAdmin,
        bonusProduct
    }
})
