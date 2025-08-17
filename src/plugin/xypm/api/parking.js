import { request } from '@/utils/request.js'

/**
 * 车位管理 API接口
 */
export default {
    /**
     * 获取车位列表
     * @returns
     */
    getParkingList(params = {}) {
        return request({
            url: '/app/xypm/build/parking/index',
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
            url: '/app/xypm/build/parking/add',
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
            url: '/app/xypm/build/parking/edit?id=' + id,
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
            url: '/app/xypm/build/parking/destroy',
method: 'delete',
data
        })
    }
}
