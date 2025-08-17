import { request } from '@/utils/request.js'

/**
 * 管理 API接口
 */
export default {
    /**
     * 获取商铺列表
     * @returns
     */
    getShopList(params = {}) {
        return request({
            url: '/app/xypm/admin/build/shop/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取商铺选项
     * @returns
     */
    getShopOption(params = {}) {
        return request({
            url: '/app/xypm/admin/build/shop/select',
            method: 'get',
            params
        })
    },
    /**
     * 添加商铺
     * @returns
     */
    addShop(params = {}) {
        return request({
            url: '/app/xypm/admin/build/shop/save',
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
            url: '/app/xypm/admin/build/shop/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 批量添加商铺
     * @returns
     */
    multiaddShop(params = {}) {
        return request({
            url: '/app/xypm/admin/build/shop/multiadd',
            method: 'post',
            data: params
        })
    },
    /**
     * 编辑商铺
     * @returns
     */
    editShop(id, data = {}) {
        return request({
            url: '/app/xypm/admin/build/shop/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除商铺
     * @returns
     */
    deleteShop(data) {
        return request({
            url: '/app/xypm/admin/build/shop/destroy',
            method: 'delete',
            data
        })
    }
}
