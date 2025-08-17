<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="4">
          <a-form-item label="ID" field="id">
            <a-input v-model="searchForm.id" placeholder="ID" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="8">
          <a-form-item label="订单号" field="order_sn">
            <a-input v-model="searchForm.order_sn" placeholder="订单号" allow-clear />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="用户ID" field="user_id">
            <a-input-number v-model="searchForm.user_id" placeholder="用户ID" allow-clear />
          </a-form-item>
        </a-col>

        <a-col :span="4">
          <a-form-item label="姓名" field="realname">
            <a-input v-model="searchForm.realname" placeholder="姓名" allow-clear />
          </a-form-item>
        </a-col>

        <a-col :span="4">
          <a-form-item label="电话" field="mobile">
            <a-input v-model="searchForm.mobile" placeholder="电话" allow-clear />
          </a-form-item>
        </a-col>



        <a-col :span="4">
          <a-form-item label="房产类型" field="buildtype">
            <a-select
                v-model="searchForm.buildtype"
                :options="buildTypes"
                placeholder="请选择房产类型"
                allow-clear
            />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="房产名称" field="buildname">
            <a-input v-model="searchForm.buildname" placeholder="房产名称" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="4">
          <a-form-item label="支付金额" field="total_fee">
            <a-input
                v-model="searchForm.total_fee[0]"
                placeholder="最小值"
                allow-clear
            />
            <a-input
                v-model="searchForm.total_fee[1]"
                placeholder="最大值"
                allow-clear
            />

          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="订单状态" field="status">
            <a-select
                v-model="searchForm.status"
                :options="statusTypes"
                placeholder="请选择订单状态"
                allow-clear
            />
          </a-form-item>
        </a-col>

        <a-col :span="4">
          <a-form-item label="支付方式" field="pay_type">
            <a-select
                v-model="searchForm.pay_type"
                :options="payTypes"
                placeholder="请选择支付方式"
                allow-clear
            />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="支付平台" field="platform">
            <a-select
                v-model="searchForm.platform"
                :options="platformTypes"
                placeholder="请选择支付平台"
                allow-clear
            />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="创建时间" field="createtime">
            <a-range-picker
                v-model="searchForm.createtime"
                show-time
                format="YYYY-MM-DD HH:mm:ss"
                @change="handleDateChange"
            />
          </a-form-item>
        </a-col>
      </template>

      <!-- 金额列 -->
<!--      <template #amount="{ record }">-->
<!--        <div>总金额: ¥{{ record.total_amount }}</div>-->
<!--        <div>支付金额: ¥{{ record.total_fee }}</div>-->
<!--        <div>实付金额: ¥{{ record.pay_fee }}</div>-->
<!--      </template>-->

      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="getStatusColor(record.status)">
          {{ getStatusText(record.status) }}
        </a-tag>
      </template>

      <!-- 房产类型列 -->
      <template #buildtype="{ record }">
        <a-tag :color="getBuildTypeColor(record.buildtype)">
          {{ getBuildTypeText(record.buildtype) }}
        </a-tag>
      </template>

      <!-- 支付方式列 -->
      <template #pay_type="{ record }">
        <a-tag :color="record.pay_type === 'wechat' ? 'green' : 'blue'">
          {{ record.pay_type === 'wechat' ? '微信支付' : '余额支付' }}
        </a-tag>
      </template>

      <!-- 平台列 -->
      <template #platform="{ record }">
        <a-tag :color="record.platform === 'wxMiniProgram' ? 'blue' : 'green'" v-if="record.platform">
          {{ record.platform === 'wxMiniProgram' ? '微信小程序' : '微信公众号' }}
        </a-tag>
      </template>

      <!-- 时间列 -->
      <template #time="{ record }">
        <div v-if="record.paytime">
          <div>创建: {{ $tool.formatTime(record.createtime) }}</div>
          <div>支付: {{ $tool.formatTime(record.paytime) }}</div>
        </div>
        <div v-else>
          创建: {{ $tool.formatTime(record.createtime) }}
        </div>
      </template>

      <!-- 操作前置扩展 -->
      <template #operationBeforeExtend="{ record }">
        <a-space size="mini">


        </a-space>
      </template>
    </sa-table>

    <!-- 订单详情弹窗 -->
<!--    <order-detail ref="detailRef" />-->
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message, Modal } from '@arco-design/web-vue'
import api from '../../api/bill/order'
// import OrderDetail from './detail.vue'

// 引用定义
const crudRef = ref()
const detailRef = ref()

