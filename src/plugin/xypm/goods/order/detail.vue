<template>
  <a-modal
      v-model:visible="visible"
      :width="1000"
      title="订单详情"
      :footer="false"
      :mask-closable="false"
  >
    <a-spin :loading="loading">
      <div class="order-detail">
        <!-- 订单基本信息 -->
        <div class="order-base-info">
          <a-descriptions :column="2" bordered>
            <a-descriptions-item label="订单号">{{ detail.order_sn }}</a-descriptions-item>
            <a-descriptions-item label="订单状态">
              <a-tag :color="statusColor[detail.status]">
                {{ detail.status_text }}
              </a-tag>
            </a-descriptions-item>
            <a-descriptions-item label="买家信息">
              {{ detail.user.nickname }}(ID:{{ detail.user.id }})
            </a-descriptions-item>
            <a-descriptions-item label="订单金额">{{ detail.total_fee }}元</a-descriptions-item>
            <a-descriptions-item label="下单时间">{{ detail.createtime }}</a-descriptions-item>
            <a-descriptions-item label="支付时间">{{ detail.paytime_text }}</a-descriptions-item>
            <a-descriptions-item label="支付方式">{{ detail.pay_type_text }}</a-descriptions-item>
            <a-descriptions-item label="订单来源">{{ detail.platform_text }}</a-descriptions-item>
          </a-descriptions>
        </div>

        <!-- 收货信息 -->
        <div class="order-section">
          <h3 class="section-title">收货信息</h3>
          <div class="section-content">
            <p>收货人：{{ detail.realname }}</p>
            <p>联系电话：{{ detail.mobile }}</p>
            <p>收货地址：{{ detail.buildname }}</p>
          </div>
        </div>

        <!-- 商品信息 -->
        <div class="order-section">
          <h3 class="section-title">商品信息</h3>
          <div class="goods-list">
            <div class="goods-header flex">
              <div class="goods-col goods-name">商品</div>
              <div class="goods-col goods-price">单价(元)</div>
              <div class="goods-col goods-num">数量</div>
              <div class="goods-col goods-subtotal">小计(元)</div>
            </div>

            <div v-for="(item, index) in detail.item" :key="index" class="goods-item flex">
              <div class="goods-col goods-name">
                <div class="goods-info flex">
                  <a-image
                      width="60"
                      height="60"
                      :src="item.goods_image"
                      :preview="false"
                      class="goods-image"
                  />
                  <div class="goods-desc">
                    <p class="goods-title">{{ item.goods_title }}</p>
                    <p class="goods-sku">{{ item.goods_sku_text }}</p>
                  </div>
                </div>
              </div>
              <div class="goods-col goods-price">{{ item.goods_price }}</div>
              <div class="goods-col goods-num">{{ item.buy_num }}</div>
              <div class="goods-col goods-subtotal">{{ (item.goods_price * item.buy_num).toFixed(2) }}</div>
            </div>
          </div>
        </div>

        <!-- 订单金额 -->
        <div class="order-section">
          <h3 class="section-title">订单金额</h3>
          <div class="order-amount">
            <div class="amount-item flex">
              <span class="amount-label">商品总价：</span>
              <span class="amount-value">{{ detail.total_fee }}元</span>
            </div>
            <div class="amount-item flex">
              <span class="amount-label">实付金额：</span>
              <span class="amount-value">{{ detail.total_fee }}元</span>
            </div>
          </div>
        </div>

        <!-- 操作按钮 -->
        <div class="order-actions" v-if="detail.status === 1 || detail.status === 2">
          <a-space>
            <a-button
                v-if="detail.status === 1"
                type="primary"
                @click="handleDelivery"
                v-auth="['/app/xypm/goods/order/delivery']"
            >
              发货
            </a-button>
            <a-button
                v-if="detail.status === 2"
                type="primary"
                @click="handleTakeDelivery"
                v-auth="['/app/xypm/goods/order/take_delivery']"
            >
              确认收货
            </a-button>
            <a-button @click="handleLogistics" v-if="detail.status > 1">
              查看物流
            </a-button>
          </a-space>
        </div>
      </div>
    </a-spin>

    <!-- 物流信息弹窗 -->
    <a-modal
        v-model:visible="logisticsVisible"
        title="物流信息"
        :footer="false"
        :width="600"
    >
      <div id="logisticsInfo" v-if="logisticsData.length > 0">
        <div v-for="(item, index) in logisticsData" :key="index" class="logistics-item">
          <p class="logistics-time">{{ item.AcceptTime }}</p>
          <p class="logistics-station">{{ item.AcceptStation }}</p>
        </div>
      </div>
      <a-empty v-else description="暂无物流信息" />
    </a-modal>
  </a-modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Message, Modal } from '@arco-design/web-vue'
