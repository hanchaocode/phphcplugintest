import { request } from '@/utils/request.js'

/**
 * 短信配置 API接口
 */
export default {
  /**
   * 数据列表
   * @returns
   */
  getPageList(params = {}) {
    return request({
      url: '/app/aliyuncms/SmsConfig/index',
      method: 'get',
      params
    })
  },




  /**
   * 更新数据
   * @returns
   */
  update(id, data = {}) {
    return request({
      url: '/app/aliyuncms/SmsConfig/update?id=' + id,
      method: 'put',
      data
    })
  },

}
