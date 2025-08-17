import { request } from '@/utils/request.js'

/**
 * 充值套餐管理 API接口
 */
export default {
    /**
     * 获取充值套餐列表
     * @returns
     */
    getList(params = {}) {
        return request({
            url: '/app/xypm/admin/recharge/recharge/index',
            method: 'get',
            params
        })
    },
    // getOptions(params = {}) {
    //     return request({
    //         url: '/app/xypm/admin/recharge/recharge/select',
    //         method: 'get',
    //         params
    //     })
    // },

    /**
     * 添加充值套餐
     * @returns
     */
    add(params = {}) {
        return request({
            url: '/app/xypm/admin/recharge/recharge/save',
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
            url: '/app/xypm/admin/recharge/recharge/read?id=' + id,
            method: 'get'
        })
    },
  
    /**
     * 编辑充值套餐
     * @returns
     */
    edit(id, data = {}) {
        return request({
            url: '/app/xypm/admin/recharge/recharge/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除充值套餐
     * @returns
     */
    delete(data) {
        return request({
            url: '/app/xypm/admin/recharge/recharge/destroy',
            method: 'delete',
            data
        })
    }
}
