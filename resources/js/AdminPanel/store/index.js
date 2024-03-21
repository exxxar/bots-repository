import {createStore} from 'vuex'

import templates from './modules/templates';
import companies from './modules/companies';
import bots from './modules/bots';
import botFavorites from './modules/bot_favorites';
import pages from './modules/pages';
import dialogGroups from './modules/dialog_groups';
import slugs from './modules/slugs';
import products from './modules/products';
import media from './modules/media';
import appointments from './modules/appointments';
import quizzes from './modules/quiz';
import promoCodes from './modules/promocodes';
import inlineQueries from './modules/inline_queries';
import util from "@/AdminPanel/store/modules/utilites";


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
                window.currentBot : JSON.parse(localStorage.getItem('store_current_bot'))

            return state.current_bot || currentBot || null
        },

    },
    actions: {
        async saveManager(context, payload) {

            let link = `/admin/manager/register`

            let _axios = util.makeAxiosFactory(link, 'POST', payload)

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },


        async sendToQueue(context, payload = {mailForm: null}) {

            let link = `/send-to-queue`

            let _axios = util.makeAxiosFactory(link, 'POST', payload.mailForm)

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
        async sendToChannel(context, payload = {mailForm: null}) {

            let link = `/send-to-channel`

            let _axios = util.makeAxiosFactory(link, 'POST', payload.mailForm)

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
        async removeFile(context, payload = {file_path: null}) {

            let link = `/remove-file`

            let _axios = util.makeAxiosFactory(link, 'POST', {
                ...payload
            })

            return _axios.then((response) => {
                return Promise.resolve(response.data);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
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
        async updateProductsFromVk(context,payload = {dataObject:{bot_domain:null}}) {
            let link = `/admin/vk-auth-link`

            let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)

            return _axios.then((response) => {
                return Promise.resolve(response);
            }).catch(err => {
                context.commit("setErrors", err.response.data.errors || [])
                return Promise.reject(err);
            })
        },
    },
    mutations: {
        setCurrentBotUser(state, payload) {
            state.current_bot_user = payload || null;
            localStorage.setItem('store_current_bot_user', JSON.stringify(payload));
        },
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
        templates,
        appointments,
        companies,
        bots,
        botFavorites,
        pages,
        dialogGroups,
        slugs,
        products,
        quizzes,
        media,
        inlineQueries,
        promoCodes
    }
})
