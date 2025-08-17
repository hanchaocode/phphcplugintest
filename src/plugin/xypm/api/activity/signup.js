import { request } from '@/utils/request.js'

/**
 * 报名管理 API接口
 */
export default {
    /**
     * 获取报名列表
     * @returns
     */
    getSignupList(params = {}) {
        return request({
            url: '/app/xypm/admin/activity/signup/index',
            method: 'get',
            params
        })
    },
    getSignupOptions(params = {}) {
        return request({
            url: '/app/xypm/admin/activity/signup/select',
            method: 'get',
            params
        })
    },



    /**
     * 读取数据
     * @returns
     */
    read(id) {
        return request({
            url: '/app/xypm/admin/activity/signup/read?id=' + id,
            method: 'get'
        })
    },
  
    /**
     * 编辑报名
     * @returns
     */
    editSignup(id, data = {}) {
        return request({
            url: '/app/xypm/admin/activity/signup/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除报名
     * @returns
     */
    deleteSignup(data) {
        return request({
            url: '/app/xypm/admin/activity/signup/destroy' ,
            method: 'delete',
            data
        })
    }
}
