<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="800"
      :title="title"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="订单号" field="order_sn">
            <a-input v-model="formData.order_sn" disabled />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="订单状态" field="status">
            <a-select v-model="formData.status" :options="statusTypes" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="买家昵称" field="nickname">
            <a-input v-model="formData.user.nickname" disabled />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="买家ID" field="user_id">
            <a-input v-model="formData.user.id" disabled />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="订单金额" field="total_fee">
            <a-input-number v-model="formData.total_fee" disabled />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="支付方式" field="pay_type">
            <a-input v-model="formData.pay_type_text" disabled />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="下单时间" field="createtime">
            <a-input v-model="formData.createtime_text" disabled />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="支付时间" field="paytime">
            <a-input v-model="formData.paytime_text" disabled />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="收货信息" field="address">
            <a-textarea
                v-model="formData.address_info"
                :auto-size="{ minRows: 3, maxRows: 5 }"
                disabled
            />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="备注" field="remark">
            <a-textarea
                v-model="formData.remark"
                :auto-size="{ minRows: 2, maxRows: 4 }"
                placeholder="请输入订单备注"
            />
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
  </component>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/goods/order.js'

const emit = defineEmits(['success'])

const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

const title = computed(() => {
  return mode.value === 'add' ? '新增订单' : '编辑订单'
})

// 订单状态选项
const statusTypes = [
  { label: '待支付', value: '0' },
  { label: '待发货', value: '1' },
  { label: '待收货', value: '2' },
  { label: '已完成', value: '3' },
  { label: '已取消', value: '-1' },
  { label: '已退款', value: '-2' },
]

// 表单数据
const formData = reactive({
  id: null,
  order_sn: '',
  status: '',
  total_fee: 0,
  pay_type_text: '',
  createtime_text: '',
  paytime_text: '',
  remark: '',
  address_info: '',
  user: {
    id: '',
    nickname: ''
  }
})

// 验证规则
const rules = {
  status: [{ required: true, message: '请选择订单状态' }]
}

// 打开弹窗
const open = async (type = 'edit', id = '') => {
  mode.value = type
  formRef.value?.clearValidate()
  visible.value = true

  if (type === 'add') {
    Object.assign(formData, {
      id: null,
      order_sn: '',
      status: '0',
      total_fee: 0,
      pay_type_text: '',
      createtime_text: '',
      paytime_text: '',
      remark: '',
      address_info: '',
      user: {
        id: '',
        nickname: ''
      }
    })
  } else {
    const { data } = await api.read(id)
    setFormData(data)
  }
}

// 设置表单数据
const setFormData = (data) => {
  Object.assign(formData, {
    id: data.id,
    order_sn: data.order_sn,
    status: String(data.status),
    total_fee: data.total_fee,
    pay_type_text: data.pay_type_text || '',
    createtime_text: data.createtime_text || '',
    paytime_text: data.paytime_text || '',
    remark: data.remark || '',
    address_info: `${data.realname} ${data.mobile}\n${data.buildname}`,
    user: {
      id: data.user?.id || '',
      nickname: data.user?.nickname || ''
    }
  })
}

// 提交表单
const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    try {
      let result = {}
      const params = {
        id: formData.id,
        status: formData.status,
        remark: formData.remark
      }


        result = await api.editOrder(params)


      if (result.code === 200) {
        Message.success('操作成功')
        emit('success')
        done(true)
      }
    } finally {
      loading.value = false
    }
  }
  done(false)
}

// 关闭弹窗
const close = () => {
  visible.value = false
}

defineExpose({ open })
</script>

<style scoped>
:deep(.arco-form-item-label-col) {
  flex-basis: 80px;
}
</style>