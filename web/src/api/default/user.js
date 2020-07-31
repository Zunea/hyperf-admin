import { axios } from '@/utils/request'

export function getUser (params) {
    return axios({
        url: 'admin/user',
        method: 'get',
        params
    })
}

export function addUser (data) {
    return axios({
        url: 'admin/user',
        method: 'post',
        data
    })
}

export function updateUser (id, data) {
    return axios({
        url: `admin/user/${id}`,
        method: 'put',
        data
    })
}

export function delUser (id) {
    return axios({
        url: `admin/user/${id}`,
        method: 'delete'
    })
}
