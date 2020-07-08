import Vue from 'vue'
import {
    DEFAULT_MULTI_TAB
} from '@/configs/constants'

const app = {
    state: {
        multiTab: false,
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
        }
    },
    actions: {
        ToggleMultiTab ({ commit }, bool) {
            commit('TOGGLE_MULTI_TAB', bool)
        },
        MessageTake ({ commit }, bool) {
            commit('MESSAGE_TAKE', bool)
        }
    }
}

export default app
