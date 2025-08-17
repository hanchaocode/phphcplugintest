import { request } from '@/utils/request.js'

/**
 * 链接管理 API接口
 */
export default {
    /**
     * 获取链接列表
     * @returns
     */
    getLinkList(params = {}) {
        return request({
            url: '/app/xypm/admin/link/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取链接选项
     * @returns
     */
    getLinkOption(params = {}) {
        return request({
            url: '/app/xypm/admin/link/select',
            method: 'get',
            params
        })
    },

    /**
     * 添加链接
     * @returns
     */
    addLink(params = {}) {
        return request({
            url: '/app/xypm/admin/link/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑链接
     * @returns
     */
    editLink(id, data = {}) {
        return request({
            url: '/app/xypm/admin/link/update?id=' + id,
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
            url: '/app/xypm/admin/link/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除链接
     * @returns
     */
    deleteLink(data) {
        return request({
            url: '/app/xypm/admin/link/destroy',
            method: 'delete',
            data
        })
    },

    /**
     * 生成链接
     * @returns
     */
    buildLink(params = {}) {
        return request({
            url: '/app/xypm/admin/link/build',
            method: 'post',
            data: params
        })
    },
}
