import { request } from '@/utils/request.js'

/**
 * 户主管理 API接口
 */
export default {
    /**
     * 获取户主列表
     * @returns
     */


    getMemberList(params = {}) {
        return request({
            url: '/app/xypm/admin/member/index',
            method: 'get',
            params
        })
    },


    /**
     * 添加户主
     * @returns
     */
    addMember(params = {}) {
        return request({
            url: '/app/xypm/admin/member/save',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑户主
     * @returns
     */
    editMember(id, data = {}) {
        return request({
            url: '/app/xypm/admin/member/update?id=' + id,
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
            url: '/app/xypm/admin/member/read?id=' + id,
            method: 'get'
        })
    },
    /**
     * 删除户主
     * @returns
     */
    deleteMember(data) {
        return request({
            url: '/app/xypm/admin/member/destroy',
            method: 'delete',
            data
        })
    },

    /**
     * 成员列表
     * @returns
     */
    getFamilyList(params = {"buildtype":"house"}) {
        return request({
            url: '/app/xypm/admin/member/family',
            method: 'get',
            params
        })
    },

    /**
     * 添加成员
     * @returns
     */
    addFamily(params = {}) {
        return request({
            url: '/app/xypm/admin/member/savefamily',
            method: 'post',
            data: params
        })
    },

    /**
     * 编辑成员
     * @returns
     */
    editFamily(id, data = {}) {
        return request({
            url: '/app/xypm/admin/member/updatefamily?id=' + id,
            method: 'put',
            data
        })
    },

    /**
     * 解绑成员
     * @returns
     */
    unbind(id, data = {}) {
        return request({
            url: '/app/xypm/admin/member/unbind?id=' + id,
            method: 'put',
            data
        })
    },
}
