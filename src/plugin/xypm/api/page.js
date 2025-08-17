import { request } from '@/utils/request.js'

/**
 * 页面模板 API接口
 */
export default {
    /**
     * 获取模板列表
     * @returns
     */
    getPageList(params = {}) {
        return request({
            url: '/app/xypm/admin/page/index',
            method: 'get',
            params
        })
    },

    /**
     * 获取历史
     * @returns
     */
    getHistroy(params = {}) {
        return request({
            url: '/app/xypm/admin/page/history',
            method: 'get',
            params
        })
    },

    /**
     * 添加模板
     * @returns
     */
    addPage(params = {}) {
        return request({
            url: '/app/xypm/admin/page/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 发布模板
     * @returns
     */
    publishPage(params = {}) {
        return request({
            url: '/app/xypm/admin/page/use',
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
            url: '/app/xypm/admin/page/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 编辑模板
     * @returns
     */
    editPage(id, data = {}) {
        return request({
            url: '/app/xypm/admin/page/update?id=' + id,
            method: 'put',
            data
        })
    },
    /**
     * 恢复历史
     * @returns
     */
    recoverPage(id) {
        return request({
            url: '/app/xypm/admin/page/recover?id=' + id,
            method: 'get',

        })
    },


    /**
     * 删除模板
     * @returns
     */
    deletePage(data) {
        return request({
            url: '/app/xypm/admin/page/destroy',
            method: 'delete',
            data
        })
    }
}
