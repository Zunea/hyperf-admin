import Vue from 'vue'
import Vuex from 'vuex'
import app from './modules/app'
import user from './modules/user'
import i18n from './modules/i18n'
import router from './modules/router'
import tagsView from './modules/tagsView'
import getters from './getters'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        app,
        user,
        router,
        i18n,
        tagsView
    },
    state: {},
    mutations: {},
    actions: {},
    getters
})