// 房产类型选项
const buildTypes = [
  { label: '全部', value: '' },
  { label: '房屋', value: 'house' },
  { label: '商铺', value: 'shop' },
  { label: '车位', value: 'parking' },
  { label: '储物间', value: 'garage' },
]

// 状态选项
const statusTypes = [
  { label: '全部', value: '' },
  { label: '待付款', value: 0 },
  { label: '已付款', value: 1 },
  { label: '已取消', value: -1 },
]

// 支付方式选项
const payTypes = [
  { label: '全部', value: '' },
  { label: '微信支付', value: 'wechat' },
  { label: '余额支付', value: 'balance' },
]

// 平台选项
const platformTypes = [
  { label: '全部', value: '' },
  { label: '微信小程序', value: 'wxMiniProgram' },
  { label: '微信公众号', value: 'wxOfficialAccount' },
]

// 搜索表单
const searchForm = ref({
  order_sn: '',
  user_id: undefined,
  realname: '',
  mobile: '',
  buildname: '',
  total_fee:['',''],
  buildtype: '',
  status: '',
  pay_type: '',
  platform: '',
  createtime: [],
})

// 处理日期变化
const handleDateChange = (dateArray) => {
  searchForm.value.createtime = dateArray
}

// 获取状态文本
const getStatusText = (status) => {
  const map = {
    '-1': '已取消',
    '0': '待付款',
    '1': '已付款'
  }
  return map[status] || '未知状态'
}

// 获取状态颜色
const getStatusColor = (status) => {
  const map = {
    '-1': 'gray',
    '0': 'orange',
    '1': 'green'
  }
  return map[status] || 'gray'
}

// 获取房产类型文本
const getBuildTypeText = (type) => {
  const map = {
    'house': '房屋',
    'shop': '商铺',
    'parking': '车位',
    'garage': '储物间'
  }
  return map[type] || '未知类型'
}

// 获取房产类型颜色
const getBuildTypeColor = (type) => {
  const map = {
    'house': 'blue',
    'shop': 'purple',
    'parking': 'cyan',
    'garage': 'green'
  }
  return map[type] || 'gray'
}

// 打开详情弹窗
const openDetail = (record) => {
  detailRef.value?.open(record)
}

// 取消订单
const handleCancel = (record) => {
  Modal.confirm({
    title: '确认取消订单',
    content: `确定要取消订单 ${record.order_sn} 吗？`,
    okText: '确认取消',
    cancelText: '再想想',
    onOk: async () => {
      try {
        const resp = await api.cancelOrder(record.id)
        if (resp.code === 200) {
          Message.success('订单取消成功')
          crudRef.value?.refresh()
        }
      } catch (error) {
        Message.error('取消失败：' + error.message)
      }
    }
  })
}

// SaTable 基础配置
const options = reactive({
  api: api.getOrderList,
  rowSelection: { showCheckedAll: true },
  add: {
    show: false // 交费订单通常不需要手动添加
  },
  edit: {
    show: false // 交费订单通常不需要编辑
  },
  delete: {
    show: true,
    auth: ['/app/xypm/bill/order/destroy'],
    func: async (params) => {
      const resp = await api.deleteOrder(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
  operationColumn: {
    show: true,
    width: 150
  }
})

// SaTable 列配置
const columns = reactive([
  { title: 'ID', dataIndex: 'id',width:100 },
  { title: '订单号', dataIndex: 'order_sn',width:160,  },
  { title: '姓名', dataIndex: 'realname',width:100, },
  { title: '电话', dataIndex: 'mobile', width:120},
  { title: '房产类型', dataIndex: 'buildtype', width:120,slotName: 'buildtype',},
  { title: '房产名称', dataIndex: 'buildname',width: 150 },
  { title: '支付金额', dataIndex: 'total_fee',width:120},
  { title: '支付方式', dataIndex: 'pay_type', slotName: 'pay_type', width: 100},
  { title: '支付时间', dataIndex: 'paytime_text',width: 180 },
  { title: '支付平台', dataIndex: 'platform', slotName: 'platform',},
  { title: '订单状态', dataIndex: 'status', slotName: 'status',width:150 },
  { title: '创建时间', dataIndex: 'createtime',width: 180 }
])

// 页面数据初始化
const initPage = async () => {
  // Initialize any necessary data here
}

// SaTable 数据请求
const refresh = async () => {
  crudRef.value?.refresh()
}

// 页面加载完成执行
onMounted(async () => {
  initPage()
  refresh()
})
</script>

<style scoped>
.ma-content-block {
  margin: 20px;
}
</style>