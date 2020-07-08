import { axios } from '@/utils/request'

export function getUser (params) {
    return axios({
        url: 'user',
        method: 'get',
        params
    })
}

export function addUser (data) {
    return axios({
        url: 'user',
        method: 'post',
        data
    })
}

export function updateUser (id, data) {
    return axios({
        url: `user/${id}`,
        method: 'put',
        data
    })
}

export function delUser (id) {
    return axios({
        url: `user/${id}`,
        method: 'delete'
    })
}
