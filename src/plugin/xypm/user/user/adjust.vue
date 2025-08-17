<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="500"
      title="调整余额"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <!-- 表单信息 start -->
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="24">
        <a-col :span="12">

          <a-form-item label="当前余额"  >
            {{formData.money}}
          </a-form-item>
        </a-col>

      </a-row>
      <a-row :gutter="24">
        <a-col :span="12">
          <a-form-item label="调整数额" field="num">
            <a-input-number v-model="formData.num" placeholder="请输入数额" />

          </a-form-item>
          <a-col :span="24">
          <a-alert>负数为减少余额</a-alert>
          </a-col>
        </a-col>
      </a-row>
      <a-row :gutter="24">
        <a-col :span="24">
        <a-form-item label="备注" field="remark">
          <a-textarea v-model="formData.remark" placeholder="请输入备注" />
        </a-form-item>
        </a-col>
      </a-row>


    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/user'

const emit = defineEmits(['success'])
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const userId = ref()
const mode = ref('')

let title = computed(() => {
  return '调整余额'
})

const formData = reactive({
  id: null,
  money:'',
  num:'',
  remark:'',
})

const rules = {
  money: [{ required: true, message: '余额不能为空' }],
}


// 打开弹框
const open = async (row) => {
  userId.value = row.id
  visible.value = true


  const {data} = await api.read(row.id)
  setFormData(data)
}


const setFormData = async (data) => {
  for (const key in formData) {
    if (data[key] !== null && data[key] !== undefined) {
      formData[key] = data[key]
    }
  }
}

const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }


    try {
      let result = {}
      result = await api.recharge(data.id, data)

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

const close = () => (visible.value = false)

defineExpose({ open, setFormData })
</script>

<style scoped>
:deep(.arco-form-item-label-col) {
  flex-basis: 100px;
}
</style>
