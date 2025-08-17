import { request } from '@/utils/request.js'

/**
 * 物业服务 API接口
 */
export default {
    /**
     * 获取服务列表
     * @returns
     */
    getServiceList(params = {}) {
        return request({
            url: '/app/xypm/service',
            method: 'get',
            params
        })
    },

    /**
     * 添加服务
     * @returns
     */
    addService(params = {}) {
        return request({
            url: '/app/xypm/service/add',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑服务
     * @returns
     */
    editService(id, data = {}) {
        return request({
            url: '/app/xypm/service/edit?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除服务
     * @returns
     */
    deleteService(data) {
        return request({
            url: '/app/xypm/service/destroy',
method: 'delete',
data
        })
    }
}