import api from '../../api/goods/order'

const visible = ref(false)
const loading = ref(false)
const logisticsVisible = ref(false)
const logisticsData = ref([])

// 状态标签颜色
const statusColor = {
  '0': 'orange',
  '1': 'blue',
  '2': 'purple',
  '3': 'green',
  '-1': 'red',
  '-2': 'gray',
}

// 订单详情数据
const detail = reactive({
  id: null,
  order_sn: '',
  status: '',
  status_text: '',
  total_fee: 0,
  pay_type_text: '',
  createtime: '',
  paytime_text: '',
  platform_text: '',
  realname: '',
  mobile: '',
  buildname: '',
  user: {
    id: '',
    nickname: ''
  },
  item: []
})

// 打开详情弹窗
const open = async (id) => {
  visible.value = true
  loading.value = true
  try {
    const { data } = await api.read(id)
    Object.assign(detail, data)
  } finally {
    loading.value = false
  }
}

// 发货操作
const handleDelivery = () => {
  Modal.confirm({
    title: '确认发货',
    content: '确定货物已配送?',
    okText: '确定',
    cancelText: '取消',
    onOk: async () => {
      const resp = await api.deliveryOrder(detail.id)
      if (resp.code === 200) {
        Message.success('发货成功')
        visible.value = false
      }
    }
  })
}

// 确认收货操作
const handleTakeDelivery = () => {
  Modal.confirm({
    title: '确认收货',
    content: '确保买家已经收到您的商品，提前确认收货?',
    okText: '确定',
    cancelText: '取消',
    onOk: async () => {
      const resp = await api.takeDeliveryOrder(detail.id)
      if (resp.code === 200) {
        Message.success('确认收货成功')
        visible.value = false
      }
    }
  })
}

// 查看物流
const handleLogistics = async () => {
  logisticsVisible.value = true
  const resp = await api.getLogistics(detail.id)
  if (resp.code === 200) {
    logisticsData.value = resp.data.Traces || []
  }
}

defineExpose({ open })
</script>

<style scoped>
.order-detail {
  padding: 10px;
}

.order-section {
  margin-top: 20px;
  padding: 15px;
  background: #f7f7f7;
  border-radius: 4px;
}

.section-title {
  margin-bottom: 15px;
  font-size: 16px;
  font-weight: bold;
}

.goods-list {
  border: 1px solid #e6e6e6;
  border-radius: 4px;
}

.goods-header {
  padding: 10px 15px;
  background: #f7f7f7;
  font-weight: bold;
  border-bottom: 1px solid #e6e6e6;
}

.goods-item {
  padding: 15px;
  border-bottom: 1px solid #e6e6e6;
}

.goods-item:last-child {
  border-bottom: none;
}

.goods-col {
  padding: 0 10px;
}

.goods-name {
  width: 50%;
}

.goods-price, .goods-num, .goods-subtotal {
  width: 15%;
  text-align: center;
}

.goods-info {
  align-items: center;
}

.goods-image {
  margin-right: 10px;
}

.goods-title {
  margin-bottom: 5px;
  font-weight: bold;
}

.goods-sku {
  color: #999;
  font-size: 12px;
}

.order-amount {
  padding: 10px;
  background: #fff;
  border-radius: 4px;
}

.amount-item {
  margin-bottom: 10px;
  justify-content: flex-end;
}

.amount-label {
  width: 100px;
  text-align: right;
}

.amount-value {
  width: 100px;
  text-align: right;
  font-weight: bold;
}

.order-actions {
  margin-top: 20px;
  text-align: center;
}

.logistics-item {
  padding: 10px 0;
  border-bottom: 1px dashed #e6e6e6;
}

.logistics-item:last-child {
  border-bottom: none;
}

.logistics-time {
  font-weight: bold;
  margin-bottom: 5px;
}

.logistics-station {
  color: #666;
}

.flex {
  display: flex;
}
</style>