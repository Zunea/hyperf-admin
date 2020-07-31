import { axios } from '@/utils/request'

export function login (data) {
    return axios({
        url: 'admin/auth/login',
        method: 'post',
        data
    })
}
