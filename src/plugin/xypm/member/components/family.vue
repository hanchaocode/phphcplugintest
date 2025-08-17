<template>
  <a-modal v-model:visible="visible" fullscreen :footer="false" @close="handleClose">
    <template #title>成员列表</template>

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
        <a-col :sm="8" :xs="24">
          <a-form-item field="status" label="状态">
            <sa-select v-model="searchForm.status" dict="data_status" placeholder="请选择状态" allow-clear />
          </a-form-item>
        </a-col>
      </template>


      <!-- 操作前置扩展 -->
      <template #tableBeforeButtons>
        <a-button type="primary"   @click="openAddModal()">
          <template #icon> <icon-plus /> </template> 增加
        </a-button>
      </template>
    </sa-table>

    <!-- 编辑表单 -->
    <edit-form ref="editRef" :pid="familyId" @success="refresh"  />
  </a-modal>
</template>

<script setup>
import { ref, reactive } from 'vue'
import api from '../../api/member'
import { Message } from '@arco-design/web-vue'
import EditForm from "@/views/xypm/member/components/editfamily.vue";
// 定义 props


const emit = defineEmits(['success'])

const visible = ref(false)
const crudRef = ref()
const familyId = ref()
const editRef = ref()
const users = ref([])

// 搜索表单
const searchForm = ref({
  realname: '',
  mobile: '',
  pid: null,
  status: '',
})
// 打开添加


const openAddModal = (record) => {
  editRef.value.open(record, familyId.value) // 传递 pid
}
// 打开弹框
const open = (row) => {
  familyId.value = row.id
  visible.value = true
  searchForm.value.pid = familyId.value
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
  api: api.getFamilyList,
  rowSelection: { showCheckedAll: true },
  singleLine: true,
  delete: {
    show: true,
    auth: ['/app/xypm/member/family/destroy'],
    func: async (params) => {
      const resp = await api.deleteMember(params)
      if (resp.code === 200) {
        Message.success(`删除成功！`)
        crudRef.value?.refresh()
      }
    },
  },
})

// SaTable 列配置
const columns = reactive([
  { title: '身份', dataIndex: 'identity_text' },
  { title: '姓名', dataIndex: 'realname' },
  { title: '手机', dataIndex: 'phone' },
  { title: '身份证号', dataIndex: 'idcard' },
  { title: '状态', dataIndex: 'status_text', width: 120 },
])

defineExpose({ open })
</script>
