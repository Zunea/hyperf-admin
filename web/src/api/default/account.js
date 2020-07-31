import { axios } from '@/utils/request'

// 获取用户信息接口
export function getInfo () {
    return axios({
        url: 'admin/account/info',
        method: 'get'
    })
}

// 获取菜单接口
export function getMenu () {
    return axios({
        url: 'admin/account/menu',
        method: 'get'
    })
}

// 退出接口
export function logout () {
    return axios({
        url: 'admin/account/logout',
        method: 'post'
    })
}

// 获取选项接口
export function getOption () {
    return axios({
        url: 'admin/account/option',
        method: 'get'
    })
}

// 修改密码接口
export function changePassword (data) {
    return axios({
        url: 'admin/account/password',
        method: 'put',
        data
    })
}
