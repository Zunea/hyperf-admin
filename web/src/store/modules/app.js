import Vue from 'vue'
import {
    DEFAULT_MULTI_TAB,
    DEFAULT_THEME_TYPE
} from '@/config/constants'

const app = {
    state: {
        multiTab: false,
        themeType: 'light',
        messageTake: false,
        baseURL: process.env.API_BASE_URL
    },
    mutations: {
        TOGGLE_MULTI_TAB: (state, bool) => {
            Vue.ls.set(DEFAULT_MULTI_TAB, bool)
            state.multiTab = bool
        },
        MESSAGE_TAKE: (state, bool) => {
            state.messageTake = bool
        },
        THEME_TYPE: (state, data) => {
            Vue.ls.set(DEFAULT_THEME_TYPE, data)
            state.themeType = data
        }
    },
    actions: {
        ToggleMultiTab ({ commit }, bool) {
            if (bool !== null) {
                commit('TOGGLE_MULTI_TAB', bool)
            }
        },
        MessageTake ({ commit }, bool) {
            commit('MESSAGE_TAKE', bool)
        },
        ToggleThemeType  ({ commit }, data) {
            if (data !== null) {
                commit('THEME_TYPE', data)
            }
        }
    }
}

export default app
