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

        <a-col :span="12">
          <a-form-item label="面值" field="facevalue" required>
            <a-input-number v-model="formData.facevalue" :min="1" placeholder="请输入面值" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="售价" field="buyprice" required>
            <a-input v-model="formData.buyprice"  placeholder="请输入售价" />
          </a-form-item>
        </a-col>

      </a-row>
      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="备注" field="remark">
            <a-textarea v-model="formData.remark" placeholder="请输入备注" />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="16">
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
import api from '../../api/recharge/recharge.js'



const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')
const buildLoading = ref(false)
const buildOptions = ref([])

const title = computed(() => {
  return '' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 表单信息
const formData = reactive({
  id: null,
  facevalue: '',
  buyprice: '',
  remark: '',
  status: 'normal',

})

// 验证规则
const rules = {
  facevalue: [{ required: true, message: '请输入面值' }],
  buyprice: [{ required: true, message: '请输入售价' }],
}

const initPage = async () => {


}



// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true


  // 初始化楼宇选项
  await initPage()
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
    try {
      let data = { ...formData }
      let result = {}

      if (mode.value === 'add') {
        // 添加数据
        data.id = undefined
        result = await api.add(data)
      } else {
        // 修改数据
        result = await api.edit(data.id, data)
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
const close = () => {
  visible.value = false
}

defineExpose({ open, setFormData })
</script>