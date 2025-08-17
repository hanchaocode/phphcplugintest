<template>
  <a-modal
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
          <a-form-item label="商铺区域" field="build_area_id" required>
            <a-select
                v-model="formData.build_area_id"
                placeholder="请输入商铺区域"
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
          <a-form-item label="商铺编号" field="number">
            <a-input v-model="formData.number" placeholder="请输入商铺编号" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="所在楼层" field="floor">
            <a-input v-model="formData.floor" placeholder="请输入所在楼层" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="户主姓名" field="ownername">
            <a-input v-model="formData.ownername" placeholder="请输入户主姓名" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="户主电话" field="mobile">
            <a-input v-model="formData.mobile" placeholder="请输入户主电话" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="建筑面积" field="buildarea">
            <a-input v-model="formData.buildarea" min="0" placeholder="请输入建筑面积" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="开始收费日期" field="billdate">
            <a-date-picker v-model="formData.billdate" placeholder="选择日期" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="状态" field="status">
            <a-select v-model="formData.status"  dict="data_status" placeholder="请选择状态">
              <a-option  v-for="type in statusTypes" :key="type.value" :value="type.value">   {{ type.label }}</a-option>
            </a-select>
          </a-form-item>
        </a-col>
      </a-row>
    </a-form>
    <!-- 表单信息 end -->
  </a-modal>
</template>

<script setup>
import { ref, reactive, computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/build/shop.js'
import areaApi from "@/views/xypm/api/build/area.js";

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')
const areaLoading = ref(false)
const areaOptions = ref([])
let title = computed(() => {
  return '商铺信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})
const statusTypes = [
  { label: '未绑定户主', value: 0 },
  { label: '已绑定户主', value: 1 },

]
// 表单信息
const formData = reactive({
  id: null,
  build_area_id: '',
  number: '',
  floor: '',
  ownername: '',
  mobile: '',
  buildarea: 0,
  billdate: '',
  status: 0,
})

// 验证规则
const rules = {
  build_area_id: [{ required: true, message: '商铺区域必需填写' }],
  number: [{ required: true, message: '商铺编号必需填写' }],
  ownername: [{ required: true, message: '户主姓名必需填写' }],
  mobile: [{ required: true, message: '户主电话必需填写' }],
  buildarea: [{ required: true, message: '建筑面积必需填写' }],
  status: [{ required: true, message: '状态必需选择' }],
}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  formRef.value.clearValidate()
  visible.value = true
  // 初始化区域选项
  await initPage()
  if (type == 'edit') {
    const { data } = await api.read(id)
    data.status = parseInt(data.status)
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

const initPage = async () => {
  const areaResp = await areaApi.getAreaOptions({'type':'shop'})
  areaOptions.value = areaResp.data

}


// 数据保存
const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }
    let result = {}
    if (mode.value === 'add') {
      // 添加数据
      data.id = undefined
      result = await api.addShop(data)
    } else {
      // 修改数据
      result = await api.editShop(data.id, data)
    }
    if (result.code === 200) {
      Message.success('操作成功')
      emit('success')
      done(true)
    }
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
