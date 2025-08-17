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
        <a-col :span="24">
          <a-form-item label="标题" field="title" required>
            <a-input v-model="formData.title" placeholder="请输入活动标题" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="封面图" field="image">
            <sa-upload-image v-model="formData.image" />
            <div class="text-xs text-gray-500 mt-1">支持格式：JPG、PNG、GIF，建议尺寸：800x400px</div>
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="报名开始时间" field="signup_start_time" required>
            <a-date-picker
                v-model="formData.signup_start_time"
                show-time
                format="YYYY-MM-DD HH:mm"
                placeholder="请选择报名开始时间"
                style="width: 100%"
            />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="报名结束时间" field="signup_end_time" required>
            <a-date-picker
                v-model="formData.signup_end_time"
                show-time
                format="YYYY-MM-DD HH:mm"
                placeholder="请选择报名结束时间"
                style="width: 100%"
            />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="活动开始时间" field="start_time" required>
            <a-date-picker
                v-model="formData.start_time"
                show-time
                format="YYYY-MM-DD HH:mm"
                placeholder="请选择活动开始时间"
                style="width: 100%"
            />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="活动结束时间" field="end_time" required>
            <a-date-picker
                v-model="formData.end_time"
                show-time
                format="YYYY-MM-DD HH:mm"
                placeholder="请选择活动结束时间"
                style="width: 100%"
            />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="主办单位" field="organizer">
            <a-input v-model="formData.organizer" placeholder="请输入主办单位名称" />
          </a-form-item>
        </a-col>
        <a-col :span="12">
          <a-form-item label="活动人数" field="number">
            <a-input-number
                v-model="formData.number"
                placeholder="请输入活动人数"
                :min="0"
                style="width: 100%"
            />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="12">
          <a-form-item label="省市区" field="city">

                <ma-cityLinkage type="cascader" v-model="formData.city"/>
          </a-form-item>
        </a-col>

      </a-row>
      <a-row :gutter="16">

        <a-col :span="24">
          <a-form-item label="详细地址" field="address">
            <a-input v-model="formData.address" placeholder="请输入详细地址" />
          </a-form-item>
        </a-col>
      </a-row>
      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="活动内容" field="content">
            <a-textarea v-model="formData.content" height="400px" />
          </a-form-item>
        </a-col>
      </a-row>

      <a-row :gutter="16">
        <a-col :span="24">
          <a-form-item label="状态" field="status">
            <a-radio-group v-model="formData.status">
              <a-radio value="normal">正常</a-radio>
              <a-radio value="hidden">隐藏</a-radio>
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
import api from '../../api/activity/activity' // 调整为活动API
// import cityOptions from '@/utils/city' // 假设有城市数据

const emit = defineEmits(['success'])
// 引用定义
const visible = ref(false)
const loading = ref(false)
const formRef = ref()
const mode = ref('')

let title = computed(() => {
  return '活动信息' + (mode.value === 'add' ? '-新增' : '-编辑')
})

// 表单信息
const formData = reactive({
  id: null,
  title: '',
  image: '',
  signup_start_time: '',
  signup_end_time: '',
  start_time: '',
  end_time: '',
  organizer: '',
  number: 0,
  city: [],
  address: '',
  content: '',
  status: 'normal'
})

// 验证规则
const rules = {
  title: [{ required: true, message: '标题不能为空' }],
  signup_start_time: [{ required: true, message: '请选择报名开始时间' }],
  signup_end_time: [{ required: true, message: '请选择报名结束时间' }],
  start_time: [{ required: true, message: '请选择活动开始时间' }],
  end_time: [{ required: true, message: '请选择活动结束时间' }],
  content: [{ required: true, message: '活动内容不能为空' }]
}

// 打开弹框
const open = async (type = 'add', id = '') => {
  mode.value = type
  formRef.value?.clearValidate()
  visible.value = true

  // 重置表单数据
  if (type === 'add') {
    Object.assign(formData, {
      id: null,
      title: '',
      image: '',
      signup_start_time: '',
      signup_end_time: '',
      start_time: '',
      end_time: '',
      organizer: '',
      number: 0,
      city: [],
      address: '',
      content: '',
      status: 'normal'
    })
  } else {
    const { data } = await api.read(id)
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

  // 处理城市数据，假设后端返回的是字符串格式"省/市/区"
  if (data.city) {

    formData.city = data.city.split('/')
  }
}

// 数据保存
const submit = async (done) => {
  const validate = await formRef.value?.validate()
  if (!validate) {
    loading.value = true
    let data = { ...formData }

    // 处理城市数据为字符串格式
    if (Array.isArray(data.city)) {
      data.city = data.city.join('/')
    }

    try {
      let result = {}
      if (mode.value === 'add') {
        data.id = undefined
        result = await api.addActivity(data)
      } else {
        result = await api.editActivity(data.id, data)
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

// 关闭弹窗
const close = () => (visible.value = false)

defineExpose({ open, setFormData })
</script>

<style scoped>
:deep(.arco-form-item-label-col) {
  flex-basis: 100px;
}
</style>