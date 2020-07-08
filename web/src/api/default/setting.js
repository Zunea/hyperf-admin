import { axios } from '@/utils/request'

export function getSetting (params) {
    return axios({
        url: 'setting',
        method: 'get',
        params
    })
}

export function updateSetting (data) {
    return axios({
        url: 'setting',
        method: 'put',
        data
    })
}
