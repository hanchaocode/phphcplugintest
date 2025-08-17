import { request } from '@/utils/request.js'

/**
 * 费用账单 API接口
 */
export default {
    /**
     * 获取账单列表
     * @returns
     */
    getBillList(params = {}) {
        return request({
            url: '/app/xypm/admin/bill/bill/index',
            method: 'get',
            params
        })
    },
    /**
     * 生成账单
     * @returns
     */
    buildBill( data = {}) {
        return request({
            url: '/app/xypm/admin/bill/bill/build',
            method: 'post',
            data
        })
    },


    /**
     * 编辑账单
     * @returns
     */
    editBill(id, data = {}) {
        return request({
            url: '/app/xypm/admin/bill/bill/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除账单
     * @returns
     */
    deleteBill(data) {
        return request({
            url: '/app/xypm/admin/bill/bill/destroy',
            method: 'delete',
            data
        })
    }
}
