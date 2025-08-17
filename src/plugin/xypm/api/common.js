import { request } from '@/utils/request.js'

export default {
    /**
     * 上传图片接口
     * @returns
     */
    uploadImage(data = {}) {
        return request({
            url: '/core/system/uploadImage',
            method: 'post',
            timeout: 30000,
            // headers: { 'Content-Type': 'multipart/form-data' },
            data
        })
    }
}