<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="600"
      title="新建模板"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <!-- 表单信息 start -->
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="名称" field="名称">
            <a-input v-model="formData.name" placeholder="请输入名称" />
          </a-form-item>
        </a-col>
      </a-row>


      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="封面图" field="cover">
            <sa-upload-image v-model="formData.cover" />
          </a-form-item>
        </a-col>
      </a-row>


        <a-col :span="24">
          <a-form-item label="类型" field="type">
            <a-select
                v-model="formData.type"
                :options="typeList"
                placeholder="请选择类型"
                allow-clear
            />
          </a-form-item>
        </a-col>

    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/page'

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

// Options for building types


const typeList = [
  { label: '首页模板', value: 'index' },
  { label: '商城模板', value: 'shop' },
  { label: '用户中心模板', value: 'user' },

]

// 表单信息
const formData = reactive({
  id: null,
  name: '',
  type: '',
  cover: '',


})

// 验证规则
const rules = {
  name: [{ required: true, message: '名称必需填写' }],

}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true

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
    result = await api.addPage(data.id, data)

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
