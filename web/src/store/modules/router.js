/**
 * 向后端请求用户的菜单，动态生成路由
 */
import { constantRouterMap } from '@/router/constantRouter'
import { generatorDynamicRouter } from '@/router/generator-routers'

const permission = {
    state: {
        routers: constantRouterMap,
        addRouters: [],
        router: {}
    },
    mutations: {
        SET_ROUTERS: (state, routers) => {
            state.addRouters = routers
            state.routers = constantRouterMap.concat(routers)
        },
        SET_ROUTER: (state, router) => {
            state.router = router
        }
    },
    actions: {
        GenerateRoutes ({ commit }) {
            return new Promise((resolve) => {
                generatorDynamicRouter().then((result) => {
                    const { routers } = result
                    commit('SET_ROUTERS', routers || [])
                    resolve()
                })
            })
        },
        SetCurrentRoute ({ commit }, router) {
            commit('SET_ROUTER', router || {})
        }
    }
}

export default permission
