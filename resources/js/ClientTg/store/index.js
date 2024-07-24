import {createStore} from 'vuex'

import cashBack from './modules/cashback';
import admins from './modules/admins';

import products from './modules/shop/products';
import ingredients from './modules/ingrediens';

import wheelOfFortune from "./modules/globals/wheelOfFortune";
import chatHistory from "./modules/chat_history";
import wheelOfFortuneCustom from "./modules/globals/wheelOfFortuneCustom";
import friendsGame from "./modules/globals/friendsGame";
import bonusProduct from "./modules/globals/bonusProduct";
import instagramQuest from "./modules/globals/instagramQuest";
import schedule from "./modules/globals/schedule";
import cashOut from "./modules/globals/cashOut";
import cart from "./modules/shop/cart";
import self from "./modules/self";
import favorites from "./modules/shop/favorites";
import shopAdmin from "./modules/shop/admin";
import company from "./modules/company";
import watches from "./modules/shop/watch";
import pages from "./modules/pages";
import bots from "./modules/bots";
import dialogs from "./modules/dialogs";
import slugs from "./modules/slugs";
import botUsers from "./modules/bot_users";
import media from "./modules/media";
import quiz from "./modules/quiz";
import profileForm from "./modules/profile";
import appointments from "./modules/appointments";
import payments from "./modules/payment";
import promocodes from "./modules/promocodes";
import orders from "./modules/shop/orders"
import reviews from "./modules/shop/reviews"
import mailing from "./modules/mailing"

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

            let link = `/bot-client/send-to-channel`

            let _axios = util.makeAxiosFactory(link, 'POST', payload.mailForm)

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
        async loadNotes(context){


            let link = `/bot-client/manager-notes`

            let _axios = util.makeAxiosFactory(link,'POST')

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
        async requestTelegramChannelId(context, payload = {dataObject:null}) {



            let data = {
                ...payload.dataObject
            }
            let link = `/bot-client/telegram-channel-id`

            let _axios = util.makeAxiosFactory(link,'POST',data)

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
        async feedBackForm(context, payload = {callbackForm: null}) {
            let callbackForm = payload.callbackForm

            let link = `/bot-client/feedback`

            let _axios = util.makeAxiosFactory(link, 'POST', callbackForm)

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
        async callbackForm(context, payload = {callbackForm: null}) {
            let callbackForm = payload.callbackForm

            let link = `/bot-client/callback`

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
        wheelOfFortuneCustom,
        instagramQuest,
        schedule,
        cashOut,
        cart,
        self,
        favorites,
        shopAdmin,
        bonusProduct,
        watches,
        company,
        pages,
        bots,
        dialogs,
        slugs,
        botUsers,
        media,
        ingredients,
        quiz,
        chatHistory,
        profileForm,
        appointments,
        payments,
        promocodes,
        orders,
        friendsGame,
        mailing,
        reviews
    }
})
