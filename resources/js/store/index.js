import {createStore} from 'vuex'

import cashBack from './modules/cashback';
import admins from './modules/admins';
import templates from './modules/templates';
import companies from './modules/companies';
import bots from './modules/bots';
import products from './modules/products';
import pages from './modules/pages';
import dialogGroups from './modules/dialog_groups';
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

            return state.current_company || currentCompany|| null
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
        updateCurrentBot(context, payload = {bot: null}) {
            let currentBot = !localStorage.getItem('store_current_bot') ?
                null : JSON.parse(localStorage.getItem('store_current_bot'))

            currentBot = payload.bot || currentBot || null;

            context.commit("setCurrentBot", currentBot)
        }
    },
    mutations: {
        setCurrentCompany(state, payload) {
            state.current_company = payload || null;
            localStorage.setItem('store_current_company', JSON.stringify(payload));
        },
        setCurrentBot(state, payload) {
            state.current_bot = payload || null;
            localStorage.setItem('store_current_bot', JSON.stringify(payload));
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
        dialogGroups
    }
})
