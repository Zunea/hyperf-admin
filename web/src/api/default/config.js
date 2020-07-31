import { axios } from '@/utils/request'

export function getConfig (params) {
    return axios({
        url: 'admin/config',
        method: 'get',
        params
    })
}

export function addConfig (data) {
    return axios({
        url: 'admin/config',
        method: 'post',
        data
    })
}

export function updateConfig (id, data) {
    return axios({
        url: `admin/config/${id}`,
        method: 'put',
        data
    })
}

export function deleteConfig (ids) {
    return axios({
        url: `admin/config/${ids}`,
        method: 'delete'
    })
}

export function enableConfig (ids) {
    return axios({
        url: `admin/config/enable/${ids}`,
        method: 'put'
    })
}

export function disableConfig (ids) {
    return axios({
        url: `admin/config/disable/${ids}`,
        method: 'put'
    })
}

export function saveConfig (group, data) {
    return axios({
        url: `admin/config/save/${group}`,
        method: 'put',
        data
    })
}
