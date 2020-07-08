import { axios } from '@/utils/request'

export function upload (data) {
    return axios({
        url: 'upload',
        method: 'post',
        data
    })
}
