<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="600"
      :title="title"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <!-- 表单信息 start -->
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="标题" field="title">
            <a-input v-model="formData.title" placeholder="请输入标题" />
          </a-form-item>
        </a-col>
      </a-row>
        <a-row :gutter="16">
          <a-col :span="24">
            <a-form-item label="缩略图" field="image">
              <sa-upload-image v-model="formData.image"  />
            </a-form-item>
          </a-col>
        </a-row>

        <a-row :gutter="16">
          <a-col :span="24">
            <a-form-item label="内容" field="content">
              <a-textarea v-model="formData.content" placeholder="请输入内容"  class="h-28" show-word-limit allow-clear />
            </a-form-item>
          </a-col>
        </a-row>


        <a-col :span="24">
          <a-form-item label="状态" field="status">
            <sa-radio v-model="formData.status" dict="xypm_data_status" placeholder="请选择状态" />
          </a-form-item>
        </a-col>

    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/notice'

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

// Options for building types


let title = computed(() => {
  return '楼宇信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 表单信息
const formData = reactive({
  id: null,
  title: '',
  image: '',
  content: '',
  remark: '',
  status: 'normal',

})

// 验证规则
const rules = {
  title: [{ required: true, message: '标题必需填写' }],

}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true


  if (type == 'edit') {
    const {data} = await api.read(id)

    setFormData(data)
  }
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
    if (mode.value === 'add') {
      // 添加数据
      data.id = undefined
      result = await api.addNotice(data)
    } else {
      // 修改数据
      result = await api.editNotice(data.id, data)
    }
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
