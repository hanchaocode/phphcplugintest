import { request } from '@/utils/request.js'

/**
 * 配置中心 API接口
 */
export default {
    /**
     * 获取配置列表
     * @returns
     */
    getConfigList(params = {}) {
        return request({
            url: '/app/xypm/admin/config/index',
            method: 'get',
            params
        })
    },

    /**
     * 读取配置
     * @returns
     */
    readConfig(name, data = {}) {
        return request({
            url: '/app/xypm/admin/config/set?name=' + name,
            method: 'get',
            data
        })
    },

    /**
     * 编辑配置
     * @returns
     */
    editConfig(data = {}) {
        return request({
            url: '/app/xypm/admin/config/edit' ,
            method: 'put',
            data
        })
    },


    /**
     * 图片 cdnurl配置
     * @returns
     */
    cdnUrl(data = {}) {
        return request({
            url: '/app/xypm/admin/config/cdnurl' ,
            method: 'put',
            data
        })
    },

}
