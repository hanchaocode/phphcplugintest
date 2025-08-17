import { request } from '@/utils/request.js'

/**
 * 商城管理 API接口
 */
export default {
    /**
     * 获取商品列表
     * @returns
     */
    getGoodsList(params = {}) {
        return request({
            url: '/app/xypm/shop/goods/index',
            method: 'get',
            params
        })
    },

    /**
     * 添加商品
     * @returns
     */
    addGoods(params = {}) {
        return request({
            url: '/app/xypm/shop/goods/add',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑商品
     * @returns
     */
    editGoods(id, data = {}) {
        return request({
            url: '/app/xypm/shop/goods/edit?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除商品
     * @returns
     */
    deleteGoods(data) {
        return request({
            url: '/app/xypm/shop/goods/destroy',
method: 'delete',
data
        })
    }
}
