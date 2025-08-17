import { request } from '@/utils/request.js'

/**
 * 楼宇管理 API接口
 */
export default {
    /**
     * 获取楼宇列表
     * @returns
     */
    getBuildingList(params = {}) {
        return request({
            url: '/app/xypm/build/build/index',
            method: 'get',
            params
        })
    },

    /**
     * 添加楼宇
     * @returns
     */
    addBuilding(params = {}) {
        return request({
            url: '/app/xypm/build/build/add',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑楼宇
     * @returns
     */
    editBuilding(id, data = {}) {
        return request({
            url: '/app/xypm/build/build/edit?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除楼宇
     * @returns
     */
    deleteBuilding(data) {
        return request({
            url: '/app/xypm/build/build/destroy',
method: 'delete',
data
        })
    }
}
