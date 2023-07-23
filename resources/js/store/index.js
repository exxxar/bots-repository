import {createStore} from 'vuex'

import cashBack from './modules/cashback';
import admins from './modules/admins';
import templates from './modules/templates';
import companies from './modules/companies';
import bots from './modules/bots';
import products from './modules/shop/products';
import pages from './modules/pages';
import dialogGroups from './modules/dialog_groups';
import slugs from './modules/slugs';
import wheelOfFortune from "@/store/modules/globals/wheelOfFortune";
import instagramQuest from "@/store/modules/globals/instagramQuest";
import cart from "@/store/modules/shop/cart";
import self from "@/store/modules/self";
import favorites from "@/store/modules/shop/favorites";
import shopAdmin from "@/store/modules/shop/admin";
import util from "@/store/modules/utilites";


export default createStore({
    state: {
        current_company: null,
        current_bot: null,
        errors: [],
    },
    getters: {
        getErrors: state => state.errors || [],
        getCurrentCompany: state => {
            let currentCompany = !localStorage.getItem('store_current_company') ?
                null : JSON.parse(localStorage.getItem('store_current_company'))

            return state.current_company || currentCompany || null
        },
        getCurrentBot: state => {
            let currentBot = !localStorage.getItem('store_current_bot') ?
                null : JSON.parse(localStorage.getItem('store_current_bot'))

            return state.current_bot || currentBot || null
        },
    },
    actions: {

        updateCurrentCompany(context, payload = {company: null}) {

            let currentCompany = !localStorage.getItem('store_current_company') ?
                null : JSON.parse(localStorage.getItem('store_current_company'))

            currentCompany = payload.company || currentCompany || null;

            context.commit("setCurrentCompany", currentCompany)
        },
        resetCurrentCompany(context) {
            context.commit("setCurrentCompany", null)
        },
        resetCurrentBot(context) {
            context.commit("setCurrentBot", null)
        },
        updateCurrentBot(context, payload = {bot: null}) {
            let currentBot = !localStorage.getItem('store_current_bot') ?
                null : JSON.parse(localStorage.getItem('store_current_bot'))

            currentBot = payload.bot || currentBot || null;

            context.commit("setCurrentBot", currentBot)
        },
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
        setCurrentCompany(state, payload) {
            state.current_company = payload || null;
            localStorage.setItem('store_current_company', JSON.stringify(payload));

            window.dispatchEvent(new CustomEvent('store_current_company-change-event'));
        },
        setCurrentBot(state, payload) {
            state.current_bot = payload || null;
            localStorage.setItem('store_current_bot', JSON.stringify(payload));

            window.dispatchEvent(new CustomEvent('store_current_bot-change-event'));
        },
        setErrors(state, payload) {
            state.errors = payload || [];
        },
    },
    modules: {
        cashBack,
        admins,
        templates,
        companies,
        bots,
        products,
        pages,
        dialogGroups,
        slugs,
        wheelOfFortune,
        instagramQuest,
        cart,
        self,
        favorites,
        shopAdmin
    }
})
