import { request } from '@/utils/request.js'

/**
 * 报修管理 API接口
 */
export default {
    /**
     * 获取报修列表
     * @returns
     */
    getRepairList(params = {}) {
        return request({
            url: '/app/xypm/admin/repair/index',
            method: 'get',
            params
        })
    },

    /**
     * 处理报修
     * @returns
     */
    handleRepair(data = {}) {
        return request({
            url: '/app/xypm/admin/repair/handle' ,
            method: 'post',
            data
        })
    },

    /**
     * 编辑报修
     * @returns
     */
    editRepair(id, data = {}) {
        return request({
            url: '/app/xypm/admin/repair/update?id=' + id,
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
            url: '/app/xypm/admin/repair/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除报修
     * @returns
     */
    deleteRepair(data) {
        return request({
            url: '/app/xypm/admin/repair/destroy',
            method: 'delete',
            data
        })
    }
}
