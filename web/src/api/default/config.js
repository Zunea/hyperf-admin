import { axios } from '@/utils/request'

export function getConfig (params) {
    return axios({
        url: 'config',
        method: 'get',
        params
    })
}

export function addConfig (data) {
    return axios({
        url: 'config',
        method: 'post',
        data
    })
}

export function updateConfig (id, data) {
    return axios({
        url: `config/${id}`,
        method: 'put',
        data
    })
}

export function deleteConfig (ids) {
    return axios({
        url: `config/${ids}`,
        method: 'delete'
    })
}

export function enableConfig (ids) {
    return axios({
        url: `config/enable/${ids}`,
        method: 'put'
    })
}

export function disableConfig (ids) {
    return axios({
        url: `config/disable/${ids}`,
        method: 'put'
    })
}

export function saveConfig (group, data) {
    return axios({
        url: `config/save/${group}`,
        method: 'put',
        data
    })
}
