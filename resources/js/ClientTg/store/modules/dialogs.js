import util from './utilites';

const BASE_DIALOGS_LINK = '/bot-client/dialogs'

let state = {
    dialogs: [],
    dialogs_paginate_object: null,
}

const getters = {
    getDialogs: state => state.dialogs || [],
    getDialogCommands: state => state.dialogs || [],
    getDialogsPaginateObject: state => state.dialogs_paginate_object || null,
}

const actions = {
    async loadDialogs(context, payload = {dataObject: {search:null, simple: false}, page: 0, size: 12}) {

        let data = {
            ...payload.dataObject
        }

        let page = payload.page || 0
        let size = 12

        let link = `${BASE_DIALOGS_LINK}?page=${page}&size=${size}`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setDialogs", dataObject.data)
            delete dataObject.data
            context.commit('setDialogsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async updateDialogGroup(context, payload= {dialogGroupForm: null}){
        let link = `${BASE_DIALOG_GROUPS_LINK}/update-group`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.dialogGroupForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateDialogCommand(context, payload= {dialogCommandForm: null}){
        let link = `${BASE_DIALOG_GROUPS_LINK}/update-dialog`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.dialogCommandForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeDialogGroup(context, payload= {dataObject: {dialogGroupId: null}}){
        let link = `${BASE_DIALOG_GROUPS_LINK}/remove-group/${payload.dataObject.dialogGroupId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeDialogCommand(context, payload= {dataObject: {dialogCommandId: null}}){
        let link = `${BASE_DIALOG_GROUPS_LINK}/remove-dialog/${payload.dataObject.dialogCommandId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createDialogGroup(context, payload = {dialogGroupForm: null}) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/add-group`

        let _axios = util.makeAxiosFactory(link,"POST", payload.dialogGroupForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async swapDialogCommand(context, payload = {swapForm: null}) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/swap-dialog`

        let _axios = util.makeAxiosFactory(link,"POST", payload.swapForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async unlinkDialogCommand(context, payload = {dataObject: null}) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/unlink-dialog`

        let _axios = util.makeAxiosFactory(link,"POST", payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async swapDialogGroup(context, payload = {swapForm: null}) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/swap-group`

        let _axios = util.makeAxiosFactory(link,"POST", payload.swapForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async stopDialogs(context) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/stop-dialogs`

        let _axios = util.makeAxiosFactory(link,"POST")

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async createDialogCommand(context, payload = {dialogCommandForm: null}) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/add-dialog`

        let _axios = util.makeAxiosFactory(link,"POST", payload.dialogCommandForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async duplicateDialogCommand(context, payload = {dataObject: null}) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/duplicate-dialog`

        let _axios = util.makeAxiosFactory(link,"POST", payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async attachDialogCommandToSlug(context, payload = {dataObject: null}) {
        let link = `${BASE_DIALOG_GROUPS_LINK}/attach-dialog-to-slug`

        let _axios = util.makeAxiosFactory(link,"POST", payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setDialogs(state, payload) {
        state.dialogs = payload || [];
        localStorage.setItem('cashman_dialogs', JSON.stringify(payload));
    },
    setDialogsPaginateObject(state, payload) {
        state.dialogs_paginate_object = payload || [];
        localStorage.setItem('cashman_dialogs_paginate_object', JSON.stringify(payload));
    }
}

const dialogGroupsModule = {
    state,
    mutations,
    actions,
    getters
}
export default dialogGroupsModule;
