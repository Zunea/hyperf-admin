import { axios } from '@/utils/request'

export function upload (data) {
    return axios({
        url: 'admin/upload',
        method: 'post',
        data
    })
}
