import { request } from '@/utils/request.js'

/**
 * 财务管理 API接口
 */
export default {
    /**
     * 获取财务记录列表
     * @returns
     */
    getMoneyList(params = {}) {
        return request({
            url: '/app/xypm/money',
            method: 'get',
            params
        })
    },

    /**
     * 添加财务记录
     * @returns
     */
    addMoney(params = {}) {
        return request({
            url: '/app/xypm/money/add',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑财务记录
     * @returns
     */
    editMoney(id, data = {}) {
        return request({
            url: '/app/xypm/money/edit?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除财务记录
     * @returns
     */
    deleteMoney(data) {
        return request({
            url: '/app/xypm/money/destroy',
method: 'delete',
data
        })
    }
}
