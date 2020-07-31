import Vue from 'vue'
import VueI18n from 'vue-i18n'
import { axios } from '@/utils/request'
import Storage from 'vue-ls'
import { LANG } from '@/config/constants'

// 简体中文
import zhCN from './lang/zh-CN'
import izhCN from 'view-design/dist/locale/zh-CN'
import ezhCN from 'element-ui/lib/locale/lang/zh-CN'

// 英文
import enUS from './lang/en-US'
import ienUS from 'view-design/dist/locale/en-US'
import eenUS from 'element-ui/lib/locale/lang/en'

Vue.use(Storage)
Vue.use(VueI18n)
Vue.locale = () => {}

export const defaultLang = 'zh_CN'

const i18n = new VueI18n({
    locale: defaultLang,
    messages: {
        en_US: Object.assign(enUS, ienUS, eenUS),
        zh_CN: Object.assign(zhCN, izhCN, ezhCN)
    }
})

export default i18n

// const loadedLanguages = [defaultLang]

export function setI18nLanguage (lang) {
    Vue.ls.set(LANG, lang)
    i18n.locale = lang
    axios.defaults.headers.Locale = lang
    document.querySelector('html').setAttribute('lang', lang)
    return lang
}

// 从缓存設置中加载当前语言
if (Vue.ls.get(LANG) !== null && defaultLang !== Vue.ls.get(LANG)) {
    setI18nLanguage(Vue.ls.get(LANG))
}

/**
 * i18n Render
 * @param key
 * @param rep
 * @returns VueI18n.TranslateResult string
 */
export function i18nRender (key, rep = {}) {
    return i18n.t(key, rep)
}
