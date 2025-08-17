<template>
  <a-modal
      v-model:visible="visible"
      title="批量添加商铺区域"
      :width="600"
      :mask-closable="false"
      @cancel="close"
      @ok="submit"
  >
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">
      <a-row :gutter="10">
        <a-col :span="24">
          <a-form-item label="商铺区域" field="build_area_id" required>
            <a-select
                v-model="formData.build_area_id"
                placeholder="请选择商铺区域"
                allow-search
                allow-clear
                :loading="areaLoading"
                @search="handleAreaSearch"
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
          <a-form-item label="编号后缀" field="suffix">
            <a-input v-model="formData.suffix" placeholder="如无后缀，可不填写" />
          </a-form-item>
        </a-col>

        <a-col :span="24">
          <a-form-item label="所在楼层" field="floor" required>
            <a-input-number v-model="formData.floor" placeholder="请输入数字" />
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
  </a-modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/build/shop'
import areaApi from '../../api/build/area' // 商铺区域API
const emit = defineEmits(['success'])
const visible = ref(false)
const formRef = ref()
const formData = reactive({
  build_area_id: '',
  nameprefix: '',
  startnum: null,
  endnum: null,
  suffix: '',
  floor: null,
})

const areaLoading = ref(false)
const areaOptions = ref([])

const rules = {
  build_area_id: [{ required: true, message: '商铺区域必需选择' }],
  startnum: [{ required: true, message: '开始编号必需填写' }],
  endnum: [{ required: true, message: '结束编号必需填写' }],
  floor: [{ required: true, message: '所在楼层必需填写' }],
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

const initPage = async () => {
  const areaResp = await areaApi.getAreaOptions({'type':'shop'})
  areaOptions.value = areaResp.data

}


const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    const data = { ...formData }
    const result = await api.multiaddShop(data)
    if (result.code === 200) {
      Message.success('批量添加成功')
      emit('success')
      done(true)
      close()
    } else {
      Message.error('批量添加失败')
    }
  }
}

const close = () => {
  visible.value = false
  formRef.value.resetFields()
}
initPage()
defineExpose({ open: () => (
    visible.value = true) })
</script>
