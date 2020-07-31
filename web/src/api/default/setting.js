import { axios } from '@/utils/request'

export function getSetting (params) {
    return axios({
        url: 'admin/setting',
        method: 'get',
        params
    })
}

export function updateSetting (data) {
    return axios({
        url: 'admin/setting',
        method: 'put',
        data
    })
}
