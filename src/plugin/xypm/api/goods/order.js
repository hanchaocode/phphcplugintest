import { request } from '@/utils/request.js'

/**
 * 订单管理 API接口
 */
export default {
    /**
     * 获取订单列表
     * @returns
     */
    getOrderList(params = {}) {
        return request({
            url: '/app/xypm/admin/goods/order/index',
            method: 'get',
            params
        })
    },
    /**
     * 获取订单选项
     * @returns
     */
    getOrderOption(params = {}) {
        return request({
            url: '/app/xypm/admin/goods/order/select',
            method: 'get',
            params
        })
    },
    /**
     * 发货
     * @returns
     */
    deliveryOrder(id) {
        return request({
            url: '/app/xypm/admin/goods/order/delivery?id='+id,
            method: 'post',

        })
    },
    /**
     * 收货
     * @returns
     */
    takeDeliveryOrder(id ) {
        return request({
            url: '/app/xypm/admin/goods/order/take_delivery?id='+id,
            method: 'post',

        })
    },


    /**
     * 编辑订单
     * @returns
     */
    editOrder(id, data = {}) {
        return request({
            url: '/app/xypm/admin/goods/order/update?id=' + id,
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
            url: '/app/xypm/admin/goods/order/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 查看物流
     * @returns
     */
    getLogistics(id) {
        return request({
            url: '/app/xypm/admin/goods/order/logistics?id=' + id,
            method: 'get'
        })
    },

    /**
     * 删除订单
     * @returns
     */
    deleteOrder(data) {
        return request({
            url: '/app/xypm/admin/goods/order/destroy',
            method: 'delete',
            data
        })
    }
}
