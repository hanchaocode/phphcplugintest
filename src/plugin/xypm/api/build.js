import { request } from '@/utils/request.js'

/**
 * 房产管理 API接口
 */
export default {
    /**
     * 获取房产列表
     * @returns
     */
    getBuildList(params = {}) {
        return request({
            url: '/app/xypm/admin/build/build/index',
            method: 'get',
            params
        })
    },
    getBuildOptions(params = {}) {
        return request({
            url: '/app/xypm/admin/build/build/select',
            method: 'get',
            params
        })
    },

    /**
     * 添加房产
     * @returns
     */
    addBuild(params = {}) {
        return request({
            url: '/app/xypm/admin/build/build/save',
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
            url: '/app/xypm/admin/build/build/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 批量添加房产
     * @returns
     */
    multiaddBuild(params = {}) {
        return request({
            url: '/app/xypm/admin/build/build/multiadd',
            method: 'post',
            data: params
        })
    },
    /**
     * 编辑房产
     * @returns
     */
    editBuild(id, data = {}) {
        return request({
            url: '/app/xypm/admin/build/build/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除房产
     * @returns
     */
    deleteBuild(data) {
        return request({
            url: '/app/xypm/admin/build/build/destroy',
            method: 'delete',
            data
        })
    }
}
