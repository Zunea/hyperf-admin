import { axios } from '@/utils/request'

export function login (data) {
    return axios({
        url: 'auth/login',
        method: 'post',
        data
    })
}
