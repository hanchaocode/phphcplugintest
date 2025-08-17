<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="600"
       title="通用配置"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <a-form ref="formRef" :model="formData"  :auto-label-width="true">
      <a-form-item label="主色调">
        <div class="flex">
          <a-input v-model="formData.mainColor" />
          <input type="color" v-model="formData.mainColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
        </div>
      </a-form-item>

      <a-form-item label="导航栏背景颜色">
        <div class="flex">
          <a-input v-model="formData.navBarBgColor" />
          <input type="color" v-model="formData.navBarBgColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
        </div>
      </a-form-item>

      <a-form-item label="导航栏字体颜色">
        <a-select v-model="formData.navBarFrontColor" style="width: 100%;">
          <a-option value="#ffffff">白色</a-option>
          <a-option value="#000000">黑色</a-option>
        </a-select>
      </a-form-item>

      <a-form-item label="页面背景颜色">
        <div class="flex">
          <a-input v-model="formData.pageBgColor" />
          <input type="color" v-model="formData.pageBgColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
        </div>
      </a-form-item>

      <a-form-item label="文字主色调">
        <div class="flex">
          <a-input v-model="formData.textMainColor" />
          <input type="color" v-model="formData.textMainColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
        </div>
      </a-form-item>

      <a-form-item label="文字浅色调">
        <div class="flex">
          <a-input v-model="formData.textLightColor" />
          <input type="color" v-model="formData.textLightColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
        </div>
      </a-form-item>

      <a-form-item label="价格文字颜色">
        <div class="flex">
          <a-input v-model="formData.textPriceColor" />
          <input type="color" v-model="formData.textPriceColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
        </div>
      </a-form-item>

      <a-form-item label="页面模块背景颜色">
        <div class="flex">
          <a-input v-model="formData.pageModuleBgColor" />
          <input type="color" v-model="formData.pageModuleBgColor" class="ant-input" style="width: 40px; height: 32px; margin-left: 8px">
        </div>
      </a-form-item>


    </a-form>
  </component>
</template>

<script setup>
import {reactive, ref} from 'vue';
import { Message } from '@arco-design/web-vue'
import api from '../api/config'

const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const emit = defineEmits(['success'])

const formData = reactive({
  mainColor: '',
  navBarBgColor: '',
  navBarFrontColor: '#ffffff',
  pageBgColor: '',
  textMainColor: '',
  textLightColor: '',
  textPriceColor: '',
  pageModuleBgColor: ''
});

// 打开弹框
const open = async (type = 'edit', id = '') => {

  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true


    const res = await api.readConfig('appstyle')

      setFormData(res.data)
    // setFormData(res.data)

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
    const submitData = {
      name: 'appstyle',
      group: 'other',
      value: JSON.stringify(data),
      type: 'array'
    };

    console.log(submitData)

    const result = await api.editConfig(submitData);

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

<style scoped>
.form-group {
  margin-bottom: 15px;
}
.control-label {
  text-align: right;
  padding-right: 10px;
}
.form-inline .form-control {
  margin-right: 10px;
}
</style>
