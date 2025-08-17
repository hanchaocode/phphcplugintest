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

      <a-col :span="24">
        <a-form-item label="编号" field="number">
          <a-input v-model="formData.number" placeholder="请输入编号" />
        </a-form-item>
      </a-col>
        <a-col :span="12">
          <a-form-item label="建筑面积" field="buildarea" required>
            <a-input-number
                v-model="formData.buildarea"
                :min="0"
                :precision="2"
                placeholder="请输入建筑面积"
            />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="业主姓名" field="ownername">
            <a-input v-model="formData.ownername" placeholder="请输入业主姓名" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="业主电话" field="mobile">
            <a-input v-model="formData.mobile" placeholder="请输入业主电话" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="开始收费日期" field="billdate">
            <a-date-picker
                v-model="formData.billdate"
                style="width: 100%"
                format="YYYY-MM-DD"
                value-format="YYYY-MM-DD"
                placeholder="请选择开始收费日期"
            />

          </a-form-item>
        </a-col>
      <a-col :span="24">
        <a-form-item label="状态" field="status">
          <a-select v-model="formData.status"  dict="data_status" placeholder="请选择状态">
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
import api from '../../api/build/garage'
import buildApi from '../../api/build'


const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')
const buildLoading = ref(false)
const buildOptions = ref([])
const statusTypes = [
  { label: '未绑定户主', value: 0 },
  { label: '已绑定户主', value: 1 },

]
const title = computed(() => {
  return '储物间信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 表单信息
const formData = reactive({
  id: null,
  number: '',
  ownername: '',
  mobile: '',
  buildarea: 0,
  billdate: '',
})

// 验证规则
const rules = {
  number: [{ required: true, message: '请输入编号' }],
  // mobile: [
  //   { pattern: /^1[3-9]\d{9}$/, message: '请输入正确的手机号码' },
  // ],
}

const initPage = async () => {
  const deptResp = await buildApi.getBuildOptions()
  buildOptions.value = deptResp.data

}



// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true


  // 初始化储物间选项
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
        result = await api.addGarage(data)
      } else {
        // 修改数据
        result = await api.editGarage(data.id, data)
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