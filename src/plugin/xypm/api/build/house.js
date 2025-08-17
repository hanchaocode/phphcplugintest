import { request } from '@/utils/request.js'

/**
 * 管理 API接口
 */
export default {
    /**
     * 获取房屋列表
     * @returns
     */
    getHouseList(params = {}) {
        return request({
            url: '/app/xypm/admin/build/house/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取房屋选项
     * @returns
     */
    getHouseOption(params = {}) {
        return request({
            url: '/app/xypm/admin/build/house/select',
            method: 'get',
            params
        })
    },
    /**
     * 添加房屋
     * @returns
     */
    addHouse(params = {}) {
        return request({
            url: '/app/xypm/admin/build/house/save',
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
            url: '/app/xypm/admin/build/house/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 批量添加房屋
     * @returns
     */
    multiaddHouse(params = {}) {
        return request({
            url: '/app/xypm/admin/build/house/multiadd',
            method: 'post',
            data: params
        })
    },
    /**
     * 编辑房屋
     * @returns
     */
    editHouse(id, data = {}) {
        return request({
            url: '/app/xypm/admin/build/house/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除房屋
     * @returns
     */
    deleteHouse(data) {
        return request({
            url: '/app/xypm/admin/build/house/destroy',
            method: 'delete',
            data
        })
    }
}
