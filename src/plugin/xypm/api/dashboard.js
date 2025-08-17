import { request } from '@/utils/request.js'

/**
 * 控制台 API接口
 */
export default {
    /**
     * 获取控制台数据
     * @returns
     */
    getDashboardData(params = {}) {
        return request({
            url: '/app/xypm/admin/dashboard/index',
            method: 'get',
            params
        })
    }
}
