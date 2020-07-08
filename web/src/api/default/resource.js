import { axios } from '@/utils/request'

export function getResource (params) {
    return axios({
        url: 'resource',
        method: 'get',
        params
    })
}

export function addResource (data) {
    return axios({
        url: 'resource',
        method: 'post',
        data
    })
}

export function updateResource (id, data) {
    return axios({
        url: `resource/${id}`,
        method: 'put',
        data
    })
}

export function delResource (id) {
    return axios({
        url: `resource/${id}`,
        method: 'delete'
    })
}
