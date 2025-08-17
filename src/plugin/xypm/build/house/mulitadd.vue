<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="600"
      title="批量添加房屋"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">

      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="楼宇" field="build_id" required>
            <a-select
                v-model="formData.build_id"
                placeholder="请选择楼宇"
                allow-search
                allow-clear
                :field-names="{ key: 'id', title: 'name' }"
                :loading="buildLoading"

            >
              <a-option v-for="item in buildOptions" :key="item.id" :value="item.id">
                {{ item.name }}
              </a-option>
            </a-select>
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="单元户数" field="unithouse">
            <a-input-number v-model="formData.unithouse" placeholder="请输入数字" />
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/build/house'
import buildApi from "@/views/xypm/api/build.js";
const emit = defineEmits(['success'])
const visible = ref(false)
const loading = ref(false)
const buildLoading = ref(false)
const buildOptions = ref([])
const formRef = ref()
const formData = reactive({
  build_id: '',
  unithouse: '',

})

const rules = {
  build_id: [{ required: true, message: '楼宇不能为空' }],
  unithouse: [{ required: true, message: '单元户数不能为空' }],

}

const initPage = async () => {
  const deptResp = await buildApi.getBuildOptions()
  buildOptions.value = deptResp.data

}

const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }
    let result = {}
    data.id = undefined
    result = await api.multiaddHouse(data)
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
const close = () => {
  visible.value = false
  formRef.value.resetFields()
}

defineExpose({ open: () => (visible.value = true) })
initPage()
</script>
