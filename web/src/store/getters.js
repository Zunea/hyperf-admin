const getters = {
    multiTab: state => state.app.multiTab,
    user: state => state.user.info,
    addRouters: state => state.router.addRouters,
    router: state => state.router.router,
    options: state => state.user.options,
    messageTake: state => state.app.messageTake,
    baseURL: state => state.app.baseURL,
    token: state => state.user,
    routerArr: state => state.tagsView.routerArr,
    themeType: state => state.app.themeType
}

export default getters
