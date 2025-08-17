<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-row  :gutter="32">
        <a-col :span="6">
          <a-form-item label="ID" field="id">
            <a-input v-model="searchForm.id" placeholder="ID" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :span="6">
          <a-form-item label="订单号" field="order_sn">
            <a-input v-model="searchForm.order_sn" placeholder="订单号" allow-clear />
          </a-form-item>
        </a-col>

        <a-col :span="6">
          <a-form-item label="充值会员" field="user_nickname">
            <a-input v-model="searchForm.user_nickname" placeholder="充值会员" allow-clear />
          </a-form-item>
        </a-col>
          <a-col :span="6">
            <a-form-item label="充值金额" field="money">
              <a-input-number
                  v-model="searchForm.money[0]"
                  placeholder="最小值"
                  allow-clear
              />
              <a-input-number
                  v-model="searchForm.money[1]"
                  placeholder="最大值"
                  allow-clear
              />

            </a-form-item>
          </a-col>
        </a-row>
        <a-row :gutter="32">
          <a-col :span="6">
            <a-form-item label="实际支付金额" field="pay_fee">
              <a-input-number
                  v-model="searchForm.pay_fee[0]"
                  placeholder="最小值"
                  allow-clear
              />
              <a-input-number
                  v-model="searchForm.money[1]"
                  placeholder="最大值"
                  allow-clear
              />

            </a-form-item>
          </a-col>
        <a-col :span="6">
          <a-form-item label="订单状态" field="status">
            <a-select
                v-model="searchForm.status"
                :options="statusTypes"
                placeholder="请选择订单状态"
                allow-clear
            />
          </a-form-item>
        </a-col>

        <a-col :span="6">
          <a-form-item label="支付平台" field="platform">
            <a-select
                v-model="searchForm.platform"
                :options="platformTypes"
                placeholder="请选择支付平台"
                allow-clear
            />
          </a-form-item>
        </a-col>

        <a-col :span="6">
          <a-form-item label="创建时间" field="createtime">
            <a-range-picker
                v-model="searchForm.createtime"
                show-time
                format="YYYY-MM-DD HH:mm:ss"
                @change="handleDateChange"
            />
          </a-form-item>
        </a-col>
        </a-row>
      </template>

      <!-- 金额列 -->
      <template #money="{ record }">
        <span>¥{{ record.money }}</span>
      </template>

      <!-- 支付金额列 -->
      <template #total_fee="{ record }">
        <span>¥{{ record.total_fee }}</span>
      </template>

      <!-- 实际支付金额列 -->
      <template #pay_fee="{ record }">
        <span>¥{{ record.pay_fee }}</span>
      </template>

      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="getStatusColor(record.status)">
          {{ getStatusText(record.status) }}
        </a-tag>
      </template>

      <!-- 平台列 -->
      <template #platform="{ record }">
        <a-tag :color="record.platform === 'wxMiniProgram' ? 'blue' : 'green'">
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

    </sa-table>

    <!-- 订单详情弹窗 -->
    <!--    <order-detail ref="detailRef" />-->
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/recharge/order' // 调整为充值订单API
// import OrderDetail from './detail.vue'

// 引用定义
const crudRef = ref()
const detailRef = ref()

// 状态选项
const statusTypes = [
  { label: '全部', value: '' },
  { label: '未支付', value: 0 },
  { label: '已支付', value: 1 },
  { label: '已取消', value: -1 },
  { label: '交易关闭', value: -2 },
]

// 平台选项
const platformTypes = [
  { label: '全部', value: '' },
  { label: '微信小程序', value: 'wxMiniProgram' },
  { label: '微信公众号', value: 'wxOfficialAccount' },
]

// 搜索表单
const searchForm = ref({
  id: '',
  order_sn: '',
  user_nickname: '',
  user_id: undefined,
  status: '',
  money: ['',''],
  pay_fee: ['',''],
  createtime: [],
})

// 处理日期变化
const handleDateChange = (dateArray) => {
  searchForm.value.createtime = dateArray
}

// 获取状态文本
const getStatusText = (status) => {
  const map = {
    '-2': '交易关闭',
    '-1': '已取消',
    '0': '未支付',
    '1': '已支付'
  }
  return map[status] || '未知状态'
}

// 获取状态颜色
const getStatusColor = (status) => {
  const map = {
    '-2': 'gray',
    '-1': 'orange',
    '0': 'red',
    '1': 'green'
  }
  return map[status] || 'gray'
}

// SaTable 基础配置
const options = reactive({
  api: api.getOrderList, // 调整为获取充值订单列表API
  rowSelection: { showCheckedAll: true },
  add: {
    show: false // 充值订单通常不需要手动添加
  },
  edit: {
    show: false // 充值订单通常不需要编辑
  },
  delete: {
    show: true,
    auth: ['/app/xypm/recharge/order/destroy'],
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
    width: 120,
    buttons: [
      {
        title: '详情',
        auth: ['/app/xypm/rechargeOrder/detail'],
        click: (record) => {
          detailRef.value?.open(record)
        }
      }
    ]
  }
})

// SaTable 列配置
const columns = reactive([
  { title: 'ID', dataIndex: 'id', width: 80 },
  { title: '订单号', dataIndex: 'order_sn', width: 200 },
  { title: '充值会员', dataIndex: 'nickname', width: 100 },
  { title: '充值金额', dataIndex: 'money', slotName: 'money', width: 120 },
  { title: '手续费', dataIndex: 'pay_fee', slotName: 'pay_fee', width: 120 },
  { title: '支付平台', dataIndex: 'platform', slotName: 'platform', width: 120 },
  { title: '订单状态', dataIndex: 'status_text', width: 120 },
  { title: '创建时间', dataIndex: 'createtime', width: 200 },
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