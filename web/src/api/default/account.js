import { axios } from '@/utils/request'

// 获取用户信息接口
export function getInfo () {
    return axios({
        url: 'account/info',
        method: 'get'
    })
}

// 获取菜单接口
export function getMenu () {
    return axios({
        url: 'account/menu',
        method: 'get'
    })
}

// 退出接口
export function logout () {
    return axios({
        url: 'account/logout',
        method: 'post'
    })
}

// 获取选项接口
export function getOption () {
    return axios({
        url: 'account/option',
        method: 'get'
    })
}
