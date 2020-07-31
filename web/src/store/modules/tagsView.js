import { indexPage } from '@/config'
import router from '@/router'

const tagsView = {
    state: {
        routerArr: []
    },
    mutations: {
        PUSH_ROUTER_ARR: (state, data) => {
            if (!state.routerArr.some(r => r.path === data.path)) {
                // 如果是默认主页 就放到第一个
                if (data.name === indexPage) {
                    state.routerArr.unshift(data)
                } else {
                    state.routerArr.push(data)
                }
            }
        },
        DEL_ROUTER_ARR: (state, index) => {
            state.routerArr.splice(index, 1)
            if (state.routerArr.length === 0) {
                router.push({ path: indexPage })
            }
        }
    },
    actions: {
        PUSH_ROUTER ({ commit }, data) {
            commit('PUSH_ROUTER_ARR', data)
        },
        DEL_ROUTER ({ commit }, index) {
            commit('DEL_ROUTER_ARR', index)
        }
    }
}

export default tagsView
