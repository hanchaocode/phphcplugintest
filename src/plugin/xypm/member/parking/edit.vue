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
          <a-form-item label="车位" field="build_id" required>
            <a-select
                v-model="formData.build_id"
                placeholder="请选择车位"
                allow-search
                allow-clear
                :field-names="{ key: 'id', title: 'buildname' }"
                :loading="buildLoading"
                @change="handleOptionChange"

            >
              <a-option v-for="item in houseOptions" :key="item.id" :value="item.id">
                {{ item.buildname }}
              </a-option>
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
          <a-form-item label="建筑面积" field="buildarea">
            <a-input v-model="formData.buildarea" min="1" placeholder="请输入建筑面积" />
          </a-form-item>
        </a-col>
        <a-col :span="24">
          <a-form-item label="开始收费日期" field="billdate">
            <a-date-picker v-model="formData.billdate" placeholder="选择日期" />
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
import parkingApi from "@/views/xypm/api/build/parking";

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')
const buildLoading = ref(false)
const houseOptions = ref([])


let title = computed(() => {
  return '房屋户主信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 表单信息
const formData = reactive({
  id: null,
  build_id: '',
  realname: '',
  buildname:'',
  mobile: '',
  idcard: '',
  buildtype: 'parking',
  buildarea: '',
  billdate: '',
  remark: '',
})

// 验证规则
const rules = {
  build_id: [{ required: true, message: '请选择房屋' }],
  realname: [{ required: true, message: '姓名必需填写' }],
  mobile: [{ required: true, message: '手机必需填写' }],
  buildarea: [{ required: true, message: '建筑面积必需填写' }],
  billddte: [{ required: true, message: '开始收费日期必需选择' }],
}


const initPage = async () => {
  const parkingResp = await parkingApi.getParkingOption()
  houseOptions.value = parkingResp.data


}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  // 重置表单数据

  formRef.value.clearValidate()
  visible.value = true

  initPage()
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

const handleOptionChange = async (selectedId) => {
    // console.log(selectedId)
  const selectedOption = houseOptions.value.find(item => item.id === selectedId)

  if (selectedOption) {
    formData.buildname = selectedOption.buildname
  } else {
    formData.buildname = '' // 如果没有找到匹配项，清空 buildname
  }
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
      result = await api.addMember(data)
    } else {
      // 修改数据
      result = await api.editMember(data.id, data)
    }
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
