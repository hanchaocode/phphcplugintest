import { request } from '@/utils/request.js'

/**
 * 活动管理 API接口
 */
export default {
    /**
     * 获取活动列表
     * @returns
     */
    getActivityList(params = {}) {
        return request({
            url: '/app/xypm/admin/activity/activity/index',
            method: 'get',
            params
        })
    },
    getActivityOptions(params = {}) {
        return request({
            url: '/app/xypm/admin/activity/activity/select',
            method: 'get',
            params
        })
    },

    /**
     * 添加活动
     * @returns
     */
    addActivity(params = {}) {
        return request({
            url: '/app/xypm/admin/activity/activity/save',
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
            url: '/app/xypm/admin/activity/activity/read?id=' + id,
            method: 'get'
        })
    },
  
    /**
     * 编辑活动
     * @returns
     */
    editActivity(id, data = {}) {
        return request({
            url: '/app/xypm/admin/activity/activity/update?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 删除活动
     * @returns
     */
    deleteActivity(data) {
        return request({
            url: '/app/xypm/admin/activity/activity/destroy',
            method: 'delete',
            data
        })
    }
}
