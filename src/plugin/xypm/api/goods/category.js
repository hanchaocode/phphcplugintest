import { request } from '@/utils/request.js'

/**
 * 商品分类管理 API接口
 */
export default {
    /**
     * 获取商品分类列表
     * @returns
     */
    getCategoryList(params = {}) {
        return request({
            url: '/app/xypm/admin/goods/category/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取商品分类选项
     * @returns
     */
    getCategoryOption(params = {}) {
        return request({
            url: '/app/xypm/admin/goods/category/select',
            method: 'get',
            params
        })
    },

    /**
     * 添加商品分类
     * @returns
     */
    addCategory(params = {}) {
        return request({
            url: '/app/xypm/admin/goods/category/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑商品分类
     * @returns
     */
    editCategory(id, data = {}) {
        return request({
            url: '/app/xypm/admin/goods/category/update?id=' + id,
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
            url: '/app/xypm/admin/goods/category/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除商品分类
     * @returns
     */
    deleteCategory(data) {
        return request({
            url: '/app/xypm/admin/goods/category/destroy',
            method: 'delete',
            data
        })
    }
}
