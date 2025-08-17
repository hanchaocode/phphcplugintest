import { request } from '@/utils/request.js'

/**
 * 通知公告管理 API接口
 */
export default {
    /**
     * 获取通知列表
     * @returns
     */
    getNoticeList(params = {}) {
        return request({
            url: '/app/xypm/admin/notice/index',
            method: 'get',
            params
        })
    },

    /**
     * 添加通知
     * @returns
     */
    addNotice(params = {}) {
        return request({
            url: '/app/xypm/admin/notice/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑通知
     * @returns
     */
    editNotice(id, data = {}) {
        return request({
            url: '/app/xypm/admin/notice/update?id=' + id,
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
            url: '/app/xypm/admin/notice/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除通知
     * @returns
     */
    deleteNotice(data) {
        return request({
            url: '/app/xypm/admin/notice/destroy',
            method: 'delete',
            data
        })
    }
}
