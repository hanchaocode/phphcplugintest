import { request } from '@/utils/request.js'

/**
 * 会员管理 API接口
 */
export default {
    /**
     * 获取会员列表
     * @returns
     */
    getUserList(params = {}) {
        return request({
            url: '/app/xypm/admin/user/index',
            method: 'get',
            params
        })
    },

    /**
     * 读取会员
     * @returns
     */
    read(id) {
        return request({
            url: '/app/xypm/admin/user/read?id='+id,
            method: 'get'
        })
    },

    /**
     * 添加会员
     * @returns
     */
    addUser(params = {}) {
        return request({
            url: '/app/xypm/admin/user/add',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑会员
     * @returns
     */
    editUser(id, data = {}) {
        return request({
            url: '/app/xypm/admin/user/edit?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 调整余额
     * @returns
     */
    recharge(id, data = {}) {
        return request({
            url: '/app/xypm/admin/user/recharge?id=' + id,
            method: 'post',
            data
        })
    },

    /**
     * 删除会员
     * @returns
     */
    deleteUser(data) {
        return request({
            url: '/app/xypm/admin/user/destroy',
            method: 'delete',
            data
        })
    }
}
