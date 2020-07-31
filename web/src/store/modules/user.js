import Vue from 'vue'
import { login } from '@/api/default/auth'
import { changePassword, getInfo, getOption /* , logout */ } from '@/api/default/account'
import { ACCESS_TOKEN } from '@/config/constants'

const user = {
    state: {
        token: '',
        info: {},
        options: {
            AdminGroupList: [],
            AdminConfigTypeList: [],
            AdminConfigGroup: []
        }
    },
    mutations: {
        // 设置Token
        SET_TOKEN: (state, token) => {
            state.token = token
        },
        // 设置用户信息
        SET_INFO: (state, info) => {
            state.info = info
        },
        // 设置选项列表
        SET_OPTIONS: (state, options) => {
            state.options = options
        }
    },

    actions: {
        // 登录
        Login ({ commit }, userInfo) {
            return new Promise((resolve, reject) => {
                login(userInfo).then((response) => {
                    const result = response.result
                    Vue.ls.set(ACCESS_TOKEN, result.token, 7 * 24 * 60 * 60 * 1000)
                    commit('SET_TOKEN', result.token)
                    resolve(result)
                }).catch((error) => {
                    reject(error)
                })
            })
        },
        // 获取用户信息
        GetInfo ({ commit }) {
            return new Promise((resolve, reject) => {
                getInfo().then((response) => {
                    const result = response.result || {}
                    commit('SET_INFO', result)
                    this.dispatch('GetOptions')
                    resolve(response)
                }).catch((error) => {
                    reject(error)
                })
            })
        },
        // 登出
        Logout ({ commit /* , state */ }) {
            commit('SET_TOKEN', '')
            commit('SET_INFO', {})
            Vue.ls.remove(ACCESS_TOKEN)
            /* return new Promise((resolve) => {
                logout(state.token).then(() => {
                    resolve()
                }).catch(() => {
                    resolve()
                }).finally(() => {
                    commit('SET_TOKEN', '')
                    commit('SET_INFO', {})
                    Vue.ls.remove(ACCESS_TOKEN)
                })
            }) */
        },
        // 获取选项
        GetOptions ({ commit }) {
            return new Promise((resolve, reject) => {
                getOption().then((response) => {
                    const result = response.result || {}
                    commit('SET_OPTIONS', result)
                    resolve(response)
                }).catch((error) => {
                    reject(error)
                })
            })
        },
        // 修改密码
        ChangePassword ({ commit }, data) {
            return new Promise((resolve, reject) => {
                changePassword(data).then((response) => {
                    resolve(response)
                }).catch((error) => {
                    reject(error)
                })
            })
        }
    }
}

export default user
