<template>
  <div class="ma-content-block">
    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :span="8">
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
          <a-form-item label="买家昵称" field="user_nickname">
            <a-input v-model="searchForm.user_nickname" placeholder="买家昵称" allow-clear />
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

        <a-col :span="8">
          <a-form-item label="下单时间" field="createtime">
            <a-range-picker
                v-model="searchForm.createtime"
                show-time
                style="width: 100%"
            />
          </a-form-item>
        </a-col>

        <a-col :span="8">
          <a-form-item label="支付时间" field="paytime">
            <a-range-picker
                v-model="searchForm.paytime"
                show-time
                style="width: 100%"
            />
          </a-form-item>
        </a-col>
      </template>

      <!-- 操作前置扩展 -->
      <template #operationBeforeExtend="{ record }">
        <a-space size="mini">
          <a-link v-auth="['/app/xypm/goods/order/detail']" @click="openDetail(record)">
            <icon-eye /> 详情
          </a-link>
          <a-link
              v-if="record.status === 1"
              v-auth="['/app/xypm/goods/order/delivery']"
              @click="handleDelivery(record)"
          >
            <icon-send /> 发货
          </a-link>
          <a-link
              v-if="record.status === 2"
              v-auth="['/app/xypm/goods/order/take_delivery']"
              @click="handleTakeDelivery(record)"
          >
            <icon-check-circle /> 确认收货
          </a-link>
        </a-space>
      </template>

      <!-- 状态列 -->
      <template #status="{ record }">
        <a-tag :color="statusColor[record.status]">
          {{ statusText[record.status] }}
        </a-tag>
      </template>
    </sa-table>

    <!-- 详情弹窗 -->
    <detail-modal ref="detailRef" />
  </div>
</template>

<script setup>
import { onMounted, ref, reactive } from 'vue'
import { Message, Modal } from '@arco-design/web-vue'
import api from '../../api/goods/order'
import DetailModal from './detail.vue'

const crudRef = ref()
const detailRef = ref()

// 订单状态选项
const statusTypes = [
  { label: '全部', value: '' },
  { label: '待支付', value: '0' },
  { label: '待发货', value: '1' },
  { label: '待收货', value: '2' },
  { label: '已完成', value: '3' },
  { label: '已取消', value: '-1' },
  { label: '已退款', value: '-2' },
]

// 状态显示文本
const statusText = {
  '0': '待支付',
  '1': '待发货',
  '2': '待收货',
  '3': '已完成',
  '-1': '已取消',
  '-2': '已退款',
}



// 状态标签颜色
const statusColor = {
  '0': 'orange',
  '1': 'blue',
  '2': 'purple',
  '3': 'green',
  '-1': 'red',
  '-2': 'gray',
}

// 搜索表单
const searchForm = ref({
  id:'',
  order_sn: '',

  user_nickname: '',
  status: '',
  createtime: [],
  paytime: [],
})

// SaTable 基础配置
const options = reactive({
  api: api.getOrderList,
  rowSelection: { showCheckedAll: true },
  add: {
    show: false,
  },
  edit: {
    show: false,
  },
  delete: {
    show: true,
    auth: ['/app/xypm/goods/order/destroy'],
    func: async (params) => {
      const resp = await api.deleteOrder(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'ID', dataIndex: 'id', width: 80 },
  { title: '订单号', dataIndex: 'order_sn', width: 200 },
  { title: '买家昵称', dataIndex: 'nickname', width: 150 },
  { title: '买家id', dataIndex: 'user_id', width: 150 },
  { title: '订单金额', dataIndex: 'total_fee', width: 120 },
  { title: '下单时间', dataIndex: 'createtime', width: 180, formatter: 'datetime' },
  { title: '支付时间', dataIndex: 'paytime_text', width: 180, formatter: 'datetime' },
  { title: '订单状态', dataIndex: 'status', slotName: 'status', width: 120 },
])

// 打开详情弹窗
const openDetail = (record) => {
  detailRef.value.open(record.id)
}


// 发货操作
const handleDelivery = (record) => {
  Modal.confirm({
    title: '确认发货',
    content: '确定货物已配送?',
    okText: '确定',
    cancelText: '取消',
    onOk: async () => {
      const resp = await api.deliveryOrder(record.id)
      if (resp.code === 200) {
        Message.success('发货成功')
        crudRef.value?.refresh()
      }
    }
  })
}

// 确认收货操作
const handleTakeDelivery = (record) => {
  Modal.confirm({
    title: '确认收货',
    content: '确保买家已经收到您的商品，提前确认收货?',
    okText: '确定',
    cancelText: '取消',
    onOk: async () => {
      const resp = await api.takeDeliveryOrder(record.id)
      if (resp.code === 200) {
        Message.success('确认收货成功')
        crudRef.value?.refresh()
      }
    }
  })
}
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
.order-head {
  background: #f7f7f7;
  padding: 0px 15px;
  width: 100%;
  font-weight: bold;
  margin-top: 5px;
  display: flex;
}

.order-head div {
  padding: 15px;
}

.item-top {
  background: #f7f7f7;
  padding: 10px;
  width: 100%;
  border: 1px solid #e6e6e6;
}

.item-content {
  border: 1px solid #e6e6e6;
  border-top: none;
  display: flex;
}

.obr {
  border-right: 1px solid #e6e6e6;
}

.item-goods {
  border-bottom: solid 1px #e6e6e6;
  padding: 15px;
  display: flex;
  align-items: center;
}

.item-goods:last-child {
  border: none;
}

.vhc {
  padding: 15px;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>