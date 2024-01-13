import util from './utilites';


const BASE_QUIZ_LINK = '/admin/quizzes'

let state = {
    quizzes: [],
    quizzes_results: [],
    quiz_questions: [],
    quiz_commands: [],
    quizzes_paginate_object: null,
    quiz_questions_paginate_object: null,
    quiz_commands_paginate_object: null,
    quizzes_results_paginate_object: null,
}

const getters = {
    getQuizzes: state => state.quizzes || [],
    getQuizzesResults: state => state.quizzes_results || [],
    getQuizQuestions: state => state.quiz_questions || [],
    getQuizCommands: state => state.quiz_commands || [],
    getQuizById: (state) => (id) => {
        return state.quizzes.find(item => item.id === id)
    },
    getQuizzesPaginateObject: state => state.quizzes_paginate_object || null,
    getQuizzesResultsPaginateObject: state => state.quizzes_results_paginate_object || null,
    getQuizQuestionsPaginateObject: state => state.quiz_questions_paginate_object || null,
    getQuizCommandsPaginateObject: state => state.quiz_commands_paginate_object || null,
}

const actions = {


    async loadQuizCommands(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_QUIZ_LINK}/list-of-quiz-commands/${payload.dataObject.quiz_id}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setQuizCommands", dataObject.data)
            delete dataObject.data
            context.commit('setQuizCommandsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadQuizQuestions(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_QUIZ_LINK}/list-of-quiz-questions/${payload.dataObject.quiz_id}?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setQuizQuestions", dataObject.data)
            delete dataObject.data
            context.commit('setQuizQuestionsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadQuizzes(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_QUIZ_LINK}/list-of-quiz?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setQuizzes", dataObject.data)
            delete dataObject.data
            context.commit('setQuizzesPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async loadQuizzesResults(context, payload = {dataObject: null, page: 0, size: 50}) {
        let page = payload.page || 0
        let size = payload.size || 50

        let link = `${BASE_QUIZ_LINK}/list-of-results?page=${page}&size=${size}`
        let method = 'POST'
        let data = payload.dataObject

        let _axios = util.makeAxiosFactory(link, method, data)

        return _axios.then((response) => {
            let dataObject = response.data
            context.commit("setQuizzesResults", dataObject.data)
            delete dataObject.data
            context.commit('setQuizzesResultsPaginateObject', dataObject)
            return Promise.resolve();
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeQuizQuestion(context, payload = {quizQuestionForm: null}) {
        let link = `${BASE_QUIZ_LINK}/quiz-question-store`

        let _axios = util.makeAxiosFactory(link,"POST", payload.quizQuestionForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },

    async storeQuizCommand(context, payload = {quizCommandForm: null}) {
        let link = `${BASE_QUIZ_LINK}/quiz-command-store`

        let _axios = util.makeAxiosFactory(link,"POST", payload.quizCommandForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async storeQuiz(context, payload = {quizForm: null}) {
        let link = `${BASE_QUIZ_LINK}/quiz-store`

        let _axios = util.makeAxiosFactory(link,"POST", payload.quizForm)

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeQuiz(context, payload= {quizId: null}){
        let link = `${BASE_QUIZ_LINK}/remove-quiz/${payload.quizId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeQuizCommand(context, payload= {quizCommandId: null}){
        let link = `${BASE_QUIZ_LINK}/remove-quiz-command/${payload.quizCommandId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeQuizAnswer(context, payload= {quizAnswerId: null}){
        let link = `${BASE_QUIZ_LINK}/remove-quiz-answer/${payload.quizAnswerId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async removeQuizQuestion(context, payload= {quizQuestionId: null}){
        let link = `${BASE_QUIZ_LINK}/remove-quiz-question/${payload.quizQuestionId}`

        let _axios = util.makeAxiosFactory(link, 'DELETE')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
    async restoreQuiz(context, payload= {quizId: null}){
        let link = `${BASE_QUIZ_LINK}/restore-quiz/${payload.quizId}`

        let _axios = util.makeAxiosFactory(link, 'GET')

        return _axios.then((response) => {
            return Promise.resolve(response.data);
        }).catch(err => {
            context.commit("setErrors", err.response.data.errors || [])
            return Promise.reject(err);
        })
    },
}
const mutations = {
    setQuizzes(state, payload) {
        state.quizzes = payload || [];
        localStorage.setItem('cashman_quizzes', JSON.stringify(payload));
    },
    setQuizQuestions(state, payload) {
        state.quiz_questions = payload || [];
        localStorage.setItem('cashman_quiz_questions', JSON.stringify(payload));
    },
    setQuizCommands(state, payload) {
        state.quiz_commands = payload || [];
        localStorage.setItem('cashman_quiz_commands', JSON.stringify(payload));
    },
    setQuizzesResults(state, payload) {
        state.quizzes_results = payload || [];
        localStorage.setItem('cashman_quizzes_results', JSON.stringify(payload));
    },
    setQuizzesResultsPaginateObject(state, payload) {
        state.quizzes_results_paginate_object = payload || [];
        localStorage.setItem('cashman_quizzes_results_paginate_object', JSON.stringify(payload));
    },
    setQuizzesPaginateObject(state, payload) {
        state.quizzes_paginate_object = payload || [];
        localStorage.setItem('cashman_quizzes_paginate_object', JSON.stringify(payload));
    },
    setQuizQuestionsPaginateObject(state, payload) {
        state.quiz_questions_paginate_object = payload || [];
        localStorage.setItem('cashman_quiz_questions_paginate_object', JSON.stringify(payload));
    },
    setQuizCommandsPaginateObject(state, payload) {
        state.quiz_commands_paginate_object = payload || [];
        localStorage.setItem('cashman_quiz_commands_paginate_object', JSON.stringify(payload));
    }
}

const quizModule = {
    state,
    mutations,
    actions,
    getters
}
export default quizModule;
