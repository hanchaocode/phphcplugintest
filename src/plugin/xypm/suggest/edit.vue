<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="600"
      title="详情"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <!-- 表单信息 start -->
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">

        <a-row :gutter="10">
          <a-col :span="24">
            <a-form-item label="房产" field="title">
             {{formData.buildname}}
            </a-form-item>
          </a-col>
        </a-row>
        <a-row :gutter="10">
          <a-col :span="24">
            <a-form-item label="姓名" >
              {{formData.realname}}
            </a-form-item>
          </a-col>
        </a-row>
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="电话" >
            {{formData.mobile}}
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="内容">
            {{formData.content}}
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="状态">
            {{formData.status_text}}
          </a-form-item>
        </a-col>
      </a-row>
      <a-col :span="24">
        <a-form-item label="处理" field="status">
          <a-select v-model="formData.status" placeholder="请选择状态">
            <a-option  v-for="type in statusTypes" :key="type.value" :value="type.value">   {{ type.label }}</a-option>
          </a-select>
        </a-form-item>
      </a-col>
    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/suggest'

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

// Options for building types

const statusTypes = [
  { label: '待处理', value: 0 },
  { label: '处理中', value: 1 },
  { label: '已处理', value: 2 },
]
let title = computed(() => {
  return '编辑建议'
})

// 表单信息
const formData = reactive({
  id: null,
  buildname: '',
  realname: '',
  mobile: '',
  content: '',
  status: '',
  status_text: '',

})

// 验证规则
const rules = {


}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true



  const {data} = await api.read(id)
  data.status = parseInt(data.status)
  setFormData(data)

}

// 设置数据
const setFormData = async (data) => {
  for (const key in formData) {
    if (data[key] !== null && data[key] !== undefined) {
      formData[key] = data[key]
    }
  }
}

// 数据保存
const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }
    let result = {}

      // 修改数据
      result = await api.editSuggest(data.id, {"status":formData.status})

    if (result.code === 200) {
      Message.success('操作成功')
      emit('success')
      done(true)
    }
    // 防止连续点击提交
    setTimeout(() => {
      loading.value = false
    }, 500)
  }
  done(false)
}

// 关闭弹窗
const close = () => (visible.value = false)

defineExpose({ open, setFormData })
</script>
