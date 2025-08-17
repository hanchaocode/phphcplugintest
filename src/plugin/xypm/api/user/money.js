import { request } from '@/utils/request.js'

/**
 * 余额调整记录管理 API接口
 */
export default {
    /**
     * 获取记录列表
     * @returns
     */
    getList(params = {}) {
        return request({
            url: '/app/xypm/admin/user/money/index',
            method: 'get',
            params
        })
    },

    /**
     * 删除记录
     * @returns
     */
    delete(data) {
        return request({
            url: '/app/xypm/admin/user/money/destroy',
            method: 'delete',
            data
        })
    }
}
