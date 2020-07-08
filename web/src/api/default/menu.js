import { axios } from '@/utils/request'

export function getMenu (params) {
    return axios({
        url: 'menu',
        method: 'get',
        params
    })
}

export function addMenu (data) {
    return axios({
        url: 'menu',
        method: 'post',
        data
    })
}

export function updateMenu (id, data) {
    return axios({
        url: `menu/${id}`,
        method: 'put',
        data
    })
}

export function delMenu (id) {
    return axios({
        url: `menu/${id}`,
        method: 'delete'
    })
}
