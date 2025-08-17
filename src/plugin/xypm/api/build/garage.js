import { request } from '@/utils/request.js'

/**
 * 管理 API接口
 */
export default {
    /**
     * 获取储物间列表
     * @returns
     */
    getGarageList(params = {}) {
        return request({
            url: '/app/xypm/admin/build/garage/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取选项
     * @returns
     */
    getGarageOption(params = {}) {
        return request({
            url: '/app/xypm/admin/build/garage/select',
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
            url: '/app/xypm/admin/build/garage/save',
            method: 'post',
            data: params
        })
    },
    /**
     * 读取数据
     * @returns
     */
    read(id) {
        return request({
            url: '/app/xypm/admin/build/garage/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 批量添加储物间
     * @returns
     */
    multiaddGarage(params = {}) {
        return request({
            url: '/app/xypm/admin/build/garage/multiadd',
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
            url: '/app/xypm/admin/build/garage/update?id=' + id,
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
            url: '/app/xypm/admin/build/garage/destroy',
            method: 'delete',
            data
        })
    }
}
