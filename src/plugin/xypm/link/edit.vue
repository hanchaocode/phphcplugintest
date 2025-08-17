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
    <!-- 表单信息 start -->
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="分类类型" field="type" required>
            <a-select
                v-model="formData.type"
                :options="typeOptions"
                placeholder="请选择分类类型"
            />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row>
        <a-col :span="12">
          <a-form-item label="名称" field="name" required>
            <a-input v-model="formData.name" placeholder="请输入名称" />
          </a-form-item>
        </a-col>

      </a-row>
      <a-row>
        <a-col :span="12">
          <a-form-item label="路径" field="url" required>
            <a-input v-model="formData.url" placeholder="请输入路径" />
          </a-form-item>
        </a-col>
      </a-row>


    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/link'

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')
const categoryTree = ref([])

let title = computed(() => {
  return '商品分类' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 分类类型选项
const typeOptions = [
  { label: '基础链接', value: 'basic' },
  { label: '会员中心', value: 'user' },
  { label: '公告链接', value: 'notice' },
  { label: '活动链接', value: 'activity' },
]

// 表单信息
const formData = reactive({
  id: null,
  name: '',
  type: 'basic',
  url: '',

})

// 验证规则
const rules = {
  name: [{ required: true, message: '名称不能为空' }],
  url: [{ required: true, message: '请输入路径' }],
}



// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  formRef.value?.clearValidate()
  visible.value = true


  // 重置表单数据
  if (type === 'add') {
    Object.assign(formData, {
      id: null,
      name: '',
      intro: '',

      image: '',
      pid: 0,
      weigh: 0,
      status: 'normal'
    })
  } else {
    const { data } = await api.read(id)
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

    try {
      let result = {}
      if (mode.value === 'add') {
        data.id = undefined
        result = await api.addLink(data)
      } else {
        result = await api.editLink(data.id, data)
      }

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
const close = () => (visible.value = false)

defineExpose({ open, setFormData })
</script>

<style scoped>
:deep(.arco-form-item-label-col) {
  flex-basis: 100px;
}
</style>