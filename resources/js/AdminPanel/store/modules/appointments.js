import util from './utilites';

const BASE_APPOINTMENTS_LINK = '/admin/appointments'

let state = {
    appointments: [],
    appointment_events: [],
    appointment_schedules: [],
    appointments_paginate_object: null,
    appointments_events_paginate_object: null,
    appointments_schedules_paginate_object: null,
}

const getters = {
    getAppointments: state => state.appointments || [],
    getAppointmentEvents: state => state.appointment_events || [],
    getAppointmentSchedules: state => state.appointment_schedules || [],
    getAppointmentsPaginateObject: state => state.appointments_paginate_object || null,
    getAppointmentEventsPaginateObject: state => state.appointments_events_paginate_object || null,
    getAppointmentSchedulesPaginateObject: state => state.appointments_schedules_paginate_object || null,
}

const actions = {
    async loadAppointmentEvents(context, payload = {dataObject: {botId: null, search:null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_APPOINTMENTS_LINK}/event-list?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setAppointmentEvents", dataObject.data)
            delete dataObject.data
            context.commit('setAppointmentEventsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async addAppointmentEvent(context, payload= {appointmentEventForm: null}){
        let link = `${BASE_APPOINTMENTS_LINK}/add-event`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.appointmentEventForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateAppointmentEvent(context, payload= {appointmentEventForm: null}){
        let link = `${BASE_APPOINTMENTS_LINK}/update-event`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.appointmentEventForm)
        return _axios.then((response) => {

            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async duplicateAppointmentEvent(context, payload= {dataObject: {appointmentEventId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/duplicate-event/${payload.dataObject.appointmentEventId}`
        let _axios = util.makeAxiosFactory(link, 'POST')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeAppointmentEvent(context, payload= {dataObject: {appointmentEventId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/remove-event/${payload.dataObject.appointmentEventId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async forceRemoveAppointment(context, payload= {dataObject: {appointmentEventId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/force-remove-event/${payload.dataObject.appointmentEventId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async restoreAppointmentEvent(context, payload= {dataObject: {appointmentEventId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/restore-event/${payload.dataObject.appointmentEventId}`
        let _axios = util.makeAxiosFactory(link, 'GET')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

}
const mutations = {
    setAppointments(state, payload) {
        state.appointments = payload || [];
        localStorage.setItem('cashman_appointments', JSON.stringify(payload));
    },
    setAppointmentEvents(state, payload) {
        state.appointment_events = payload || [];
        localStorage.setItem('cashman_appointment_events', JSON.stringify(payload));
    },
    setAppointmentSchedules(state, payload) {
        state.appointment_schedules = payload || [];
        localStorage.setItem('cashman_appointment_schedules', JSON.stringify(payload));
    },
    setAppointmentsPaginateObject(state, payload) {
        state.appointments_paginate_object = payload || [];
        localStorage.setItem('cashman_appointments_paginate_object', JSON.stringify(payload));
    },
    setAppointmentEventsPaginateObject(state, payload) {
        state.appointment_events_paginate_object = payload || [];
        localStorage.setItem('cashman_appointment_events_paginate_object', JSON.stringify(payload));
    },
    setAppointmentSchedulesPaginateObject(state, payload) {
        state.appointment_schedules_paginate_object = payload || [];
        localStorage.setItem('cashman_appointment_schedules_paginate_object', JSON.stringify(payload));
    }
}

const appointmentsModule = {
    state,
    mutations,
    actions,
    getters
}
export default appointmentsModule;
