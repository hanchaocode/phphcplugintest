import { request } from '@/utils/request.js'

/**
 * 建议管理 API接口
 */
export default {
    /**
     * 获取建议列表
     * @returns
     */
    getSuggestList(params = {}) {
        return request({
            url: '/app/xypm/admin/suggest/index',
            method: 'get',
            params
        })
    },


    /**
     * 处理建议
     * @returns
     */
    handleSuggest(data = {}) {
        return request({
            url: '/app/xypm/admin/suggest/handle' ,
            method: 'post',
            data
        })
    },
    /**
     * 编辑建议
     * @returns
     */
    editSuggest(id, data = {}) {
        return request({
            url: '/app/xypm/admin/suggest/update?id=' + id,
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
            url: '/app/xypm/admin/suggest/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除建议
     * @returns
     */
    deleteSuggest(data) {
        return request({
            url: '/app/xypm/admin/suggest/destroy',
            method: 'delete',
            data
        })
    }
}
