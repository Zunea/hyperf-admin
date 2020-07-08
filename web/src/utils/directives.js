import store from '@/store'

export default (Vue) => {
    // 指令权限
    Vue.directive('auth', {
        inserted: (el, directive) => {
            const { meta } = store.getters.router
            if (!meta.actions || meta.actions.indexOf(directive.value) === -1) {
                el.parentNode.removeChild(el)
            }
        }
    })
}
