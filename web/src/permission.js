import Vue from 'vue'
import router from './router'
import { i18nRender } from '@/locales'
import store from './store'
import NProgress from 'nprogress'
import 'nprogress/nprogress.css'
import { ACCESS_TOKEN } from '@/config/constants'

NProgress.configure({ showSpinner: false })

// 白名单
const whiteList = ['login']
router.beforeEach((to, from, next) => {
    NProgress.start()
    // 动态修改title
    document.title = (to.meta && to.meta.title) ? i18nRender(`menu.${to.meta.title}`) : ''

    // token不存在
    if (!Vue.ls.get(ACCESS_TOKEN)) {
        // 免登陆白名单
        if (whiteList.includes(to.name)) {
            next()
        } else {
            next({ path: '/auth/login' })
            NProgress.done()
        }
        return
    }

    if (to.path === '/auth/login') {
        next({ path: '/' })
        NProgress.done()
    } else if (!store.getters.user.id) {
        store.dispatch('GetInfo').then((res) => {
            store.dispatch('GenerateRoutes', {}).then(() => {
                console.log('account', res.result)
                console.log('router', store.getters.addRouters)
                router.addRoutes(store.getters.addRouters)
                next({ ...to, replace: true })
            }).catch((err) => {
                // todo 请求用户接口失败
                console.log(err)
                store.dispatch('Logout').then(() => {
                    next({ path: '/auth/login' })
                })
            })
        })
    } else {
        // 增加tab多页签数据
        store.dispatch('PUSH_ROUTER', {
            fullPath: to.fullPath,
            meta: to.meta,
            name: to.name,
            path: to.path
        })
        next()
    }
})

router.afterEach((route) => {
    store.dispatch('SetCurrentRoute', route).then(() => {})
    NProgress.done()
})
