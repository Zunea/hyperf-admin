import { axios } from '@/utils/request'

export function getGroup (params) {
    return axios({
        url: 'admin/group',
        method: 'get',
        params
    })
}

export function addGroup (data) {
    return axios({
        url: 'admin/group',
        method: 'post',
        data
    })
}

export function updateGroup (id, data) {
    return axios({
        url: `admin/group/${id}`,
        method: 'put',
        data
    })
}

export function delGroup (id) {
    return axios({
        url: `admin/group/${id}`,
        method: 'delete'
    })
}

export function updateGroupMenu (id, data) {
    return axios({
        url: `admin/group/menu/${id}`,
        method: 'put',
        data
    })
}

export function updateGroupResource (id, data) {
    return axios({
        url: `admin/group/resource/${id}`,
        method: 'put',
        data
    })
}
