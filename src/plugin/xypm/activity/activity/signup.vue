<template>
  <a-modal v-model:visible="visible" fullscreen :footer="false" @close="handleClose">
    <template #title>报名管理</template>

    <sa-table ref="crudRef" :options="options" :columns="columns" :searchForm="searchForm">
      <!-- 搜索区 tableSearch -->
      <template #tableSearch>
        <a-col :sm="8" :xs="24">
          <a-form-item field="realname" label="姓名">
            <a-input v-model="searchForm.realname" placeholder="请输入姓名" allow-clear />
          </a-form-item>
        </a-col>
        <a-col :sm="8" :xs="24">
          <a-form-item field="mobile" label="手机号">
            <a-input v-model="searchForm.nickname" placeholder="请输入手机号" allow-clear />
          </a-form-item>
        </a-col>

      </template>



    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" :pid="familyId" @success="refresh"  />
  </a-modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import api from '../../api/activity/signup'
import { Message } from '@arco-design/web-vue'
import EditForm from "@/views/xypm/member/components/editfamily.vue";
// 定义 props


const emit = defineEmits(['success'])

const visible = ref(false)
const crudRef = ref()
const activityId = ref()
const editRef = ref()
const users = ref([])

// 搜索表单
const searchForm = ref({
  realname: '',
  mobile: '',
  activity_id: null,

})
// 打开添加


// 打开弹框
const open = (row) => {
  activityId.value = row.id
  visible.value = true
  searchForm.value.activity_id = activityId.value
  crudRef.value?.refresh()
}

// SaTable 数据请求
const refresh = async () => {
  crudRef.value?.refresh()
}


const handleClose = async () => {
  emit('success', true)
}

// SaTable 基础配置
const options = reactive({
  api: api.getSignupList,
  rowSelection: { showCheckedAll: true },
  singleLine: true,
  delete: {
    show: true,
    auth: ['/app/xypm/member/family/destroy'],
    func: async (params) => {
      const resp = await api.deleteSignup(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: 'id', dataIndex: 'ID' },
  { title: '姓名', dataIndex: 'realname' },
  { title: '电话', dataIndex: 'mobile' },
  { title: '房产名称', dataIndex: 'buildname' },

])

defineExpose({ open })
</script>
