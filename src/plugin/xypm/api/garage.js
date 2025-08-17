import { request } from '@/utils/request.js'

/**
 * 储物间管理 API接口
 */
export default {
    /**
     * 获取储物间列表
     * @returns
     */
    getGarageList(params = {}) {
        return request({
            url: '/app/xypm/build/garage/index',
            method: 'get',
            params
        })
    },

    /**
     * 添加储物间
     * @returns
     */
    addGarage(params = {}) {
        return request({
            url: '/app/xypm/build/garage/add',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑储物间
     * @returns
     */
    editGarage(id, data = {}) {
        return request({
            url: '/app/xypm/build/garage/edit?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除储物间
     * @returns
     */
    deleteGarage(data) {
        return request({
            url: '/app/xypm/build/garage/destroy',
method: 'delete',
data
        })
    }
}
