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
          <a-form-item label="用户名" field="username" required>
            <a-input v-model="formData.username" placeholder="请输入用户名" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="昵称" field="nickname">
            <a-input v-model="formData.nickname" placeholder="请输入昵称" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="电子邮箱" field="email" required>
            <a-input v-model="formData.email" placeholder="请输入电子邮箱" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="手机号" field="mobile">
            <a-input v-model="formData.mobile" placeholder="请输入手机号" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="性别" field="gender">
            <a-radio-group v-model="formData.gender">
              <a-radio :value="1">男</a-radio>
              <a-radio :value="2">女</a-radio>
              <a-radio :value="0">保密</a-radio>
            </a-radio-group>
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="生日" field="birthday">
            <a-date-picker
                v-model="formData.birthday"
                format="YYYY-MM-DD"
                placeholder="请选择生日"
                style="width: 100%"
            />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="头像" field="avatar">
            <sa-upload-image v-model="formData.avatar" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="密码" field="password">
            <a-input type="password" v-model="formData.password" placeholder="请输入密码" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">

        <a-col :span="12">
          <a-form-item label="格言" field="bio">
            <a-input v-model="formData.bio" placeholder="请输入格言" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="状态" field="status">
            <a-radio-group v-model="formData.status">
              <a-radio value="normal">正常</a-radio>
              <a-radio value="hidden">禁用</a-radio>
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
import api from '../../api/user'

const emit = defineEmits(['success'])
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

let title = computed(() => {
  return '用户信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})

const formData = reactive({
  id: null,
  username: '',
  nickname: '',
  email: '',
  mobile: '',
  avatar: '',
  gender: 0,
  birthday: '',
  password: '',
  money: 0.00,
  bio: '',
  status: 'normal'
})

const rules = {
  username: [{ required: true, message: '用户名不能为空' }],
  email: [{ required: true, message: '电子邮箱不能为空' }]
}

const open = async (type = 'add', id = '') => {
  mode.value = type
  formRef.value?.clearValidate()
  visible.value = true

  if (type === 'add') {
    Object.assign(formData, {
      id: null,
      username: '',
      nickname: '',
      email: '',
      mobile: '',
      avatar: '',
      gender: 0,
      birthday: '',
      password: '',
      money: 0.00,
      bio: '',
      status: 'normal'
    })
  } else {
    const { data } = await api.read(id)
    setFormData(data)
  }
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

    // 如果密码为空，表示不修改密码
    if (!data.password) {
      delete data.password
    }

    try {
      let result = {}
      if (mode.value === 'add') {
        data.id = undefined
        result = await api.addUser(data)
      } else {
        result = await api.editUser(data.id, data)
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

const close = () => (visible.value = false)

defineExpose({ open, setFormData })
</script>

<style scoped>
:deep(.arco-form-item-label-col) {
  flex-basis: 100px;
}
</style>
