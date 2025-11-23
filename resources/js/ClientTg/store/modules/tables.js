import util from './utilites';

const BASE_TABLES_LINK = '/bot-client/tables'

let state = {
    tables: [],
    tables_paginate_object: null,
}

const getters = {
    getTables: state => state.tables || [],
    getTablesPaginateObject: state => state.tables_paginate_object || null,
}

const actions = {
    async loadApprovedSelfTableBasket(context) {
        let link = `${BASE_TABLES_LINK}/approved-self-basket`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async nearestBookingList(context, payload = {start_date: null, end_date: null}) {
        let link = `${BASE_TABLES_LINK}/nearest-booking-list`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async myUpcomingBookings(context) {
        let link = `${BASE_TABLES_LINK}/my-upcoming-bookings`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async bookingList(context,payload = {date: null, number: null}) {
        let link = `${BASE_TABLES_LINK}/booking-list`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


    async bookATable(context,payload = {dataObject: {name: null, phone: null,persons:1,description:null,table:null }}) {
        let link = `${BASE_TABLES_LINK}/book-table`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async exportNearestBookings(context,payload = {start_date: null, end_date: null}) {
        let link = `${BASE_TABLES_LINK}/export-nearest-bookings`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async cancelBookingTable(context,payload = {bookingId:null}) {
        let link = `${BASE_TABLES_LINK}/cancel-booking/${payload.bookingId}`
        let method = 'DELETE'

        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storeTableAdditionalServices(context, payload = {dataObject: null}) {
        let link = `${BASE_TABLES_LINK}/store-additional-service`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async acceptTableOder(context, payload = {dataObject: {table_id: null, type: 0}}) {
        let link = `${BASE_TABLES_LINK}/accept-table-order`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async requestApproveTable(context, payload = {dataObject: {table_id: null}}) {
        let link = `${BASE_TABLES_LINK}/request-approve-table`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async startTablePay(context, payload) {
        let link = `${BASE_TABLES_LINK}/table-pay`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


    async sendOrderToMyChat(context, payload = {dataObject: {table_id: null}}) {
        let link = `${BASE_TABLES_LINK}/send-order-to-my-chat`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async requestWaiterComing(context, payload = {dataObject: {table_id: null}}) {
        let link = `${BASE_TABLES_LINK}/call-waiter`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async changeTableWaiter(context, payload = {dataObject: {table_id: null}}) {
        let link = `${BASE_TABLES_LINK}/change-table-waiter`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async closeTableOrder(context, payload = {dataObject: {table_id: null}}) {
        let link = `${BASE_TABLES_LINK}/close-table`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadTableData(context, payload = {dataObject: {table_id: null}}) {
        let link = `${BASE_TABLES_LINK}/table-data`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method, payload.dataObject)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadCurrentTableData(context) {
        let link = `${BASE_TABLES_LINK}/current`
        let method = 'POST'

        let _axios = util.makeAxiosFactory(link, method)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadTables(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_TABLES_LINK}/waiter-tables?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setTables", dataObject.data)
            delete dataObject.data
            context.commit('setTablesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storeTables(context, payload = {promoCodeForm: null}) {
        let link = `${BASE_TABLES_LINK}/store`

        let _axios = util.makeAxiosFactory(link, "POST", payload.promoCodeForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeTables(context, payload = {promoCodeId: null}) {
        let link = `${BASE_TABLES_LINK}/${payload.promoCodeId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },


}
const mutations = {

    setTables(state, payload) {
        state.tables = payload || [];
        localStorage.setItem('cashman_tables', JSON.stringify(payload));
    },

    setTablesPaginateObject(state, payload) {
        state.tables_paginate_object = payload || [];
        localStorage.setItem('cashman_tables_paginate_object', JSON.stringify(payload));
    },

}

const tablesModule = {
    state,
    mutations,
    actions,
    getters
}
export default tablesModule;
