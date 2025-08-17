import { request } from '@/utils/request.js'

/**
 * tabbar API接口
 */
export default {



    /**
     * 读取数据
     * @returns
     */
    read() {
        return request({
            url: '/app/xypm/admin/tabbar/get',
            method: 'get'
        })
    },
    /**
     * 更新
     * @returns
     */
    save(data = {}) {
        return request({
            url: '/app/xypm/admin/tabbar/save',
            method: 'post',
            data
        })
    },

}
