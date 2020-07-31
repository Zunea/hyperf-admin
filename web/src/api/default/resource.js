import { axios } from '@/utils/request'

export function getResource (params) {
    return axios({
        url: 'admin/resource',
        method: 'get',
        params
    })
}

export function addResource (data) {
    return axios({
        url: 'admin/resource',
        method: 'post',
        data
    })
}

export function updateResource (id, data) {
    return axios({
        url: `admin/resource/${id}`,
        method: 'put',
        data
    })
}

export function delResource (id) {
    return axios({
        url: `admin/resource/${id}`,
        method: 'delete'
    })
}
