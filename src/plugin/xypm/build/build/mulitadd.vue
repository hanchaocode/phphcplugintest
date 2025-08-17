<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="600"
      title="批量添加楼宇"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="楼宇类型" field="status">
            <a-radio-group v-model="formData.status">
              <a-radio value="high">高层</a-radio>
              <a-radio value="multilayer">多层</a-radio>
              <a-radio value="villa">别墅</a-radio>
            </a-radio-group>
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="名称前缀" field="nameprefix">
            <a-input v-model="formData.nameprefix" placeholder="如:第或A-，可不填写" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="开始编号" field="startnum">
            <a-input-number v-model="formData.startnum" placeholder="请输入数字" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="结束编号" field="endnum">
            <a-input-number v-model="formData.endnum" placeholder="请输入数字" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="名称后缀" field="suffix">
            <a-input v-model="formData.suffix" placeholder="如:栋或幢，可不填写" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="单元数量" field="unitnum">
            <a-input-number v-model="formData.unitnum" placeholder="请输入数字" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="楼宇层数" field="floornum">
            <a-input-number v-model="formData.floornum" placeholder="请输入数字" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="房屋数量" field="roomnum">
            <a-input-number v-model="formData.roomnum" placeholder="请输入数字" />
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
import api from '../../api/build'

const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const formData = reactive({
  status: 'high',
  nameprefix: '',
  startnum: '',
  endnum: '',
  suffix: '',
  unitnum: '',
  floornum: '',
  roomnum: '',
})

const rules = {
  startnum: [{ required: true, message: '开始编号必需填写' }],
  endnum: [{ required: true, message: '结束编号必需填写' }],
  unitnum: [{ required: true, message: '单元数量必需填写' }],
  floornum: [{ required: true, message: '楼宇层数必需填写' }],
  roomnum: [{ required: true, message: '房屋数量必需填写' }],
}

const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }
    let result = {}
    data.id = undefined
    result = await api.multiaddBuild(data)
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
</script>
