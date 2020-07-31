import Vue from 'vue'
import App from './App'
import router from './router'
import i18n from './locales'
import store from './store'
import './permission'
import directives from './utils/directives'
import Fragment from 'vue-fragment'
import { indexPage, loginPage } from './config'
import './assets/css/public.less'
import 'babel-polyfill'
import './assets/icons/iconfont.css'

// iView
import ViewUI from 'view-design'
import iViewConfig from './config/iview'
import './theme/iview.less'

// elementUI
import ElementUI from 'element-ui'
import ElementLocale from 'element-ui/lib/locale'
import ElementUIConfig from './config/elementui'
import './theme/elementui.sass'

// iView
Vue.use(ViewUI, iViewConfig)

// elementUI
Vue.use(ElementUI)
ElementLocale.i18n((key, value) => i18n.t(key, value))
Vue.prototype.$ELEMENT = ElementUIConfig

Vue.use(Fragment.Plugin)
Vue.prototype.$indexPage = indexPage
Vue.prototype.$loginPage = loginPage
Vue.use(directives)
/* eslint-disable no-new */
new Vue({
    i18n,
    router,
    store,
    render: (h) => h(App)
}).$mount('#app')
