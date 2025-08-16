<template>
  <a-modal
    v-model:visible="visible"
    :title="title"
    @ok="handleSubmit"
    @cancel="handleReset"
  >
    <a-alert type="info" style="margin-bottom: 10px;">
      <template #message>
        <b>温馨提示</b><br>
        应用key和密钥你可以通过 https://ram.console.aliyun.com/manage/ak 获取
      </template>
    </a-alert>

    <a-form
      ref="formRef"
      :model="formData"
      layout="vertical"
      class="edit-form"
    >
      <a-card :bordered="false">
        <a-form-item label="应用key" name="key">
          <a-input v-model="formData.key" placeholder="请输入应用key" />
        </a-form-item>

        <a-form-item label="密钥secret" name="secret">
          <a-input v-model="formData.secret" placeholder="请输入密钥secret" />
        </a-form-item>

        <a-form-item label="签名" name="sign">
          <a-input v-model="formData.sign" placeholder="请输入签名" />
        </a-form-item>

        <a-form-item label="短信模板">
          <div v-for="(item, index) in formData.template" :key="index" class="template-item">
            <a-space>
              <a-input
                v-model="item.key"
                placeholder="模板键名"
                style="width: 150px"
              />
              <a-input
                v-model="item.value"
                placeholder="模板值"
                style="width: 200px"
              />
              <a-button
                type="text"
                danger
                @click="removeTemplate(index)"
              >
                <template #icon><icon-delete /></template>
              </a-button>
            </a-space>
          </div>
          <a-button type="dashed" @click="addTemplate">
            <template #icon><icon-plus /></template>
            添加模板
          </a-button>
        </a-form-item>
      </a-card>
    </a-form>

    <template #footer>
      <a-button @click="handleReset">重置</a-button>
      <a-button type="primary" @click="handleSubmit">确定</a-button>
    </template>
  </a-modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../api/config'

const visible = ref(false)
const title = ref('')
const formRef = ref()
const formData = reactive({
  key: 'your key',
  secret: 'your secret',
  sign: 'your sign',
  template: [
    { key: 'register', value: 'SMS_114000000' },
    { key: 'resetpwd', value: 'SMS_114000000' },
    { key: 'changepwd', value: 'SMS_114000000' },
    { key: 'changemobile', value: 'SMS_114000000' },
    { key: 'profile', value: 'SMS_114000000' },
    { key: 'notice', value: 'SMS_114000000' },
    { key: 'mobilelogin', value: 'SMS_114000000' },
    { key: 'bind', value: 'SMS_114000000' }
  ]
})

// 打开弹窗
const open = (type) => {
  visible.value = true
  title.value = type === 'edit' ? '编辑配置' : '新增配置'
}

// 设置表单数据
const setFormData = (data) => {
  Object.assign(formData, data)
  // 如果模板是JSON字符串，需要转换为数组
  if (typeof data.template === 'string') {
    try {
      const templateObj = JSON.parse(data.template)
      formData.template = Object.keys(templateObj).map(key => ({
        key,
        value: templateObj[key]
      }))
    } catch (e) {
      formData.template = []
    }
  }
}

// 添加模板
const addTemplate = () => {
  formData.template.push({ key: '', value: '' })
}

// 删除模板
const removeTemplate = (index) => {
  formData.template.splice(index, 1)
}

// 提交表单
const handleSubmit = async () => {
  try {
    // 转换模板数据为JSON字符串
    const submitData = {
      ...formData,
      template: JSON.stringify(
        formData.template.reduce((obj, item) => {
          if (item.key && item.value) {
            obj[item.key] = item.value
          }
          return obj
        }, {})
      )
    }

    const resp = title.value === '新增配置' 
      ? await api.save(submitData)
      : await api.update(submitData)

    if (resp.code === 200) {
      Message.success(resp.message)
      visible.value = false
      emit('success')
    }
  } catch (error) {
    console.error(error)
  }
}

// 重置表单
const handleReset = () => {
  formRef.value?.resetFields()
}

defineExpose({ open, setFormData })
</script>

<style scoped>
.template-item {
  margin-bottom: 10px;
}
</style>