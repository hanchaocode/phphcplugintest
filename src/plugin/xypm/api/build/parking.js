import { request } from '@/utils/request.js'

/**
 * 管理 API接口
 */
export default {
    /**
     * 获取车位列表
     * @returns
     */
    getParkingList(params = {}) {
        return request({
            url: '/app/xypm/admin/build/parking/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取选项
     * @returns
     */
    getParkingOption(params = {}) {
        return request({
            url: '/app/xypm/admin/build/parking/select',
            method: 'get',
            params
        })
    },

    /**
     * 添加车位
     * @returns
     */
    addParking(params = {}) {
        return request({
            url: '/app/xypm/admin/build/parking/save',
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
            url: '/app/xypm/admin/build/parking/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 批量添加车位
     * @returns
     */
    multiaddParking(params = {}) {
        return request({
            url: '/app/xypm/admin/build/parking/multiadd',
            method: 'post',
            data: params
        })
    },
    /**
     * 编辑车位
     * @returns
     */
    editParking(id, data = {}) {
        return request({
            url: '/app/xypm/admin/build/parking/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除车位
     * @returns
     */
    deleteParking(data) {
        return request({
            url: '/app/xypm/admin/build/parking/destroy',
            method: 'delete',
            data
        })
    }
}
