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
          <a-form-item label="楼宇名称" field="name">
            <a-input v-model="formData.name" placeholder="请输入楼宇名称" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="单元数量" field="unitnum">
            <a-input-number v-model="formData.unitnum" min="1" placeholder="请输入单元数量" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="楼宇层数" field="floornum">
            <a-input-number v-model="formData.floornum" min="1" placeholder="请输入楼宇层数" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="房屋数量" field="roomnum">
            <a-input-number v-model="formData.roomnum" min="1" placeholder="请输入房屋数量" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="权重" field="weigh">
            <a-input-number v-model="formData.weigh" min="0" placeholder="请输入权重" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="楼宇类型" field="status">
            <a-select v-model="formData.status" placeholder="请选择楼宇类型">

              <a-option  v-for="type in buildingTypes" :key="type.value" :value="type.value">   {{ type.label }}</a-option>
            </a-select>
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
import api from '../../api/build.js'

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

// Options for building types
const buildingTypes = [
  { label: '高层', value: 'high' },
  { label: '多层', value: 'multilayer' },
  { label: '别墅', value: 'villa' },
]

let title = computed(() => {
  return '楼宇信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 表单信息
const formData = reactive({
  id: null,
  name: '',
  unitnum: 1,
  floornum: 1,
  roomnum: 1,
  weigh: 0,
  status: 'high',
})

// 验证规则
const rules = {
  name: [{ required: true, message: '楼宇名称必需填写' }],
  unitnum: [{ required: true, message: '单元数量必需填写' }],
  floornum: [{ required: true, message: '楼宇层数必需填写' }],
  roomnum: [{ required: true, message: '房屋数量必需填写' }],
  weigh: [{ required: true, message: '权重必需填写' }],
  status: [{ required: true, message: '楼宇类型必需选择' }],
}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true
  // 初始化楼宇选项

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
      result = await api.addBuild(data)
    } else {
      // 修改数据
      result = await api.editBuild(data.id, data)
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
