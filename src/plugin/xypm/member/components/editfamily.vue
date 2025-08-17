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
          <a-form-item label="身份" field="identity">
            <a-select
                v-model="formData.identity"
                placeholder="请选择身份"

            >
              <a-option  v-for="type in identityTypes" :key="type.value" :value="type.value">   {{ type.label }}</a-option>

            </a-select>
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="姓名" field="realname">
            <a-input v-model="formData.realname" placeholder="请输入姓名" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="手机" field="mobile">
            <a-input v-model="formData.mobile"   placeholder="请输入手机" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="身份证号" field="idcard">

            <a-input v-model="formData.idcard" placeholder="请输入身份证号" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="备注" field="remark">
            <a-input v-model="formData.remark"  placeholder="" />
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
import api from '../../api/member'

// 定义 props
const props = defineProps({
  pid: {
    type: [String, Number],
    default: null
  },
  buildtype: {
    type: [String, Number],
    default: null
  },
})

const emit = defineEmits(['success'])

// 状态管理
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

// 常量定义
const identityTypes = [
  { label: '家人', value: 2 },
  { label: '租户', value: 3 },
]

// 计算属性
const title = computed(() => {
  return '户主信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 表单数据
const formData = reactive({
  id: null,
  pid: props.pid || '',
  identity: '',
  buildtype: '',
  realname: '',
  buildname: '',
  mobile: '',
  idcard: '',
  remark: '',
})

// 验证规则
const rules = {
  identity: [{ required: true, message: '身份必须填写' }],
  realname: [{ required: true, message: '姓名必需填写' }],
  mobile: [{ required: true, message: '手机必需填写' }],
}

// 方法定义
const setFormData = async (data) => {
  Object.keys(formData).forEach(key => {
    if (data[key] !== null && data[key] !== undefined) {
      formData[key] = data[key]
    }
  })
}

const open = async (type = 'add', id = '', pid = null,buildtype = null) => {
  mode.value = type
  formRef.value?.clearValidate()
  visible.value = true

  // 设置 pid
  if (pid) {
    formData.pid = pid
  } else if (props.pid) {
    formData.pid = props.pid
  }
  if (buildtype) {
    formData.buildtype = buildtype
  } else if (props.buildtype) {
    formData.buildtype = props.buildtype
  }
  if (type === 'edit') {
    try {
      const { data } = await api.read(id)
      setFormData(data)
    } catch (error) {
      Message.error('获取数据失败')
      console.error(error)
    }
  }
}

const submit = async (done) => {
  try {
    const validate = await formRef.value?.validate()
    if (validate) {
      done(false)
      return
    }

    loading.value = true
    const data = { ...formData }
    let result = {}

    if (mode.value === 'add') {
      result = await api.addFamily(data)
    } else {
      result = await api.editFamily(data.id, data)
    }

    if (result.code === 200) {
      Message.success('操作成功')
      emit('success')
      done(true)
    } else {
      Message.error(result.message || '操作失败')
      done(false)
    }
  } catch (error) {
    Message.error('操作异常')
    console.error(error)
    done(false)
  } finally {
    loading.value = false
  }
}

const close = () => {
  visible.value = false
}

defineExpose({ open, setFormData })
</script>
