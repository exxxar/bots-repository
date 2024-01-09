import util from './utilites';

const BASE_APPOINTMENTS_LINK = '/admin/appointments'

let state = {
    appointments: [],
    appointment_events: [],
    appointment_services: [],
    appointment_schedules: [],
    appointment_reviews: [],
    appointment_paginate_object: null,
    appointment_services_paginate_object: null,
    appointment_events_paginate_object: null,
    appointment_schedules_paginate_object: null,
    appointment_reviews_paginate_object: null,
}

const getters = {
    getAppointments: state => state.appointments || [],
    getAppointmentEvents: state => state.appointment_events || [],
    getAppointmentServices: state => state.appointment_services || [],
    getAppointmentSchedules: state => state.appointment_schedules || [],
    getAppointmentReviews: state => state.appointment_reviews || [],
    getAppointmentsPaginateObject: state => state.appointment_paginate_object || null,
    getAppointmentEventsPaginateObject: state => state.appointment_events_paginate_object || null,
    getAppointmentSchedulesPaginateObject: state => state.appointment_schedules_paginate_object || null,
    getAppointmentServicesPaginateObject: state => state.appointment_services_paginate_object || null,
    getAppointmentReviewsPaginateObject: state => state.appointment_reviews_paginate_object || null,
}

const actions = {
    async loadAppointments(context, payload = {dataObject: {search:null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_APPOINTMENTS_LINK}/appointment-list/${payload.dataObject.event_id}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setAppointments", dataObject.data)
            delete dataObject.data
            context.commit('setAppointmentsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadAppointmentEvents(context, payload = {dataObject: {search:null}, page: 0, size: 12}) {
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
    async loadAppointmentReviews(context, payload = {dataObject: {search:null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_APPOINTMENTS_LINK}/review-list/${payload.dataObject.event_id}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setAppointmentReviews", dataObject.data)
            delete dataObject.data
            context.commit('setAppointmentReviewsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadAppointmentSchedules(context, payload = {dataObject: {event_id:null}, page:0, size:10}) {
        let link = `${BASE_APPOINTMENTS_LINK}/schedule-list/${payload.dataObject.event_id}?page=${payload.page || 0}&size=${payload.size || 10}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setAppointmentSchedules", dataObject.data)
            delete dataObject.data
            context.commit('setAppointmentSchedulesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadAppointmentServices(context, payload = {dataObject: {event_id:null, search:null}, page: 0, size: 12}) {
        let page = payload.page || 0
        let size = payload.size || 12

        let link = `${BASE_APPOINTMENTS_LINK}/service-list/${payload.dataObject.event_id}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setAppointmentServices", dataObject.data)
            delete dataObject.data
            context.commit('setAppointmentServicesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadAppointmentServiceCategories(context, payload = {dataObject: {event_id:null}}) {


        let link = `${BASE_APPOINTMENTS_LINK}/service-category-list/${payload.dataObject.event_id}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            return Promise.resolve(dataObject);
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
    async addAppointmentService(context, payload= {appointmentServiceForm: null}){
        let link = `${BASE_APPOINTMENTS_LINK}/add-service`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.appointmentServiceForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeAppointment(context, payload= {dataObject: { appointmentForm:null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/store-appointment`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject.appointmentForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeAppointmentSchedule(context, payload= {dataObject: { schedule:null, bot_id:null, appointment_event_id:null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/store-schedule`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeAppointmentReview(context, payload= {dataObject: { appointmentReviewForm:null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/store-review`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.dataObject.appointmentReviewForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateAppointmentService(context, payload= {appointmentServiceForm: null}){
        let link = `${BASE_APPOINTMENTS_LINK}/update-service`
        let _axios = util.makeAxiosFactory(link, 'POST', payload.appointmentServiceForm)
        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async updateAppointmentEvent(context, payload= {appointmentEventForm: null}){
        let link = `${BASE_APPOINTMENTS_LINK}/update-event`
        let _axios = util.makeAxiosFactory(link, 'post', payload.appointmentEventForm)
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
    async removeAppointment(context, payload= {dataObject: {appointmentId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/remove-appointment/${payload.dataObject.appointmentId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeAppointmentReview(context, payload= {dataObject: {appointmentReviewId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/remove-review/${payload.dataObject.appointmentReviewId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
        return _axios.then((response) => {
            return Promise.resolve(response);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async removeAppointmentSchedule(context, payload= {dataObject: {appointmentScheduleId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/remove-schedule/${payload.dataObject.appointmentScheduleId}`
        let _axios = util.makeAxiosFactory(link, 'DELETE')
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
    async removeAppointmentService(context, payload= {dataObject: {appointmentServiceId: null}}){
        let link = `${BASE_APPOINTMENTS_LINK}/remove-service/${payload.dataObject.appointmentServiceId}`
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
    setAppointmentServices(state, payload) {
        state.appointment_services = payload || [];
        localStorage.setItem('cashman_appointment_services', JSON.stringify(payload));
    },
    setAppointmentReviews(state, payload) {
        state.appointment_reviews = payload || [];
        localStorage.setItem('cashman_appointment_reviews', JSON.stringify(payload));
    },
    setAppointmentSchedules(state, payload) {
        state.appointment_schedules = payload || [];
        localStorage.setItem('cashman_appointment_schedules', JSON.stringify(payload));
    },
    setAppointmentsPaginateObject(state, payload) {
        state.appointments_paginate_object = payload || [];
        localStorage.setItem('cashman_appointments_paginate_object', JSON.stringify(payload));
    },
    setAppointmentReviewsPaginateObject(state, payload) {
        state.appointment_reviews_paginate_object = payload || [];
        localStorage.setItem('cashman_appointment_reviews_paginate_object', JSON.stringify(payload));
    },
    setAppointmentEventsPaginateObject(state, payload) {
        state.appointment_events_paginate_object = payload || [];
        localStorage.setItem('cashman_appointment_events_paginate_object', JSON.stringify(payload));
    },
    setAppointmentServicesPaginateObject(state, payload) {
        state.appointment_services_paginate_object = payload || [];
        localStorage.setItem('cashman_appointment_services_paginate_object', JSON.stringify(payload));
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
