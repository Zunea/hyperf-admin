import { setI18nLanguage } from '@/locales'

const i18n = {
    state: {
        lang: 'zh_CN'
    },
    mutations: {
        SET_LANG: (state, lang) => {
            state.lang = lang
        }
    },
    actions: {
        // 设置界面语言
        SetLang ({ commit }, lang) {
            return new Promise((resolve) => {
                commit('SET_LANG', lang)
                setI18nLanguage(lang)
                resolve()
            })
        }
    }
}

export default i18n
