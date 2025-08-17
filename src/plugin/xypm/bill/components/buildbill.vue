<template>
  <component
      is="a-modal"
      v-model:visible="visible"
      :width="600"
      title="生成账单"
      :mask-closable="false"
      :ok-loading="loading"
      @cancel="close"
      @before-ok="submit"
  >
    <a-alert>
      温馨提示：<br/>
      1.请先在配置中心-》收费配置中配置好物业费收费标准在生成账单。<br/>
      2.只有已录入业主信息的{{ buildTypeText }}才会生成账单{{ buildtype !== 'parking' ? '建筑面积填0' + buildTypeText + '不会生成账单' : '' }}。<br/>
      3.账单以月为单位生成,单个{{ buildTypeText }}一次最多生成12个月的账单。
    </a-alert>
    <a-form ref="formRef" :model="formData" :rules="rules" :auto-label-width="true">

      <a-row :gutter="10">
        <a-col :span="24">
          <a-col :span="24">
            <a-form-item label="截止时间" field="enddate">
              <a-date-picker v-model="formData.enddate"    format="YYYY-MM"
                             value-format="YYYY-MM" placeholder="选择日期" />
            </a-form-item>
          </a-col>
        </a-col>

      </a-row>
    </a-form>
    <!-- 表单信息 end -->
  </component>
</template>

<script setup>
import { ref, reactive,computed } from 'vue'
import { Message } from '@arco-design/web-vue'
import api from '../../api/bill'
import buildApi from "@/views/xypm/api/build.js";


const emit = defineEmits(['success'])
const visible = ref(false)
const loading = ref(false)
const buildLoading = ref(false)
const buildOptions = ref([])
const formRef = ref()
const buildtype = ref('') // 新增

const formData = reactive({
  buildtype: '', // 这个可以保留或删除，根据您的实际需求
  enddate: '',
})

const buildTypeMap = {
  '房屋':'house',
  '商铺':'shop',
  '车位':'parking',
  '储物间':'garge'

}

const buildTypeText = computed(() => {
  formData.buildtype = buildTypeMap[buildtype.value]
  return buildtype.value
})
const rules = {

  enddate: [{ required: true, message: '截至时间不能为空' }],

}

const initPage = async () => {


}

const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }
    let result = {}
    data.id = undefined

    result = await api.buildBill(data)
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
// 修改 open 方法接收参数
const open = (type) => {
  buildtype.value = type
  visible.value = true
}

defineExpose({ open })

initPage()
</script>
