import Vue from 'vue'
import App from './App'
import router from './router'
import i18n from '@/locales'
import store from './store'
import './permission'
import directives from '@/utils/directives'

// iView
import ViewUI from 'view-design'
import iViewConfig from '@/configs/iview'
import './theme/iview.less'

// elementUI
import ElementUI from 'element-ui'
import ElementLocale from 'element-ui/lib/locale'
import ElementUIConfig from '@/configs/elementui'
import './theme/elementui.sass'
import '@/assets/icons/iconfont.css'
import Fragment from 'vue-fragment'

Vue.config.productionTip = false
Vue.use(Fragment.Plugin)

// iView
Vue.use(ViewUI, iViewConfig)

// elementUI
Vue.use(ElementUI)
ElementLocale.i18n((key, value) => i18n.t(key, value))
Vue.prototype.$ELEMENT = ElementUIConfig

Vue.use(directives)
/* eslint-disable no-new */
new Vue({
    i18n,
    router,
    store,
    render: (h) => h(App)
}).$mount('#app')
