import { request } from '@/utils/request.js'

/**
 * 商品管理 API接口
 */
export default {
    /**
     * 获取商品列表
     * @returns
     */
    getGoodsList(params = {}) {
        return request({
            url: '/app/xypm/admin/goods/goods/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取商品选项
     * @returns
     */
    getGoodsOption(params = {}) {
        return request({
            url: '/app/xypm/admin/goods/goods/select',
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
            url: '/app/xypm/admin/goods/goods/save',
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
            url: '/app/xypm/admin/goods/goods/update?id=' + id,
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
            url: '/app/xypm/admin/goods/goods/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除商品
     * @returns
     */
    deleteGoods(data) {
        return request({
            url: '/app/xypm/admin/goods/goods/destroy',
            method: 'delete',
            data
        })
    }
}
