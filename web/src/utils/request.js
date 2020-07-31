import Vue from 'vue'
import axios from 'axios'
import store from '@/store'
import { Message } from 'view-design'
import {
    ACCESS_TOKEN,
    AUTHORIZATION,
    LANG,
    EXCHANGE_TOKEN
} from '@/config/constants'

const service = axios.create({
    // eslint-disable-next-line no-undef
    baseURL: process.env.VUE_APP_API_BASE_URL,
    timeout: 6000 // 请求超时时间
})

service.interceptors.request.use((config) => {
    const token = Vue.ls.get(ACCESS_TOKEN)
    const locale = Vue.ls.get(LANG)
    if (token) {
        // eslint-disable-next-line no-param-reassign
        config.headers[AUTHORIZATION] = token
    }
    if (locale) {
        // eslint-disable-next-line no-param-reassign
        config.headers.Locale = locale
    }
    return config
}, error => Promise.reject(error))

service.interceptors.response.use((response) => {
    const result = response.data
    if (result.code !== 200) {
        if (result.code === 401) {
            store.dispatch('Logout').then(() => {
                setTimeout(() => {
                    window.location.reload()
                }, 1000)
            })
        }
        if (result.errors || store.getters.messageTake) {
            store.dispatch('MessageTake', false).then(() => {})
            // 表单手动接管错误
            return Promise.reject(result)
        }
        // 顶部自动提示
        Message.error(result.message || 'Network Error')

        return Promise.reject(result)
    }
    // 交换Token
    if (response.headers[EXCHANGE_TOKEN]) {
        Vue.ls.set(ACCESS_TOKEN, response.headers[EXCHANGE_TOKEN])
    }
    return response.data
}, (error) => {
    Message.error('Network Error')
    return Promise.reject(error)
})

export {
    service as axios
}
