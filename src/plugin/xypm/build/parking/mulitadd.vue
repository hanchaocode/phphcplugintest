<template>
  <a-modal
      v-model:visible="visible"
      title="批量添加车位"
      :width="600"
      :mask-closable="false"
      @cancel="close"
      @ok="submit"
  >
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="车位区域" field="build_area_id" required>
            <a-select
                v-model="formData.build_area_id"
                placeholder="请输入车位区域"
                allow-search
                allow-clear
                :field-names="{ key: 'id', title: 'name' }"
                :loading="areaLoading"

            >
              <a-option v-for="item in areaOptions" :key="item.id" :value="item.id">
                {{ item.name }}
              </a-option>
            </a-select>
          </a-form-item>

        </a-col>
        <a-col :span="24">
          <a-form-item label="编号前缀" field="nameprefix">
            <a-input v-model="formData.nameprefix" placeholder="如:A或B-，可不填写" />
          </a-form-item>
        </a-col>

        <a-col :span="24">
          <a-form-item label="开始编号" field="startnum" required>
            <a-input-number v-model="formData.startnum" placeholder="请输入数字" />
          </a-form-item>
        </a-col>

        <a-col :span="24">
          <a-form-item label="结束编号" field="endnum" required>
            <a-input-number v-model="formData.endnum" placeholder="请输入数字" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="编号后缀" field="namesuffix">
            <a-input v-model="formData.namesuffix" placeholder="如:A或B-，可不填写" />
          </a-form-item>
        </a-col>


      </a-row>
    </a-form>
  </a-modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/build/parking'
import areaApi from '../../api/build/area' // 商铺区域API


const emit = defineEmits(['success'])
const visible = ref(false)
const formRef = ref()
const formData = reactive({
  build_area_id: '',
  nameprefix: '',
  namesuffix: '',
  startnum: null,
  endnum: null,
  suffix: '',
  floor: null,
})

const areaLoading = ref(false)
const areaOptions = ref([])

const rules = {

  startnum: [{ required: true, message: '开始编号必需填写' }],
  endnum: [{ required: true, message: '结束编号必需填写' }],

}

// 搜索商铺区域
const handleAreaSearch = async (value) => {
  areaLoading.value = true
  try {
    const res = await areaApi.getAreaList({ name: value })
    if (res.code === 200) {
      areaOptions.value = res.data.data || []
    }
  } finally {
    areaLoading.value = false
  }
}

const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    const data = { ...formData }
    const result = await api.multiaddParking(data)
    if (result.code === 200) {
      Message.success('批量添加成功')
      emit('success')

      close()
    } else {
      Message.error('批量添加失败')
    }
  }

}

const initPage = async () => {
  const areaResp = await areaApi.getAreaOptions({'type':'parking'})
  areaOptions.value = areaResp.data

}

const close = () => {
  visible.value = false
  formRef.value.resetFields()
}
initPage()
defineExpose({ open: () => (visible.value = true) })
</script>
