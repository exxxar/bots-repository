import {createStore} from 'vuex'

import cashBack from './modules/cashback';
import admins from './modules/admins';
import templates from './modules/templates';
import companies from './modules/companies';
import bots from './modules/bots';
import products from './modules/products';
import pages from './modules/pages';


export default createStore({
    state: {
        errors: [],
    },
    getters: {
        getErrors: state => state.errors || [],
    },
    actions: {

    },
    mutations: {
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
        pages
    }
})
