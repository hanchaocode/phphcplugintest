import { request } from '@/utils/request.js'

/**
 * 充值订单管理 API接口
 */
export default {
    /**
     * 获取订单列表
     * @returns
     */
    getOrderList(params = {}) {
        return request({
            url: '/app/xypm/admin/recharge/order/index',
            method: 'get',
            params
        })
    },
    getOrderOptions(params = {}) {
        return request({
            url: '/app/xypm/admin/recharge/order/select',
            method: 'get',
            params
        })
    },

    /**
     * 添加订单
     * @returns
     */
    addOrder(params = {}) {
        return request({
            url: '/app/xypm/admin/recharge/order/save',
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
            url: '/app/xypm/admin/recharge/order/read?id=' + id,
            method: 'get'
        })
    },

    /**
     * 编辑订单
     * @returns
     */
    editOrder(id, data = {}) {
        return request({
            url: '/app/xypm/admin/recharge/order/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除订单
     * @returns
     */
    deleteOrder(data) {
        return request({
            url: '/app/xypm/admin/recharge/order/destroy',
            method: 'delete',
            data
        })
    }
}
