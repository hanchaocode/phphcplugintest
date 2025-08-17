import { request } from '@/utils/request.js'

/**
 * 区域管理 API接口
 */
export default {
    /**
     * 获取区域列表
     * @returns
     */
    getAreaList(params = {}) {
        return request({
            url: '/app/xypm/admin/build/area/index',
            method: 'get',
            params
        })
    },

    /**
     * 添加区域
     * @returns
     */
    addArea(params = {}) {
        return request({
            url: '/app/xypm/admin/build/area/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑区域
     * @returns
     */
    editArea(id, data = {}) {
        return request({
            url: '/app/xypm/admin/build/area/update?id=' + id,
            method: 'put',
            data
        })
    },
    /**
     * 读取数据
     * @returns
     */
    read(id) {
        return request({
            url: '/app/xypm/admin/build/area/read?id=' + id,
            method: 'get'
        })
    },
    getAreaOptions(params = {}) {
        return request({
            url: '/app/xypm/admin/build/area/select',
            method: 'get',
            params
        })
    },
    /**
     * 删除区域
     * @returns
     */
    deleteArea(data) {
        return request({
            url: '/app/xypm/admin/build/area/destroy',
            method: 'delete',
            data
        })
    }
}
