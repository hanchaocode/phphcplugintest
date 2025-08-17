import { request } from '@/utils/request.js'

/**
 * 文章管理 API接口
 */
export default {
    /**
     * 获取文章列表
     * @returns
     */
    getArticleList(params = {}) {
        return request({
            url: '/app/xypm/admin/article/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取文章选项
     * @returns
     */
    getArticleOption(params = {}) {
        return request({
            url: '/app/xypm/admin/article/select',
            method: 'get',
            params
        })
    },

    /**
     * 添加文章
     * @returns
     */
    addArticle(params = {}) {
        return request({
            url: '/app/xypm/admin/article/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑文章
     * @returns
     */
    editArticle(id, data = {}) {
        return request({
            url: '/app/xypm/admin/article/update?id=' + id,
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
            url: '/app/xypm/admin/article/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除文章
     * @returns
     */
    deleteArticle(data) {
        return request({
            url: '/app/xypm/admin/article/destroy',
            method: 'delete',
            data
        })
    }
}
