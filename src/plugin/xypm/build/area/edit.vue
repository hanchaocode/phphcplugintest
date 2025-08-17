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
          <a-form-item label="名称" field="name">
            <a-input v-model="formData.name" placeholder="请输入名称" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="类型" field="type" placeholder="请选择类型">
            <a-radio-group v-model="formData.type">
              <a-radio value="shop">商铺</a-radio>
              <a-radio value="parking">车位</a-radio>
            </a-radio-group>
          </a-form-item>
        </a-col>

        <a-col :span="24">
          <a-form-item label="备注" field="remark">
            <a-input v-model="formData.remark" placeholder="请输入备注" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="状态" field="status">
            <a-radio-group v-model="formData.status">
              <a-radio value="normal">正常</a-radio>
              <a-radio value="hidden">隐藏</a-radio>
            </a-radio-group>
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
import api from '../../api/build/area.js'

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

// Options for building types


let title = computed(() => {
  return '区域信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})
const areaTypes = [
  { label: '商铺', value: 'shop' },
  { label: '车位', value: 'parking' },
]
// 表单信息
const formData = reactive({
  id: null,
  name: '',
  type: '',
  remark: '',
  status: 'normal'

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
      result = await api.addArea(data)
    } else {
      // 修改数据
      result = await api.editArea(data.id, data)
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
