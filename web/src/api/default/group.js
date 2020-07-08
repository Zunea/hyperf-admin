import { axios } from '@/utils/request'

export function getGroup (params) {
    return axios({
        url: 'group',
        method: 'get',
        params
    })
}

export function addGroup (data) {
    return axios({
        url: 'group',
        method: 'post',
        data
    })
}

export function updateGroup (id, data) {
    return axios({
        url: `group/${id}`,
        method: 'put',
        data
    })
}

export function delGroup (id) {
    return axios({
        url: `group/${id}`,
        method: 'delete'
    })
}

export function updateGroupMenu (id, data) {
    return axios({
        url: `group/menu/${id}`,
        method: 'put',
        data
    })
}

export function updateGroupResource (id, data) {
    return axios({
        url: `group/resource/${id}`,
        method: 'put',
        data
    })
}
